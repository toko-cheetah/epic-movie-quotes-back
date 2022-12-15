<?php

namespace App\Http\Requests\Admin;

use App\Rules\AlphaNumSpace;
use App\Rules\GeoNumSpace;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name_en'                 => ['required', new AlphaNumSpace],
			'name_ka'                 => ['required', new GeoNumSpace],
			'director_en'             => ['required', new AlphaNumSpace],
			'director_ka'             => ['required', new GeoNumSpace],
			'budget'                  => ['required', 'integer'],
			'release_year'            => ['required', 'integer', 'between:1800,2099'],
			'description_en'          => ['required'],
			'description_ka'          => ['required'],
			'poster'                  => ['image'],
		];
	}
}
