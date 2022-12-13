<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		// \App\Models\User::factory(10)->create();

		User::factory()->create([
			'name'     => 'saul',
			'email'    => 'better@call.saul',
			'password' => Hash::make('asdasdas'),
		]);

		Movie::factory()
			->count(5)
			->has(Genre::factory())
			->create();
	}
}
