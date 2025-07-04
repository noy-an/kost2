<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

     public function registrasiPelanggan()
    {
        return view('auth.registrasipelanggan');
    }
    public function submitRegistrasiPelanggan(Request $request)
    {
       $user = new User();
       $user->name = $request->name;
       $user->email = $request->email;
       $user->role = $request->role;
       $user->phone = $request->phone;
       $user->url_diri = $request->url_diri;
       $user->password =bcrypt($request->password);
        if ($request->hasFile('url_diri')) {
        $user->url_diri = $request->file('url_diri')->store('diri', 'public');
    }
       $user->save();   
       return redirect()->route('login')->with('sukses', 'Registration successful as owner. Please log in.');;
    }
      public function Registrasi()
    {
        return view('auth.registrasi');
    }
    public function submitRegistrasi(Request $request)
    {
       $user = new User();
       $user->name = $request->name;
       $user->email = $request->email;
       $user->role = $request->role;
       $user->phone = $request->phone;
       $user->password =bcrypt($request->password);
  if ($request->hasFile('url_ktp')) {
        $user->url_ktp = $request->file('url_ktp')->store('ktp', 'public');
    }

    if ($request->hasFile('url_npwp')) {
        $user->url_npwp = $request->file('url_npwp')->store('npwp', 'public');
    }

    if ($request->hasFile('url_diri')) {
        $user->url_diri = $request->file('url_diri')->store('diri', 'public');
    }
       $user->save();   
       return redirect()->route('login')->with('sukses', 'Registration successful as owner. Please log in.');;
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
                return redirect()->route('admin.index');
            } elseif ($user->role === 'pemilik') {
                return redirect()->route('pemilik.index');
            } elseif ($user->role === 'marketing') {
                return redirect()->route('marketing.index');
            } elseif ($user->role === 'customer') {
                return redirect()->route('home.index');
            } elseif ($user->role === 'resepsionis') {
                return redirect()->route('resepsionis.index');
            } else {
                return redirect()->route('login')->with('gagal', 'Unauthorized access.');
            }
        } else {
            return redirect()->back()->with('gagal', 'Email or Password is incorrect.');
        }
    }

     public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->forget('home.index'); 
        $request->session()->regenerateToken();
        return redirect()->route('home.index');
    }
}
