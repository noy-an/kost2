<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http; // buat request ke API WA
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showForgetForm()
    {
        return view('auth.forgot-password');
    }

     public function sendResetLink(Request $request)
    {
        $request->validate(['phone' => 'required']); // ganti email jadi phone

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return back()->with('error', 'Nomor WA tidak ditemukan.');
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['phone' => $request->phone],
            ['token' => $token, 'created_at' => now()]
        );

        $resetLink = url('/reset-password?token=' . $token . '&phone=' . $request->phone);

        // Kirim lewat API WhatsApp
            // Kirim lewat helper kirimWa
        try {
            kirimWa($request->phone, "Klik link ini untuk reset password: $resetLink");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim link reset via WhatsApp.');
        }

        return back()->with('success', 'Link reset password telah dikirim via WhatsApp.');
    }

    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'phone' => $request->phone
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('phone', $request->phone)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->with('error', 'Token tidak valid.');
        }

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return back()->with('error', 'Nomor WA tidak ditemukan.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('phone', $request->phone)->delete();

        return redirect('login')->with('success', 'Password berhasil direset!');
    }
}

