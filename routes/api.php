<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
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

Route::group(['controller' => EmailVerificationController::class], function () {
	Route::get('/email/verify/{id}', 'verify')->name('verification.verify');
	Route::post('/email/verification-notification', 'resend')->name('verification.send');
});
