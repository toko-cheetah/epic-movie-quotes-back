<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	protected $casts = [
		'name'        => 'array',
		'director'    => 'array',
		'description' => 'array',
	];

	public function genres(): BelongsToMany
	{
		return $this->belongsToMany(Genre::class, 'movie_genre');
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
