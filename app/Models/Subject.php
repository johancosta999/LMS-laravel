<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'module_id',
        'name',
        'lectures_count',
        'assigned_lecturers',
    ];
}
