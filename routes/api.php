<?php

use App\Http\Controllers\EstrazioneVisura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return "<script>alert('oh no')</script>";
});

Route::get('/EstrazioneVisura', [EstrazioneVisura::class, 'index']);
