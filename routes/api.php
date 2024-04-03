<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return response()->json([
        'user' => $request->user()->load(['draws']),
    ]);
});

Route::apiResource('draw', App\Http\Controllers\Api\DrawController::class);