<?php

use App\Http\Controllers\ParkingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('page');
});

Route::post('/proxy-ai', [ParkingController::class, 'scanWithAI']);
