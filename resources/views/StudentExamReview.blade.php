@extends('app')

@push('title')
    Review &middot; {{ $exam->title }}
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow">{{ $exam->subject->name ?? 'Exam' }}</div>
                <h1 class="page-title">{{ $exam->title }} &middot; Review</h1>
                <p class="page-subtitle">
                    Score {{ (float) $attempt->score }} / {{ $exam->total_marks }}
                    &middot; {{ $totalQuestions - $wrong->count() }} of {{ $totalQuestions }} questions correct
                </p>
            </div>
            <a href="{{ route('student.exam.show', $exam->id) }}" class="registration-back-link">
                <i class="bi bi-arrow-left"></i>
                <span>Back to exam</span>
            </a>
        </div>

        @if ($wrong->isEmpty())
            <div class="exam-status is-done">
                <i class="bi bi-trophy"></i>
                <div>
                    <strong>All answers correct</strong>
                    <div class="small">Nothing to review. Well done!</div>
                </div>
            </div>
        @else
            <div class="review-legend mb-3">
                <span class="review-key is-correct"><i class="bi bi-check-circle-fill"></i> Correct answer</span>
                <span class="review-key is-wrong"><i class="bi bi-x-circle-fill"></i> Your wrong choice</span>
            </div>

            <div class="d-flex flex-column gap-3">
                @foreach ($wrong as $question)
                    @php $correct = array_map('intval', $question->correct_options ?? []); @endphp
                    <div class="quiz-question">
                        <div class="quiz-question-head">
                            <div class="quiz-question-number"><span>{{ $question->number }}</span> Question</div>
                            <span class="quiz-marks">
                                0 / {{ (float) $question->marks }} {{ (float) $question->marks == 1 ? 'mark' : 'marks' }}
                            </span>
                        </div>
                        <p class="quiz-question-text">{{ $question->question }}</p>

                        @if (empty($question->chosen))
                            <div class="review-skipped"><i class="bi bi-dash-circle"></i> You did not answer this question.</div>
                        @elseif ($question->hasMultipleAnswers())
                            <div class="quiz-multi-hint"><i class="bi bi-check2-square"></i> All correct options had to be selected.</div>
                        @endif

                        <div class="quiz-options">
                            @foreach ($question->options as $o => $option)
                                @php
                                    $isCorrect = in_array($o, $correct, true);
                                    $isChosen = in_array($o, $question->chosen, true);
                                @endphp
                                <div class="review-option {{ $isCorrect ? 'is-correct' : ($isChosen ? 'is-wrong' : '') }}">
                                    @if ($isCorrect)
                                        <i class="bi bi-check-circle-fill"></i>
                                    @elseif ($isChosen)
                                        <i class="bi bi-x-circle-fill"></i>
                                    @else
                                        <i class="bi bi-circle"></i>
                                    @endif
                                    <span>{{ $option }}</span>
                                    @if ($isChosen)
                                        <span class="review-tag">Your answer</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
