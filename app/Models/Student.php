<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'reg_no',
        'name',
        'phone_number',
        'email',
        'address',
        'birth_date',
        'password',
    ];
}
