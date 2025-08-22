<?php
namespace App\Http\Controllers;

use App\Models\Otp;
use Illuminate\Http\Request;

class OtpController extends Controller
{
  public function index(Request $request)
{
    $query = Otp::query();

    if ($request->search) {
        $search = $request->search;
        $query->where('phone', 'like', "%$search%")
              ->orWhere('code', 'like', "%$search%")
              ->orWhere('user_id', 'like', "%$search%");
    }

    $otps = $query->latest()->paginate(10);
    return view('otp.index', compact('otps'));
}



}
