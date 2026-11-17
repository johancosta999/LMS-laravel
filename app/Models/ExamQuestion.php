<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $fillable = [
        'exam_id',
        'question',
        'options',
        'correct_options',
        'marks',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_options' => 'array', // indexes into options, one or more
    ];

    // more than one correct answer -> students tick checkboxes instead of a single radio
    public function hasMultipleAnswers(): bool
    {
        return count($this->correct_options ?? []) > 1;
    }

    // full marks only when the chosen options are exactly the correct ones
    public function isCorrect(array $chosen): bool
    {
        $chosen = array_values(array_unique(array_map('intval', $chosen)));
        $correct = array_map('intval', $this->correct_options ?? []);
        sort($chosen);
        sort($correct);

        return $chosen !== [] && $chosen === $correct;
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
