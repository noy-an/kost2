<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SetSimulasiTanggalMiddleware
{
   public function handle($request, Closure $next)
{
    if (session()->has('simulasi_tanggal')) {
        Carbon::setTestNow(Carbon::parse(session('simulasi_tanggal')));
    } else {
        Carbon::setTestNow(); // reset
    }

    return $next($request);
}

}
