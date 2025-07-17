<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kontak;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index',[
            'title' => 'Contact'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string',
        ]);

        Kontak::create($request->only('nama', 'email', 'subjek', 'pesan'));

        return redirect()->route('contact.index')->with('success', 'Pesan kamu berhasil dikirim!');
    }

public function show()
{
    // $email = auth()->user()->email;
     $email = Auth::user()->email;

    $pesans = \App\Models\Kontak::where('email', $email)->latest()->get();

    return view('contact.show', [
        'title' => 'Histori Pesanmu',
        'pesans' => $pesans
    ]);
}


}
