<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpisodeComment extends Model
{
    protected $fillable = [
        'episode_id',
        'student_nim',
        'student_name',
        'comment',
    ];
}
