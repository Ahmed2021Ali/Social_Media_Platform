<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::post('/update', [UserController::class, 'update']);

});

                  /* User authentication Route */
Route::controller(UserController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('/resetPassword', 'resetPassword');
        Route::prefix('socialite')->group(function () {
            Route::get('{provider}/login', 'socialiteLogin');
            Route::get('{provider}/redirect', 'socialiteRedirect');
        });
    });
    Route::post('/update', 'update')->middleware('auth:sanctum');
});
                   /* End User Route */






