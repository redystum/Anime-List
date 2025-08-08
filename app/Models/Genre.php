<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $table = 'genres';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
    ];

    public function animes()
    {
        return $this->belongsToMany(Anime::class, 'anime_genres', 'genre_id', 'anime_id');
    }
}
