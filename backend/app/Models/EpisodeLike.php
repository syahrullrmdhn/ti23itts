<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpisodeLike extends Model
{
    protected $fillable = [
        'episode_id',
        'student_nim',
        'student_name',
    ];
}
