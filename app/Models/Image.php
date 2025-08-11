<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';
    public $timestamps = false;

    protected $fillable = [
        'anime_id',
        'url',
        'type',
    ];

    public function anime()
    {
        return $this->belongsTo(Anime::class, 'anime_id', 'id');
    }
}
