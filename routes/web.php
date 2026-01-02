<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Pages
Route::view('/register', 'register');
Route::view('/login', 'login');

// Actions
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', [AuthController::class, 'dashboard']);
Route::post('/logout', [AuthController::class, 'logout']);