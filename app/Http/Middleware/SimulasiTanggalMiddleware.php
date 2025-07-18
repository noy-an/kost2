<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class SimulasiTanggalMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Session::has('simulasi_tanggal')) {
            Carbon::setTestNow(Carbon::parse(Session::get('simulasi_tanggal')));
        }

        return $next($request);
    }
}
