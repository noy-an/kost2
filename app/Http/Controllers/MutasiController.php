<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\DepositHistory;
use Illuminate\Support\Facades\Auth;

class MutasiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Ambil deposit user
        $deposit = Deposit::where('user_id', $userId)->first();

        // Ambil history deposit, urut terbaru ke lama
        $depositHistories = DepositHistory::whereHas('deposit', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->orderByDesc('created_at')->get();

        return view('mutasi.index', compact('depositHistories', 'deposit'));
    }
}
