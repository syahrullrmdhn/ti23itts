<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'role',
        'status',
        'photo',
        'aib_photo',
        'fun_fact'
    ];
}
