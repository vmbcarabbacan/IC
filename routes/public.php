<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Public\WebsiteController;

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

Route::group(['prefix' => 'api'], function() {
    Route::get('/device', [WebsiteController::class, 'createDevice']);
    
    Route::group(['middleware' => ['auth:api']], function() {
        Route::post('/add-customer', [CustomerController::class, 'addCustomer']);

    });
});