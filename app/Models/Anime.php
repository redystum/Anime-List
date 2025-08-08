<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    protected $table = 'animes';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'title_jp',
        'title_en',
        'start_date',
        'end_date',
        'synopsis',
        'score',
        'num_scoring_usr',
        'nsfw',
        'media_type',
        'status',
        'num_episodes',
        'broadcast_weekday',
        'broadcast_time',
        'average_ep_duration',
        'background',
        'favorite',
        'lastFetch',
        'localScore',
        'notes',
        'viewed',
        'watching',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'anime_genres', 'anime_id', 'genre_id');
    }

    public function studios()
    {
        return $this->belongsToMany(Studio::class, 'anime_studios', 'anime_id', 'studio_id');
    }

    public function relatedAnime()
    {
        return $this->belongsToMany(Anime::class, 'anime_related_animes', 'anime_id', 'related_anime_id');
    }
}
