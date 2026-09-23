<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'lecturer_id',
        'name',
        'phone_number',
        'email',
        'address',
        'age',
        'subjects',
        "password",
    ];
}
