<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Auth\MustVerifyEmail;

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
        Cache::put("otp_update_$phone", $otp, now()->addMinutes(5));
        $pesan = "Kode OTP untuk verifikasi nomor WA baru $nama adalah: *$otp*. Berlaku 5 menit.";
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
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'required|numeric',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_card'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;
  /** @var \App\Models\User|\Illuminate\Contracts\Auth\MustVerifyEmail $user */
        if ($request->email !== $user->email) {
            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        if ($request->phone !== $user->phone) {
            session(['pending_update' => [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'avatar' => $request->file('avatar'),
                'id_card' => $request->file('id_card'),
            ]]);

            $this->kirimOtp($request->phone, $request->name);
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

    public function submitOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $data = session('pending_update');
        if (!$data) {
            return redirect()->route('profile.edit')->with('gagal', 'Session habis, silakan coba lagi.');
        }

        $cachedOtp = Cache::get("otp_update_" . $data['phone']);
        if ($request->otp != $cachedOtp) {
            return back()->with('gagal', 'Kode OTP salah atau sudah kedaluwarsa.');
        }

        $user = Auth::user();
        $user->name = $data['name'];
        $user->email = $data['email'];
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

        Cache::forget("otp_update_" . $data['phone']);
        session()->forget('pending_update');

        return redirect()->route('profile.index')->with('success', 'Nomor berhasil diverifikasi dan profil diperbarui.');
    }
}
