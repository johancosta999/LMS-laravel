<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    // extra time allowed after the timer ends, so an auto-submit on time is not rejected
    const GRACE_SECONDS = 60;

    protected $fillable = [
        'exam_id',
        'student_id',
        'started_at',
        'submitted_at',
        'answers',
        'score',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'answers' => 'array',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function endsAt()
    {
        return $this->started_at->copy()->addMinutes($this->exam->duration_minutes);
    }

    public function secondsLeft(): int
    {
        return max(0, (int) now()->diffInSeconds($this->endsAt(), false));
    }

    public function isOver(): bool
    {
        return $this->submitted_at !== null
            || now()->gt($this->endsAt()->addSeconds(self::GRACE_SECONDS));
    }
}
