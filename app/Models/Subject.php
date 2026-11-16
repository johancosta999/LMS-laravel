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
        'enrol_key',
    ];

    // students enrolled in this module
    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')->withTimestamps();
    }
}
