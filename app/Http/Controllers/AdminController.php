<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kost;
use App\Models\Booking;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Kontak;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'kostCount' => Kost::count(),
            'bookingCount' => Booking::count(),
            'tagihanCount' => Tagihan::count(),
            'pembayaranCount' => Pembayaran::count(),
            'userCount' => User::count(),
            'kontakCount' => Kontak::count(),
        ]);
    }
}
