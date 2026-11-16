<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'reg_no',
        'name',
        'phone_number',
        'email',
        'address',
        'birth_date',
        'image',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // modules this student is enrolled in
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'enrollments')->withTimestamps();
    }
}
