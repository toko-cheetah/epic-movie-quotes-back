<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\SecondaryEmail;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function register(RegisterRequest $request): JsonResponse
	{
		$user = User::create([
			'name'     => $request->name,
			'email'    => $request->email,
			'password' => Hash::make($request->password),
		]);

		event(new Registered($user));

		return response()->json(status: 204);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$secondaryEmail = SecondaryEmail::firstWhere('secondary_email', $request->name);

		$loginType = $secondaryEmail ? 'id' : (filter_var($request->name, FILTER_VALIDATE_EMAIL) ? 'email' : 'name');

		$nameValue = $secondaryEmail ? $secondaryEmail->user_id : $request->name;

		$user = User::firstWhere($loginType, $request->name);

		if ($secondaryEmail && !$secondaryEmail->email_verified_at || $user && !$user->email_verified_at)
		{
			return response()->json('This email is not verified!', 400);
		}

		if (auth()->attempt([
			$loginType => $nameValue,
			'password' => $request->password,
		], $request->remember))
		{
			$minutesJwt = $request->remember ? (60 * 24 * 30) : (60 * 24);
			$minutesCookie = $request->remember ? (60 * 24 * 30) : null;

			$payload = [
				'exp' => now()->addMinutes($minutesJwt)->timestamp,
				'uid' => User::firstWhere($loginType, $nameValue)->id,
			];

			$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

			$cookie = cookie('access_token', $jwt, $minutesCookie, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

			return response()->json('success', 200)->withCookie($cookie);
		}
		return response()->json('wrong name/email or password', 400);
	}

	public function me(): JsonResponse
	{
		return response()->json(
			[
				'message' => 'authenticated successfully',
				'user'    => jwtUser(),
				'avatar'  => asset('storage/' . jwtUser()->avatar),
			],
			200
		);
	}

	public function logout(): JsonResponse
	{
		$cookie = cookie('access_token', '', 0, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}
}
