<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
class AuthController extends Controller
{


 
    public function registrasiPelanggan()
    {
        return view('auth.registrasipelanggan',[
            'title'=> 'verif'
        ]);
    }
   private function kirimOtp($phone, $nama)
{
    $otp = rand(100000, 999999);
    Cache::put("otp_$phone", $otp, now()->addMinutes(5)); // expired dalam 5 menit

    // Ganti pake Japati / WhatsApp lu
    $pesan = "Kode OTP registrasi untuk $nama adalah: *$otp*. Berlaku 5 menit.";

    // Panggil fungsi WA
    kirimWa($phone, $pesan); // asumsinya lu udah punya fungsi kirimWa()
}
   public function submitRegistrasiPelanggan(Request $request)
{
 $validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'role' => 'required|string',
    'phone' => 'required|string|min:10|unique:users,phone',
    'password' => 'required|string|min:6',
    'avatar' => 'required|image|mimes:jpeg,png,jpg|max:5120',
    'id_card' => 'required|image|mimes:jpeg,png,jpg|max:5120',
]);

// Simpan path-nya aja
$avatarPath = $request->file('avatar')->store('avatar_temp', 'public');
$idCardPath = $request->file('id_card')->store('id_card_temp', 'public');

// Jangan sentuh objek file, simpen path-nya aja
$dataToStore = [
    'name' => $validated['name'],
    'email' => $validated['email'],
    'role' => $validated['role'],
    'phone' => $validated['phone'],
    'password' => $validated['password'],
    'avatar_path' => $avatarPath,
    'id_card_path' => $idCardPath,
];

session()->put('pending_register', $dataToStore);


    $this->kirimOtp($validated['phone'], $validated['name']);

    return redirect()->route('otp.form')->with('sukses', 'Kode OTP dikirim ke WhatsApp.');
}

public function formOtp()
{
    return view('auth.otp'); // pastiin ini file nya ada di `resources/views/auth/otp.blade.php`
}

public function submitOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6',
    ]);

    $data = session('pending_register');
    if (!$data) {
        return redirect()->route('registrasi.pelanggan')->with('gagal', 'Session habis, silakan daftar ulang.');
    }

    $cachedOtp = Cache::get("otp_" . $data['phone']);
    if ($request->otp != $cachedOtp) {
        return back()->with('gagal', 'Kode OTP salah atau sudah kedaluwarsa.');
    }

    $user = new User();
    $user->name = $data['name'];
    $user->email = $data['email'];
    $user->phone = $data['phone'];
    $user->role = $data['role'];
    $user->password = bcrypt($data['password']);
    $user->avatar = $data['avatar_path']; // bisa diubah pas verifikasi final
    $user->id_card = $data['id_card_path'];
    $user->email_verified_at = now();
    $user->save();

    Cache::forget("otp_" . $data['phone']);
    session()->forget('pending_register');

    auth()->login($user);
    return redirect()->route('home.index')->with('sukses', 'Registrasi berhasil!');
}

    public function login()
    {
        return view('auth.login');
    }

    public function submitLogin(Request $request)
    {
        $data = $request->only('email', 'password');

        if (Auth::attempt($data)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.index')->with('sukses', 'Berhasil login sebagai admin.');
            } elseif ($user->role === 'pemilik') {
                return redirect()->route('pemilik.index')->with('sukses', 'Berhasil login sebagai pemilik.');
            } elseif ($user->role === 'pelanggan') {
                return redirect()->route('home.index')->with('sukses', 'Berhasil login sebagai pelanggan.');
            } else {
                return redirect()->route('login')->with('gagal', 'Unauthorized access.');
            }
        } else {
            return redirect()->back()->withInput()->with('gagal', 'Email atau Password salah.');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->forget('home.index');
        $request->session()->regenerateToken();
        return redirect()->route('home.index')->with('sukses', 'Berhasil logout.');
    }
}
