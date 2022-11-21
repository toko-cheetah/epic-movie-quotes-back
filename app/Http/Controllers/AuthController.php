<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
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

		return response()->json('User successfuly registered!', 200);
	}
}
