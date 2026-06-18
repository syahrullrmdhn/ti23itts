<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = [
        'semester',
        'period',
        'icon',
        'student_count',
        'lecturers'
    ];

    protected $casts = [
        'lecturers' => 'array'
    ];
}
