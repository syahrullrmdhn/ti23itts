<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nim',
        'name',
        'email',
        'gender',
        'birth_place',
        'birth_date',
        'religion',
        'phone',
        'class_type',
        'entry_type',
        'role',
        'status',
        'photo',
        'aib_photo',
        'fun_fact',
    ];

    protected $casts = [
        'birth_date' => 'date:Y-m-d',
    ];
}
