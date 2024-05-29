<?php

use App\Http\Controllers\ttdController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['prefix' => 'v2'], function () {
    Route::get('/', [ttdController::class, 'index'] )->name('v2-dashboard');
    Route::post('/add', [ttdController::class, 'store'] );
    Route::get('/sprin/{id}', [ttdController::class, 'showV2'] );
});

Route::get('/sprin/{id}', [ttdController::class, 'show'] );