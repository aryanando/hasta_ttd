<?php

use App\Http\Controllers\ttdController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


// Auth::routes();
Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::group(['prefix' => 'v2'], function () {
        Route::get('/', [ttdController::class, 'index'] )->name('v2-dashboard');
        Route::post('/add', [ttdController::class, 'store'] );
    });
    
});
Route::group(['prefix' => 'v2'], function () {
    Route::get('/sprin/{id}', [ttdController::class, 'showV2'] );
});

Route::get('/sprin/{id}', [ttdController::class, 'show'] );

