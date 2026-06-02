<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api', 'admin'])->group(function(){
    Route::post('/admin/users', [UserController::class, 'getUsers']);
    Route::post('/admin/stats', [UserController::class, 'usersStats']);
    Route::post('/admin/users/{user}/validate', [UserController::class, 'validateUser']);
    Route::delete('/admin/users/{user}/delete', [UserController::class, 'removeUser']);
    Route::post('/admin/users/{user}/update-user', [UserController::class, 'updateUser']);
    Route::post('/admin/users/add', [UserController::class, 'addUser']);
});

Route::middleware('auth:api')->group(function(){
    Route::post('/user/user-info', [AuthController::class, 'me']);
    Route::post('/user/update', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users/search', [UserController::class, 'search']);
    Route::post('/friends/invite/{user}', [FriendController::class, 'invite']);
});