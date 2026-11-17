<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'title',
        'exam_date',
        'total_marks',
        'pass_marks',
        'description',
    ];

    // how long a newly created exam is spotlighted to enrolled students
    const NEW_FOR_DAYS = 7;

    // exams created within the "new" window
    public function scopeRecentlyCreated($query)
    {
        return $query->where('created_at', '>=', now()->subDays(self::NEW_FOR_DAYS));
    }

    public function isNew(): bool
    {
        return $this->created_at && $this->created_at->gte(now()->subDays(self::NEW_FOR_DAYS));
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }
}
