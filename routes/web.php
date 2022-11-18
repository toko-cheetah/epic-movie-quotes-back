<?php

use App\Http\Controllers\OAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'welcome')->name('home');

Route::controller(OAuthController::class)->group(function () {
	Route::get('/auth/google/redirect', 'redirect')->name('auth.google_redirect');
	Route::get('/auth/google/callback', 'callback')->name('auth.google_callback');
});
