<?php

namespace App\Http\Controllers;

use App\Http\Requests\SecondaryEmail\AddEmailRequest;
use App\Models\SecondaryEmail;
use App\Models\User;
use App\Notifications\VerifySecondaryEmailNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecondaryEmailController extends Controller
{
	public function collect(): JsonResponse
	{
		$secondaryEmails = SecondaryEmail::where('user_id', jwtUser()->id)->select('secondary_email', 'email_verified_at')->get();

		return response()->json(['secondary_emails' => $secondaryEmails], 200);
	}

	public function add(AddEmailRequest $request): JsonResponse
	{
		$secondaryEmail = SecondaryEmail::create([
			'user_id'         => jwtUser()->id,
			'secondary_email' => $request->email,
		]);

		User::find(jwtUser()->id)->notify(new VerifySecondaryEmailNotification($secondaryEmail));

		return response()->json(status: 204);
	}

	public function verify($id, Request $request): JsonResponse
	{
		$secondaryEmail = SecondaryEmail::findOrFail(base64_decode($id));

		if ($secondaryEmail->secondary_email !== base64_decode($request->email))
		{
			return response()->json('Invalid email provided!', 401);
		}

		if (!$secondaryEmail->email_verified_at)
		{
			$secondaryEmail->email_verified_at = now()->toDateTimeString();
			$secondaryEmail->save();
		}

		return response()->json('Secondary email verified!', 200);
	}

	public function makePrimary($email): JsonResponse
	{
		$SecondaryEmail = SecondaryEmail::where([
			['user_id', jwtUser()->id],
			['secondary_email', $email],
		])->first();
		$user = User::find(jwtUser()->id);

		SecondaryEmail::create([
			'user_id'           => jwtUser()->id,
			'secondary_email'   => $user->email,
			'email_verified_at' => $user->email_verified_at,
		]);

		$user->email = $SecondaryEmail->secondary_email;
		$user->email_verified_at = $SecondaryEmail->email_verified_at;
		$user->save();

		$SecondaryEmail->delete();

		return response()->json('Primary email updated!', 200);
	}

	public function delete($email): JsonResponse
	{
		SecondaryEmail::where([
			['user_id', jwtUser()->id],
			['secondary_email', $email],
		])->delete();

		return response()->json(status: 204);
	}
}
