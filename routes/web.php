<?php

use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ttdController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


// Auth::routes();
Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::group(['prefix' => 'v2'], function () {
        Route::get('/', [ttdController::class, 'index'])->name('v2-dashboard');
        Route::post('/add', [ttdController::class, 'store']);
        Route::get('/kwitansi-dokter', [ttdController::class, 'kwitansiDokter'])->name('kwitansi-dokter-dashboard');

        // Kwitansi
        Route::get('/receipts', [ReceiptController::class, 'index'])->name('receipts.index');
        Route::get('/receipts/create', [ReceiptController::class, 'create'])->name('receipts.create');
        Route::post('/receipts', [ReceiptController::class, 'store'])->name('receipts.store');
        Route::get('/receipts/{receipt}', [ReceiptController::class, 'show'])->name('receipts.show');
        Route::delete('/receipts/{receipt}', [ReceiptController::class, 'destroy'])->name('receipts.destroy');
    });
});
Route::group(['prefix' => 'v2'], function () {
    Route::get('/sprin/{id}', [ttdController::class, 'showV2']);
});

Route::get('/sprin/{id}', [ttdController::class, 'show']);
Route::get('/receipt/{public_code}', [ReceiptController::class, 'showPublic'])->name('receipts.public');
Route::post('/receipt/{public_code}/sign', [ReceiptController::class, 'saveSignature'])->name('receipts.sign');
Route::get('/receipt/{public_code}/pdf', [ReceiptController::class, 'generatePdf'])->name('receipts.pdf');


