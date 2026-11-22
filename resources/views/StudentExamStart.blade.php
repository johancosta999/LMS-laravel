@extends('app')

@push('title')
    {{ $exam->title }}
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="exam-dark exam-hero">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <div class="registration-eyebrow">
                        <i class="bi {{ $exam->isQuiz() ? 'bi-ui-checks' : 'bi-file-earmark-pdf' }}"></i>
                        {{ $exam->subject->name ?? 'Exam' }}
                    </div>
                    <span class="exam-pill">{{ $exam->isQuiz() ? 'Online quiz' : 'Paper exam (PDF)' }}</span>
                    <h1>{{ $exam->title }}</h1>
                </div>
                <a href="{{ route('student.view_module', $exam->subject_id) }}" class="registration-back-link">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to module</span>
                </a>
            </div>

            <div class="exam-hero-stats">
                <div class="exam-hero-stat">
                    <span>Exam date</span>
                    <strong>{{ \Carbon\Carbon::parse($exam->exam_date)->format('M jS Y') }}</strong>
                </div>
                <div class="exam-hero-stat">
                    <span>Duration</span>
                    <strong>{{ $exam->duration_minutes }} min</strong>
                </div>
                <div class="exam-hero-stat">
                    <span>Total / Pass</span>
                    <strong>{{ $exam->total_marks }} / {{ $exam->pass_marks }}</strong>
                </div>
            </div>
            @include('component.exam-shape')
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card registration-form-card">
                    <div class="registration-form-heading">
                        <div>
                            <span class="registration-section-label">Before you start</span>
                            <h2>Rules &amp; instructions</h2>
                        </div>
                    </div>
                    @include('component.exam-rules', ['exam' => $exam])
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card registration-form-card">
                    <div class="registration-form-heading">
                        <div>
                            <span class="registration-section-label">Your attempt</span>
                            <h2>{{ $attempt ? 'Status' : 'Start the exam' }}</h2>
                        </div>
                    </div>

                    @if ($attempt && $attempt->submitted_at)
                        {{-- finished --}}
                        <div class="exam-status is-done">
                            <i class="bi bi-check2-circle"></i>
                            <div>
                                <strong>Submitted</strong>
                                <div class="small">{{ $attempt->submitted_at->format('M jS Y, H:i') }}</div>
                            </div>
                            @if ($exam->isQuiz())
                                <span class="exam-score">
                                    {{ (float) $attempt->score }} / {{ $exam->total_marks }}
                                    &middot; {{ $attempt->score >= $exam->pass_marks ? 'Pass' : 'Fail' }}
                                </span>
                            @endif
                        </div>
                        @if ($exam->reviewIsOpen())
                            <a href="{{ route('student.exam.review', $exam->id) }}" class="btn-forest mt-3">
                                <i class="bi bi-eye"></i> Review incorrect answers
                            </a>
                        @elseif ($exam->allowsReview())
                            <div class="exam-status is-closed mt-3">
                                <i class="bi bi-calendar-event"></i>
                                <span>You can review your incorrect answers from
                                    <strong>{{ $exam->review_available_at->format('M jS Y, H:i') }}</strong>.</span>
                            </div>
                        @endif
                    @elseif ($attempt)
                        {{-- started, not finished --}}
                        @php $attempt->setRelation('exam', $exam); @endphp
                        @if ($attempt->isOver())
                            <div class="exam-status is-closed">
                                <i class="bi bi-hourglass-bottom"></i>
                                <span>The time for this exam is over.</span>
                            </div>
                        @else
                            <div class="exam-warning">
                                <i class="bi bi-stopwatch"></i>
                                You started at {{ $attempt->started_at->format('H:i') }}. The timer keeps running.
                            </div>
                            <a href="{{ route('student.exam.take', $exam->id) }}" class="btn-lime">
                                <i class="bi bi-play-fill"></i> Continue exam
                            </a>
                        @endif
                    @elseif (!$exam->isQuiz() && !$exam->paper_path)
                        <div class="exam-status is-closed">
                            <i class="bi bi-info-circle"></i>
                            <span>This exam has no online paper. Your lecturer will enter the marks.</span>
                        </div>
                    @else
                        {{-- not started: ask for the key --}}
                        <div class="exam-warning">
                            <i class="bi bi-stopwatch"></i>
                            <span>Once you enter the key, a <strong>{{ $exam->duration_minutes }} minute</strong> timer starts and cannot be paused.</span>
                        </div>
                        <form action="{{ route('student.exam.start', $exam->id) }}" method="post" class="exam-key-card">
                            @csrf
                            @if ($exam->enrol_key)
                                <label for="enrol_key" class="form-label"><i class="bi bi-key"></i> Exam enrolment key</label>
                                <input type="password" name="enrol_key" id="enrol_key" class="form-control mb-3" required
                                    autocomplete="off" placeholder="Enter the key from your lecturer">
                            @endif
                            <button type="submit" class="btn-lime w-100 justify-content-center">
                                <i class="bi bi-unlock"></i> Start exam
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
