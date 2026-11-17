@extends('app')

@push('title')
    {{ $exam->title }}
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow">{{ $exam->subject->name ?? 'Exam' }}</div>
                <h1 class="page-title">{{ $exam->title }}</h1>
                <p class="page-subtitle">{{ $exam->total_marks }} marks &middot; pass {{ $exam->pass_marks }}</p>
            </div>
            <div class="exam-timer" id="exam-timer" aria-live="polite">
                <i class="bi bi-stopwatch"></i> <span id="exam-timer-value">--:--</span>
            </div>
        </div>

        <div id="time-up" class="exam-time-up d-none">
            <i class="bi bi-alarm"></i> Time is up.
            {{ $exam->isQuiz() ? 'Submitting your answers…' : 'The paper is now closed.' }}
        </div>

        <details class="exam-rules-toggle">
            <summary><i class="bi bi-list-check"></i> Rules &amp; instructions</summary>
            <div class="mt-3">
                @include('component.exam-rules', ['exam' => $exam])
            </div>
        </details>

        @if ($exam->isQuiz())
            <form action="{{ route('student.exam.submit', $exam->id) }}" method="post" id="quiz-form">
                @csrf
                <div class="d-flex flex-column gap-3">
                    @foreach ($questions as $i => $question)
                        <div class="quiz-question">
                            <div class="quiz-question-head">
                                <div class="quiz-question-number"><span>{{ $i + 1 }}</span> Question</div>
                                <span class="quiz-marks">{{ (float) $question->marks }} {{ (float) $question->marks == 1 ? 'mark' : 'marks' }}</span>
                            </div>
                            <p class="quiz-question-text">{{ $question->question }}</p>
                            @if ($question->hasMultipleAnswers())
                                <div class="quiz-multi-hint"><i class="bi bi-check2-square"></i> Select all that apply.</div>
                            @endif
                            <div class="quiz-options">
                                @foreach ($question->options as $o => $option)
                                    <label class="quiz-option" for="q{{ $question->id }}o{{ $o }}">
                                        <input class="form-check-input" type="{{ $question->hasMultipleAnswers() ? 'checkbox' : 'radio' }}"
                                            name="answers[{{ $question->id }}][]"
                                            id="q{{ $question->id }}o{{ $o }}" value="{{ $o }}">
                                        <span>{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="btn-lime mt-4"
                    onclick="return confirm('Submit your answers? You cannot change them afterwards.')">
                    <i class="bi bi-send-check"></i> Submit quiz
                </button>
            </form>
        @else
            <div class="card registration-form-card" id="paper-card">
                <div class="registration-form-heading">
                    <div>
                        <span class="registration-section-label">Paper</span>
                        <h2>Exam paper</h2>
                    </div>
                    <a href="{{ route('student.exam.paper', $exam->id) }}" target="_blank" rel="noopener" class="btn-forest btn-sm">
                        <i class="bi bi-box-arrow-up-right"></i> Open in new tab
                    </a>
                </div>
                <iframe src="{{ route('student.exam.paper', $exam->id) }}" title="Exam paper" class="exam-paper-frame"></iframe>
                <p class="text-muted mt-2 mb-0">Hand in your answers as your lecturer instructed. Marks are entered by your lecturer.</p>
            </div>
        @endif
    </div>
@endsection

@push('script')
<script>
(function () {
    // server-side remaining time; the server also rejects late quiz answers
    const endsAt = Date.now() + {{ $secondsLeft }} * 1000;
    const display = document.getElementById('exam-timer-value');
    const badge = document.getElementById('exam-timer');
    const quizForm = document.getElementById('quiz-form');
    let finished = false;

    function tick() {
        const left = Math.max(0, Math.round((endsAt - Date.now()) / 1000));
        const m = Math.floor(left / 60), s = left % 60;
        display.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');

        badge.classList.toggle('is-urgent', left <= 60);

        if (left === 0 && !finished) {
            finished = true;
            document.getElementById('time-up').classList.remove('d-none');
            if (quizForm) {
                quizForm.submit();
            } else {
                const card = document.getElementById('paper-card');
                if (card) card.remove();
            }
        }
    }

    tick();
    setInterval(tick, 1000);
})();
</script>
@endpush
