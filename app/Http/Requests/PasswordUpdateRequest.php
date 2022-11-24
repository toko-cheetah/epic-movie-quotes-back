<?php

namespace App\Http\Requests;

use App\Rules\LowercaseRule;
use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'token'    => 'required',
			'email'    => ['required', 'email'],
			'password' => ['required', 'min:8', 'max:15', 'alpha_num', new LowercaseRule, 'confirmed'],
		];
	}
}
