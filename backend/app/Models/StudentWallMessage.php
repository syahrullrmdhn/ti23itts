<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentWallMessage extends Model
{
    protected $fillable = [
        'student_id',
        'author_nim',
        'author_name',
        'message',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
