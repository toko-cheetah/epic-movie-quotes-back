<?php

namespace App\Http\Requests\Profile;

use App\Rules\LowercaseRule;
use Illuminate\Foundation\Http\FormRequest;

class EditNameRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name' => ['required', 'min:3', 'max:15', 'alpha_num', 'unique:users,name', new LowercaseRule],
		];
	}
}
