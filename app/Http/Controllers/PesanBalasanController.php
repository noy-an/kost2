<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Balasan;
use App\Models\Kontak;
use Illuminate\Support\Facades\Auth;

class PesanBalasanController extends Controller
{
    public function index()
    {
        $userEmail = Auth::user()->email;

        // Cari kontak berdasarkan email user yang login
        $kontakIds = Kontak::where('email', $userEmail)->pluck('id');

        // Ambil semua balasan berdasarkan kontak_id
        $balasan = Balasan::whereIn('kontak_id', $kontakIds)->latest()->get();

        return view('pesan_balasan.index', compact('balasan'));
    }
}
