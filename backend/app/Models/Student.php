<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public const BADGE_DEFINITIONS = [
        'paling_dicari' => 'Paling Dicari',
        'si_paling_kocak' => 'Si Paling Kocak',
        'anak_sibuk' => 'Anak Sibuk',
        'most_wholesome' => 'Most Wholesome',
    ];

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
        'message',
    ];

    protected $casts = [
        'birth_date' => 'date:Y-m-d',
    ];

    public function wallMessages()
    {
        return $this->hasMany(StudentWallMessage::class)->latest();
    }

    public function votes()
    {
        return $this->hasMany(StudentVote::class);
    }
}
