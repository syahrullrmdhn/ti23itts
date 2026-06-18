<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'category',
        'title',
        'image',
        'short_description',
        'full_description',
        'date',
        'participants'
    ];
}
