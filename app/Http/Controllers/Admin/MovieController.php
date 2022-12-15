<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMovieRequest;
use App\Http\Requests\Admin\UpdateMovieRequest;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
	public function index(): JsonResponse
	{
		$movies = Movie::where('user_id', jwtUser()->id)->orderBy('id', 'desc')->get();
		$genres = [];
		foreach ($movies as $movie)
		{
			array_push($genres, $movie->genres);
		}
		return response()->json(['movies' => $movies, 'genres' => $genres], 200);
	}

	public function get(Movie $movie): JsonResponse
	{
		return response()->json(['movie' => $movie, 'genre' => $movie->genres], 200);
	}

	public function store(StoreMovieRequest $request): JsonResponse
	{
		$movie = Movie::create([
			'user_id'           => jwtUser()->id,
			'name'              => ['en' => $request->name_en, 'ka' => $request->name_ka],
			'director'          => ['en' => $request->director_en, 'ka' => $request->director_ka],
			'budget'            => $request->budget,
			'release_year'      => $request->release_year,
			'description'       => ['en' => $request->description_en, 'ka' => $request->description_ka],
			'poster'            => $request->file('poster')->storePublicly('posters'),
		]);

		foreach (explode(',', $request->genre) as $genre)
		{
			$genreRecord = Genre::updateOrCreate(['name' => $genre]);
			$movie->genres()->attach($genreRecord);
			$movie->save();
		}

		return response()->json(['movie' => $movie, 'genre' => $movie->genres], 201);
	}

	public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
	{
		$movie->name = ['en' => $request->name_en, 'ka' => $request->name_ka];
		$movie->director = ['en' => $request->director_en, 'ka' => $request->director_ka];
		$movie->budget = $request->budget;
		$movie->release_year = $request->release_year;
		$movie->description = ['en' => $request->description_en, 'ka' => $request->description_ka];

		if ($request->file('poster'))
		{
			Storage::delete($movie->poster);
			$movie->poster = $request->file('poster')->storePublicly('posters');
		}
		$movie->save();

		if ($request->genre)
		{
			$movie->genres()->detach();

			foreach (explode(',', $request->genre) as $genre)
			{
				$genreRecord = Genre::updateOrCreate(['name' => $genre]);
				$movie->genres()->attach($genreRecord);
				$movie->save();
			}
		}

		return response()->json(status: 204);
	}

	public function destroy(Movie $movie): JsonResponse
	{
		$movie->delete();
		return response()->json(status: 204);
	}
}
