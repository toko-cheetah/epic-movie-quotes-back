<?php

namespace App\Http\Requests\Profile;

use App\Rules\LowercaseRule;
use Illuminate\Foundation\Http\FormRequest;

class EditPasswordRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'password' => ['required', 'min:8', 'max:15', 'alpha_num', new LowercaseRule, 'confirmed'],
		];
	}
}
