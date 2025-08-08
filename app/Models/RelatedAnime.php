<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedAnime extends Model
{
    protected $table = 'related_anime';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'image',
        'relation_type',
    ];

    public function anime()
    {
        return $this->belongsToMany(Anime::class, 'anime_related_animes', 'anime_id', 'related_anime_id');
    }

    public function relatedAnime()
    {
        return $this->belongsToMany(Anime::class, 'anime_related_animes', 'related_anime_id', 'anime_id');
    }
}
