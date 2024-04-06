<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {

    $user = $request->user()->load(['draws']);
    $user->draws->transform(function($draw) {
        $draw->image_url = Storage::url("imgs/" . $draw->image);
        return $draw;
    });

    return $user;

});

Route::apiResource('draw', App\Http\Controllers\Api\DrawController::class);
Route::apiResource('like', App\Http\Controllers\Api\LikeController::class);