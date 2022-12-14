<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'user_id'      => User::find(1)->id,
			'name'         => ['en' => fake()->name(), 'ka' => fake()->name()],
			'director'     => ['en' => fake()->name(), 'ka' => fake()->name()],
			'budget'       => '1000000',
			'release_year' => '1999',
			'description'  => ['en' => fake()->text(), 'ka' => fake()->text()],
		];
	}
}
