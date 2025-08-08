<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    public $timestamps = false;

    protected $fillable = [
        'mal_id',
        'url',
        'type',
    ];

    public function anime()
    {
        return $this->belongsTo(Anime::class, 'mal_id', 'id');
    }
}
