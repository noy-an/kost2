<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Tagihan;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function exportGabungan()
    {
        $bookings = Booking::with(['user', 'kost'])->latest()->get();
        $tagihans = Tagihan::with('booking.kost')->latest()->get();

        $pdf = Pdf::loadView('pdf.laporan', compact('bookings', 'tagihans'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('laporan-booking-dan-tagihan.pdf');
    }
}
