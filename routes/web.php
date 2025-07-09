<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KostController;       
use App\Http\Controllers\TagihanController;    
use App\Http\Controllers\TagihanpelangganController;    
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\BookingpelangganController;
use App\Http\Controllers\PdfController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ProfilePelangganController;
// use App\Http\Controllers\LaporanPdfController;

Auth::routes(['verify' => true]);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    Route::post('/notif-read/{id}', function ($id) {
        $notif = \App\Models\Notification::find($id);
        if ($notif && $notif->user_id === Auth::id()
) {
            $notif->update(['is_read' => true]);
        }
        return response()->json(['status' => 'ok']);
    });



    Route::middleware(['auth', 'verified'])->group(function () {
   Route::resource('/', \App\Http\Controllers\HomeController::class);
Route::resource('/home', \App\Http\Controllers\HomeController::class);
   Route::resource('/about', \App\Http\Controllers\AboutController::class);
Route::resource('/kamar', \App\Http\Controllers\KamarController::class);
Route::get('/tagihan-pelanggan', [TagihanPelangganController::class, 'index'])->name('tagihanpelanggan.index');


 Route::get('/tagihanpelanggan/create/{booking_id}', [TagihanpelangganController::class, 'create'])->name('tagihanpelanggan.create');
    Route::post('/tagihanpelanggan/store', [TagihanpelangganController::class, 'store'])->name('tagihanpelanggan.store');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    Route::get('/profile', [ProfilePelangganController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfilePelangganController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfilePelangganController::class, 'update'])->name('profile.update');  
});





    //front-end

Route::resource('/kost', \App\Http\Controllers\KostController::class);




// Fix: Change the route to accept kost_id as URL parameter
Route::get('/bookingpelanggan/create/{kost_id}', [BookingpelangganController::class, 'create'])
    ->name('bookingpelanggan.form');

Route::post('/bookingpelanggan/store', [BookingpelangganController::class, 'store'])
    ->name('bookingpelanggan.store');


    
    


Route::middleware(['auth', 'role:pelanggan'])->group(function () {

});

Route::get('/booking/{kost_id}', [BookingpelangganController::class, 'create'])->name('bookingpelanggan.form');
Route::post('/booking', [BookingpelangganController::class, 'store'])->name('bookingpelanggan.store');

use App\Models\Notifbook;

Route::patch('/notifbook/{id}/read', function ($id) {
    Notifbook::where('id', $id)->update(['is_read' => true]);
    return redirect()->route('booking.index');
})->name('notifbook.read');

//BATAS FRONT END

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('admin', \App\Http\Controllers\AdminController::class);
    //backend
    Route::resource('kost', KostController::class);
    Route::resource('booking', BookingController::class);
    Route::resource('kontak', KontakController::class);
    Route::resource('pembayaran', PembayaranController::class);
    Route::resource('tagihan', TagihanController::class);
    Route::resource('pengguna', PenggunaController::class);
    Route::delete('/tagihan', [TagihanController::class, 'bulkDelete'])->name('tagihan.bulkDelete');
    Route::delete('/booking', [BookingController::class, 'bulkDelete'])->name('booking.bulkDelete');
    Route::delete('/kost', [KostController::class, 'bulkDelete'])->name('kost.bulk-delete');
    Route::delete('/pembayaran', [PembayaranController::class, 'bulkDelete'])->name('pembayaran.bulk-delete');
    Route::delete('/pengguna', [PenggunaController::class, 'bulkDelete'])->name('pengguna.bulkDelete');
    Route::delete('/kontak-destroy-all', [KontakController::class, 'destroyAll'])->name('kontak.destroyAll');


});

Route::middleware(['auth', 'role:pemilik'])->group(function () {
    Route::resource('pemilik', \App\Http\Controllers\PemilikController::class);
    Route::resource('laporan', \App\Http\Controllers\LaporanController::class);
    Route::get('/pdf/laporan', [PdfController::class, 'exportGabungan'])->name('pdf.laporan');

});

Route::get('/registrasi/pelanggan', [AuthController::class, 'registrasiPelanggan'])->name('registrasipelanggan');
Route::post('/registrasi/pelanggan/submit', [AuthController::class, 'submitRegistrasiPelanggan'])->name('registrasipelanggan.submit');
Route::get('/registrasi', [AuthController::class, 'Registrasi'])->name('registrasi');
Route::post('/registrasi/submit', [AuthController::class, 'submitRegistrasi'])->name('registrasi.submit');
Route::get('/login', [AuthController::class, 'Login'])->middleware(['guest'])->name('login');
Route::post('/login/submit', [AuthController::class, 'submitLogin'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('lupa-password', [ResetPasswordController::class, 'showForgetForm'])->name('password.request');
Route::post('lupa-password', [ResetPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');