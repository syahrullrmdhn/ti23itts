<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroPhoto extends Model
{
    protected $fillable = [
        'path',
        'sort_order',
    ];
}
