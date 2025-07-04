<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KostController;       
use App\Http\Controllers\TagihanController;    
use App\Http\Controllers\ContactController;

//backend
Route::resource('kost', KostController::class);
Route::resource('booking', BookingController::class);
Route::resource('kontak', KontakController::class);
Route::resource('pembayaran', PembayaranController::class);
Route::resource('tagihan', TagihanController::class);

//front-end
Route::resource('/', \App\Http\Controllers\HomeController::class);
Route::resource('home', \App\Http\Controllers\HomeController::class);
Route::resource('about', \App\Http\Controllers\AboutController::class);
// Route::resource('contact', ContactController::class); // kamu pakai route manual dibawah

Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('contact', [ContactController::class, 'store'])->name('contact.store');

Route::resource('admin', AdminController::class);

Route::get('registrasi/pelanggan', [AuthController::class, 'registrasiPelanggan'])->name('registrasipelanggan');
Route::post('registrasi/pelanggan/submit', [AuthController::class, 'submitRegistrasiPelanggan'])->name('registrasipelanggan.submit');
Route::get('registrasi', [AuthController::class, 'Registrasi'])->name('registrasi');
Route::post('registrasi/submit', [AuthController::class, 'submitRegistrasi'])->name('registrasi.submit');
Route::get('login', [AuthController::class, 'Login'])->name('login');
Route::post('login/submit', [AuthController::class, 'submitLogin'])->name('login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('lupa-password', [ResetPasswordController::class, 'showForgetForm'])->name('password.request');

// Kirim email token reset
Route::post('lupa-password', [ResetPasswordController::class, 'sendResetLink'])->name('password.email');

// Form reset password
Route::get('reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Proses reset password
Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
