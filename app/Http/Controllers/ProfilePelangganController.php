<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Otp;
use Carbon\Carbon;


class ProfilePelangganController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'), [
            'title' => 'Profile'
        ]);
    }

  private function kirimOtp($phone, $nama)
{
    $otp = rand(100000, 999999);

    // Simpan ke DB
    Otp::create([
        'user_id'   => null,
        'phone'     => $phone,
        'code'      => $otp,
        'expired_at'=> Carbon::now()->addMinutes(1),
    ]);

    // Kirim WA
    $pesan = "Kode OTP untuk verifikasi nomor WA $nama adalah: *$otp*. Berlaku 1 menit.";
    kirimWa($phone, $pesan);
}

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'), [
            'title' => 'Profile'
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
    
            'phone'    => 'required|numeric',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_card'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;
  

       if ($request->phone !== $user->phone) {
    session(['pending_update' => [
        'step'   => 'verify_old', // step pertama
        'name'   => $request->name,
        'phone'  => $request->phone, // nomor baru
        'avatar' => $request->file('avatar'),
        'id_card'=> $request->file('id_card'),
    ]]);

          $this->kirimOtp($user->phone, $request->name);
         return redirect()->route('profile.otp');
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::delete($user->avatar);
            $user->avatar = $request->file('avatar')->store('avatar');
        }

        if ($request->hasFile('id_card')) {
            if ($user->id_card) Storage::delete($user->id_card);
            $user->id_card = $request->file('id_card')->store('id_card');
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function formOtp()
    {
        return view('profile.otp');
    }

   public function resendOtp()
{
    $data = session('pending_update');
    if (!$data) {
        return redirect()->route('profile.edit')->with('gagal', 'Session habis, silakan coba lagi.');
    }

    $user = Auth::user();

    if ($data['step'] === 'verify_old') {
        $this->kirimOtp($user->phone, $data['name']);
        return back()->with('success', 'OTP baru sudah dikirim ke nomor lama kamu.');
    }

    if ($data['step'] === 'verify_new') {
        $this->kirimOtp($data['phone'], $data['name']);
        return back()->with('success', 'OTP baru sudah dikirim ke nomor baru kamu.');
    }
}



    public function submitOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6',
    ]);

    $data = session('pending_update');
    if (!$data) {
        return redirect()->route('profile.edit')->with('gagal', 'Session habis, silakan coba lagi.');
    }

    $user = Auth::user();

    // Ambil OTP terakhir sesuai step
    $phoneCheck = $data['step'] === 'verify_old' ? $user->phone : $data['phone'];
    $otpRecord = Otp::where('phone', $phoneCheck)
        ->where('code', $request->otp)
        ->latest()
        ->first();

    if (!$otpRecord) {
        return back()->with('gagal', 'Kode OTP salah.');
    }

    // Cek expired
    if (Carbon::now()->greaterThan($otpRecord->expired_at)) {
        return back()->with('gagal', 'Kode OTP sudah kedaluwarsa, silakan kirim ulang.');
    }

    // Step 1 → verifikasi nomor lama
    if ($data['step'] === 'verify_old') {
        session(['pending_update' => array_merge($data, ['step' => 'verify_new'])]);
        $this->kirimOtp($data['phone'], $data['name']);
        return redirect()->route('profile.otp')->with('success', 'OTP sudah dikirim ke nomor baru, silakan verifikasi.');
    }

    // Step 2 → verifikasi nomor baru
    if ($data['step'] === 'verify_new') {
        $user->name = $data['name'];
        $user->phone = $data['phone'];

        if (isset($data['avatar'])) {
            if ($user->avatar) Storage::delete($user->avatar);
            $user->avatar = $data['avatar']->store('avatar');
        }

        if (isset($data['id_card'])) {
            if ($user->id_card) Storage::delete($user->id_card);
            $user->id_card = $data['id_card']->store('id_card');
        }

        $user->save();

        session()->forget('pending_update');

        return redirect()->route('profile.index')->with('success', 'Nomor berhasil diverifikasi dan profil diperbarui.');
    }
}

}
