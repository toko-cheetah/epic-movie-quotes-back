<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecondaryEmailController;
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
Route::controller(AuthController::class)->group(function () {
	Route::post('/register', 'register')->name('register');
	Route::post('/login', 'login')->name('login');

	Route::middleware('jwt.auth')->group(function () {
		Route::get('/me', 'me')->name('me');
		Route::get('/logout', 'logout')->name('logout');
	});
});

Route::controller(OAuthController::class)->group(function () {
	Route::get('/auth/google/redirect', 'redirect')->name('auth.google_redirect');
	Route::get('/auth/google/callback', 'callback')->name('auth.google_callback');
});

Route::controller(EmailVerificationController::class)->group(function () {
	Route::get('/email/verify/{id}', 'verify')->name('verification.verify');
	Route::post('/email/verification-notification', 'resend')->name('verification.send');
});

Route::controller(PasswordResetController::class)->group(function () {
	Route::post('/forgot-password', 'email')->name('password.email');
	Route::get('/reset-password/{token}', 'reset')->name('password.reset');
	Route::post('/reset-password', 'update')->name('password.update');
});

Route::middleware('jwt.auth')->group(function () {
	Route::controller(ProfileController::class)->group(function () {
		Route::post('/avatar', 'addAvatar')->name('add_avatar');
		Route::put('/name', 'editName')->name('edit.name');
		Route::put('/password', 'editPassword')->name('edit.password');
	});

	Route::controller(SecondaryEmailController::class)->group(function () {
		Route::get('/secondary-emails', 'collect')->name('secondary_emails.collect');
		Route::post('/secondary-email', 'add')->name('secondary_emails.add');
		Route::get('/secondary-email/verify/{id}', 'verify')->name('secondary_emails.verify');
		Route::get('/secondary-email/primary/{email}', 'makePrimary')->name('secondary_emails.make_primary');
		Route::delete('/secondary-emails/{email}', 'delete')->name('secondary_emails.delete');
	});
});
