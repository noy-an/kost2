<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class SimulasiController extends Controller
{
    public function showForm()
    {
        return view('simulasi.settanggal');
    }

    public function setTanggal(Request $request)
    {
        $request->validate(['tanggal' => 'required|date']);
        Session::put('simulasi_tanggal', $request->tanggal);
        return redirect()->back()->with('success', 'Tanggal simulasi diubah ke ' . $request->tanggal);
    }

    public function resetTanggal()
    {
        Session::forget('simulasi_tanggal');
        Carbon::setTestNow(null);
        return redirect()->back()->with('success', 'Tanggal simulasi direset ke default');
    }
}
