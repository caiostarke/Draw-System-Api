<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    $user = $request->user()->load(['draws']);

    return $user;
});

Route::apiResource('draw', App\Http\Controllers\Api\DrawController::class);
Route::apiResource('like', App\Http\Controllers\Api\LikeController::class);