<?php

use App\Http\Controllers\API\Auth\AuthenticatedTokenController;
use App\Http\Controllers\API\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedTokenController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedTokenController::class, 'destroy']);
});
