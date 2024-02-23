<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Application\UserController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['token_check']], function() {
    Route::group(['prefix' => 'user'], function() {
        
        Route::get('/logout/{user_id}', [AuthController::class, 'logout']);

        Route::group(['middleware' => ['auth:api']], function() {
            Route::post('/save', [UserController::class, 'saveUser']);
            Route::post('/update', [UserController::class, 'updateUser']);
        });
    });
});