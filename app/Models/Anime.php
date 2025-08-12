<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    use HasFactory;

    const LIST_WATCHING = 'watching';
    const LIST_WATCH = 'watch';
    const LIST_WATCHED = 'watched';
    const LIST_FAVORITE = 'favorite';

    const NSFW_WHITE = 'white';
    const NSFW_GRAY = 'grey';
    const NSFW_BLACK = 'black';

    const STATUS_AIRING = 'currently_airing';
    const STATUS_FINISHED = 'finished_airing';
    const STATUS_NOT_YET_AIRED = 'not_yet_aired';


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
        'completed',
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

    public function relatedAnimes()
    {
        return $this->belongsToMany(RelatedAnime::class, 'anime_related_animes', 'anime_id', 'related_anime_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'anime_id', 'id');
    }

    public function cover()
    {
        return $this->images()->where('type', 'cover')->first()->url ?? null;
    }
}
