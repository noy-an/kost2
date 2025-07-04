<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AdminController;
Route::get('/', function () {
    return view('welcome');
});

Route::resource('/admin', \App\Http\Controllers\AdminController::class);

Route::get('/registrasi/pelanggan', [AuthController::class, 'registrasiPelanggan'])->name('registrasipelanggan');
Route::post('/registrasi/pelanggan/submit', [AuthController::class, 'submitRegistrasiPelanggan'])->name('registrasipelanggan.submit');
Route::get('/registrasi', [AuthController::class, 'Registrasi'])->name('registrasi');
Route::post('/registrasi/submit', [AuthController::class, 'submitRegistrasi'])->name('registrasi.submit');
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