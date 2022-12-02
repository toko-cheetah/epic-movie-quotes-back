<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\AddAvatarRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
	public function addAvatar(AddAvatarRequest $request): JsonResponse
	{
		$user = User::find(jwtUser()->id);
		if ($user->avatar)
		{
			Storage::delete($user->avatar);
		}
		$user->avatar = $request->file('avatar')->storePublicly('avatars');
		$user->save();

		return response()->json([
			'message' => 'Avatar added!',
			'avatar'  => asset('storage/' . $user->avatar),
		], 200);
	}
}
