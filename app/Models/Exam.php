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
        'exam_type',
        'enrol_key',
        'duration_minutes',
        'paper_path',
        'review_mode',
        'review_available_at',
    ];

    protected $hidden = [
        'enrol_key',
    ];

    protected $casts = [
        'review_available_at' => 'datetime',
    ];

    // when students can review their incorrect quiz answers
    const REVIEW_NEVER = 'never';
    const REVIEW_IMMEDIATE = 'immediate';
    const REVIEW_DATE = 'date';

    // review switched on at all (it may still be waiting for its date)
    public function allowsReview(): bool
    {
        return $this->isQuiz() && in_array($this->review_mode, [self::REVIEW_IMMEDIATE, self::REVIEW_DATE], true);
    }

    // review can be opened right now (the student must also have submitted)
    public function reviewIsOpen(): bool
    {
        if (!$this->allowsReview()) {
            return false;
        }

        return $this->review_mode === self::REVIEW_IMMEDIATE
            || ($this->review_available_at && now()->gte($this->review_available_at));
    }

    // the teacher's description as a list of rules: one per line,
    // with any bullet / numbering the teacher typed ("-", "*", "•", "1.", "2)") stripped
    public function rules(): array
    {
        $lines = preg_split('/\r\n|\r|\n/', (string) $this->description);
        $rules = [];

        foreach ($lines as $line) {
            $line = trim(preg_replace('/^\s*(?:[-*•]+|\d+[.)])\s*/u', '', $line));
            if ($line !== '') {
                $rules[] = $line;
            }
        }

        return $rules;
    }

    public function isQuiz(): bool
    {
        return $this->exam_type === 'quiz';
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

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
