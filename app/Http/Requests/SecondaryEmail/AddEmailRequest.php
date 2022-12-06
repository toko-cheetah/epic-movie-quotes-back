<?php

namespace App\Http\Requests\SecondaryEmail;

use Illuminate\Foundation\Http\FormRequest;

class AddEmailRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'email'    => ['required', 'email', 'unique:users,email', 'unique:secondary_emails,secondary_email'],
		];
	}
}
