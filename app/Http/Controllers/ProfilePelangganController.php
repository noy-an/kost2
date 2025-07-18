<?php

namespace App\Http\Controllers;

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
        return view('profile.index', compact('user'),[
            'title' => 'Profile'
        ]);
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'),[
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
            $user->phone = $request->phone;
        /** @var \App\Models\User|\Illuminate\Contracts\Auth\MustVerifyEmail $user */


                if ($request->email !== $user->email) {
                    $user->email = $request->email;
                    $user->email_verified_at = null;
                    $user->sendEmailVerificationNotification(); // GA MERAH LAGI 🐶
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

}
