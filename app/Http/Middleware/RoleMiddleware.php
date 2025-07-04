<?php

namespace App\Http\Middleware;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
       
       if (!Auth::check()) {
        return redirect()->route('login')->with('gagal', 'You must log in first.');
    }

    
    $user = Auth::user();
    if ($user->role !== $role) {
        return redirect()->back()->with('gagal', 'Cannot access this page.');
    }

    return $next($request);
    }
}