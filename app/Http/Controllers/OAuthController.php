<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
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
				return redirect()->away(env('APP_FRONT_BASE_URL'))->with('message', 'An account with this email already exists!');
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
