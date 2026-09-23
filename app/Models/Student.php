<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'reg_no',
        'name',
        'phone_number',
        'address',
        'birth_date',
    ];
}
