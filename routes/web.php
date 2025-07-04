<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AdminController;
Route::get('/', function () {
    return view('welcome');
});


//front-end
Route::resource('/', \App\Http\Controllers\HomeController::class);
Route::resource('/home', \App\Http\Controllers\HomeController::class);
Route::resource('/kost', \App\Http\Controllers\KostController::class);
Route::resource('/about', \App\Http\Controllers\AboutController::class);
Route::resource('/contact', \App\Http\Controllers\ContactController::class);

Route::middleware(['auth', 'role:admin'])->group(function () {
  Route::resource('admin', \App\Http\Controllers\AdminController::class);

});

Route::get('/registrasi/pelanggan', [AuthController::class, 'registrasiPelanggan'])->name('registrasipelanggan');
Route::post('/registrasi/pelanggan/submit', [AuthController::class, 'submitRegistrasiPelanggan'])->name('registrasipelanggan.submit');

Route::get('/login', [AuthController::class, 'Login'])->middleware(['guest'])->name('login');
Route::post('/login/submit', [AuthController::class, 'submitLogin'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/lupa-password', [ResetPasswordController::class, 'showForgetForm'])->name('password.request');

// Kirim email token reset
Route::post('/lupa-password', [ResetPasswordController::class, 'sendResetLink'])->name('password.email');

// Form reset password
Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Proses reset password
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');