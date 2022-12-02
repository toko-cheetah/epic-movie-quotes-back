<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
	public function redirect(): JsonResponse
	{
		return response()->json(Socialite::driver('google')->stateless()->redirect()->getTargetUrl(), 200);
	}

	public function callback(): JsonResponse
	{
		try
		{
			$user = Socialite::driver('google')->stateless()->user();

			$findUser = User::where('google_id', $user->id)->first();

			$findAlreadyExistedUser = User::where('email', $user->email)->first();

			if ($findAlreadyExistedUser && !$findAlreadyExistedUser->google_id)
			{
				return response()->json('An account with this email already exists!', 400);
			}
			elseif (!$findUser)
			{
				$newUser = User::create([
					'name'      => $user->name,
					'email'     => $user->email,
					'google_id' => $user->id,
				]);

				$newUser->markEmailAsVerified();
			}

			$minutes = 60 * 24 * 30;

			$payload = [
				'exp' => now()->addMinutes($minutes)->timestamp,
				'uid' => User::firstWhere('email', $user->email)->id,
			];

			$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

			$cookie = cookie('access_token', $jwt, $minutes, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

			return response()->json('success', 200)->withCookie($cookie);
		}
		catch(Exception $e)
		{
			return response()->json($e->getMessage(), 400);
		}
	}
}
