<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'category',
        'title',
        'image',
        'media_type',
        'media_source',
        'video_url',
        'short_description',
        'full_description',
        'date',
        'participants'
    ];

    public function likes()
    {
        return $this->hasMany(EpisodeLike::class);
    }

    public function comments()
    {
        return $this->hasMany(EpisodeComment::class);
    }
}
