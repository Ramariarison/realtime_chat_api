<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api', 'admin'])->group(function(){
    Route::post('/admin/users', [UserController::class, 'getUsers']);
    Route::post('/admin/stats', [UserController::class, 'usersStats']);
    Route::post('/admin/users/{user}/validate', [UserController::class, 'validateUser']);
});

Route::middleware('auth:api')->group(function(){
    Route::post('/user/user-info', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});