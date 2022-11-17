<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
	public function redirect(): RedirectResponse
	{
		return Socialite::driver('google')->redirect();
	}

	public function callback(): RedirectResponse
	{
		try
		{
			$user = Socialite::driver('google')->user();

			$findUser = User::where('google_id', $user->id)->first();

			$findAlreadyExistedUser = User::where('email', $user->email)->first();

			if ($findAlreadyExistedUser && !$findAlreadyExistedUser->google_id)
			{
				echo '<script language="javascript">';
				echo 'alert("An account with this email already exists")';
				echo '</script>';

				echo redirect()->away(env('APP_FRONT_BASE_URL'));
			}
			elseif ($findUser)
			{
				Auth::login($findUser);

				return redirect()->away(env('APP_FRONT_BASE_URL'));
			}
			else
			{
				$newUser = User::create([
					'name'      => $user->name,
					'email'     => $user->email,
					'password'  => Hash::make('googlepass'),
					'google_id' => $user->id,
				]);

				$newUser->markEmailAsVerified();

				return redirect()->away(env('APP_FRONT_BASE_URL'));
			}
		}
		catch(Exception $e)
		{
			return redirect()->away(env('APP_FRONT_BASE_URL'))->withErrors($e->getMessage());
		}
	}
}
