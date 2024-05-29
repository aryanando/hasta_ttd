<?php

use App\Http\Controllers\ttdController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ttdController::class, 'index'] );