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
        // VALIDASI INPUT
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'id_card' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // SIMPAN DATA
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->phone = $request->phone;
        $user->avatar = $request->avatar;
        $user->id_card = $request->id_card;
        $user->password = bcrypt($request->password);

        if ($request->hasFile('id_card')) {
            $user->id_card = $request->file('id_card')->store('id_card', 'public');
        }

        if ($request->hasFile('avatar')) {
            $user->avatar = $request->file('avatar')->store('avatar', 'public');
        }

        $user->save();
        return redirect()->route('login')->with('sukses', 'Registrasi telah berhasil. Silakan login');
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
            return redirect()->back()->with('gagal', 'Email or Password is incorrect.');
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

// namespace App\Http\Controllers;

// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class AuthController extends Controller
// {

//      public function registrasiPelanggan()
//     {
//         return view('auth.registrasipelanggan');
//     }

//     public function submitRegistrasiPelanggan(Request $request)
// {
//     // VALIDASI INPUT
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|email|unique:users,email',
//         'role' => 'required|string',
//         'phone' => 'required|string',
//         'password' => 'required|string|min:6',
//         'avatar' => 'required|image|mimes:jpeg,png,jpg|max:5120',
//         'id_card' => 'required|image|mimes:jpeg,png,jpg|max:5120',
//     ]);

//     // SIMPAN DATA
//     $user = new User();
//     $user->name = $request->name;
//     $user->email = $request->email;
//     $user->role = $request->role;
//     $user->phone = $request->phone;
//     $user->avatar = $request->avatar;
//     $user->id_card = $request->id_card;
//     $user->password = bcrypt($request->password);

//     if ($request->hasFile('id_card')) {
//         $user->id_card = $request->file('id_card')->store('id_card', 'public');
//     }

//     if ($request->hasFile('avatar')) {
//         $user->avatar = $request->file('avatar')->store('avatar', 'public');
//     }

//     $user->save();
//     return redirect()->route('login')->with('sukses', 'Registrasi telah berhasil.Silahkan login');
// }

    // public function submitRegistrasiPelanggan(Request $request)
    // {
    //    $user = new User();
    //    $user->name = $request->name;
    //    $user->email = $request->email;
    //    $user->role = $request->role;
    //    $user->phone = $request->phone;
    //    $user->avatar = $request->avatar;
    //    $user->id_card = $request->id_card;
    //    $user->password =bcrypt($request->password);
    //     if ($request->hasFile('id_card')) {
    //     $user->id_card = $request->file('id_card')->store('id_card', 'public');
    // }
    //     if ($request->hasFile('avatar')) {
    //     $user->avatar = $request->file('avatar')->store('avatar', 'public');
    // }
    //    $user->save();   
    //    return redirect()->route('login')->with('sukses', 'Registration successful as owner. Please log in.');;
    // }
     

//     public function login()
//     {
//         return view('auth.login');
//     }


//     public function submitLogin(Request $request)
//     {
//         $data = $request->only('email', 'password');
        
//         if (Auth::attempt($data)) {
//             $request->session()->regenerate();
            
//             $user = Auth::user();

//             if ($user->role === 'admin') {
//                 return redirect()->route('admin.index');
//             } elseif ($user->role === 'pemilik') {
//                 return redirect()->route('pemilik.index');
//             } elseif ($user->role === 'pelanggan') {
//                 return redirect()->route('home.index');
//             } else {
//                 return redirect()->route('login')->with('gagal', 'Unauthorized access.');
//             }
//         } else {
//             return redirect()->back()->with('gagal', 'Email or Password is incorrect.');
//         }
//     }

//      public function logout(Request $request)
//     {
//         Auth::guard('web')->logout();
//         $request->session()->forget('home.index'); 
//         $request->session()->regenerateToken();
//         return redirect()->route('home.index');
//     }
// }
