<?php

use App\Http\Controllers\PositionController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/users', [RegisterController::class, 'registerUserApi']);
Route::get('/users', [UserController::class, 'getAllUsersApi']);
Route::get('/users/{id}', [UserController::class, 'getUserByIdApi']);
Route::get('/positions', [PositionController::class, 'index']);
Route::get('/token', [TokenController::class, 'getToken']);
