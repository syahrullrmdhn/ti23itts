<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'category',
        'title',
        'icon',
        'gradient_color',
        'short_description',
        'full_description',
        'date',
        'participants'
    ];
}
