<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit; 

class DepositController extends Controller
{
   public function index(Request $request)
{
    $visited = session('visited_menus', []);
    if (!in_array('deposit', $visited)) {
        $visited[] = 'deposit';
        session(['visited_menus' => $visited]);
    }

    $deposit = Deposit::with('user')
        ->when($request->search, function ($query) use ($request) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->orWhere('amount', 'like', '%' . $request->search . '%');
        })
        ->latest()
        ->paginate(10);

    return view('deposit.index', compact('deposit'));
}

}
