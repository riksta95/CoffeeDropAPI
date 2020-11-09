<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashbackController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/coordinates/{postcode}', [LocationController::class, 'coordinates'])->name('coordinates');
    Route::get('/location/{postcode}', [LocationController::class, 'getNearestLocation'])->name('location');
    Route::post('/location', [LocationController::class, 'createLocation'])->name('location');

    Route::post('/cashback', [CashbackController::class, 'getCashback'])->name('cashback');
    Route::get('/marketing', [CashbackController::class, 'marketing'])->name('marketing');
});
