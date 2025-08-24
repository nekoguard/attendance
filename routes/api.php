<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/time', function () {
        return response()->json(['time' => now()->toDateTimeString()]);
    })->name('api.time');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

