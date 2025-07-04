<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function showForgetForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $User = User::where('email', $request->email)->first();
        if (!$User) {
            return back()->with('error', 'Email tidak ditemukan.');
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        $resetLink = url('/reset-password?token=' . $token . '&email=' . $request->email);

        Mail::raw("Klik link ini untuk reset password: $resetLink", function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password Pelanggan');
        });

        return back()->with('success', 'Link reset password telah dikirim ke email.');
    }

    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->with('error', 'Token tidak valid.');
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan.');
        }

        $user->password = Hash::make($request->password);

        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('login')->with('success', 'Password berhasil direset!');
    }
}

