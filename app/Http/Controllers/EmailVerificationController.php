<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
	public function verify($user_id, Request $request): JsonResponse
	{
		$user = User::findOrFail(base64_decode($user_id));

		if ($user->email !== base64_decode($request->email))
		{
			return response()->json('Invalid user provided!', 401);
		}

		if (!$user->hasVerifiedEmail())
		{
			$user->markEmailAsVerified();
		}

		return response()->json('Email verified!', 200);
	}

	public function resend(Request $request): JsonResponse
	{
		$request->user()->sendEmailVerificationNotification();

		return response()->json('Verification link sent!', 200);
	}
}
