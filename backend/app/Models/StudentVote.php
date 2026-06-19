<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentVote extends Model
{
    protected $fillable = [
        'student_id',
        'voter_nim',
        'voter_name',
        'badge',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
