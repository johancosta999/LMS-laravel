{{--
    Exam rules for students: the lecturer's description (one rule per line) + the rules the system enforces.
    Expects: $exam (Exam).
--}}
@php $rules = $exam->rules(); @endphp

<div class="exam-rules">
    @if ($rules)
        <div class="exam-rules-heading"><i class="bi bi-person-badge"></i> From your lecturer</div>
        <ol class="exam-rule-list">
            @foreach ($rules as $rule)
                <li>{{ $rule }}</li>
            @endforeach
        </ol>
    @endif

    <div class="exam-rules-heading"><i class="bi bi-shield-check"></i> Exam rules</div>
    <ul class="exam-system-rules">
        <li>
            <i class="bi bi-stopwatch"></i>
            <span>You have <strong>{{ $exam->duration_minutes }} minutes</strong>. The timer starts when you enter the key and cannot be paused.</span>
        </li>
        <li>
            <i class="bi bi-1-circle"></i>
            <span>You get <strong>one attempt</strong>. Closing the page does not stop the timer.</span>
        </li>
        @if ($exam->isQuiz())
            <li>
                <i class="bi bi-send-check"></i>
                <span>Your answers are submitted automatically when the time runs out.</span>
            </li>
            <li>
                <i class="bi bi-check2-square"></i>
                <span>For "select all that apply" questions you only get the marks if you pick <strong>all</strong> the correct answers and no wrong ones.</span>
            </li>
        @else
            <li>
                <i class="bi bi-file-earmark-lock"></i>
                <span>The paper can only be opened while the timer is running.</span>
            </li>
        @endif
        <li>
            <i class="bi bi-flag"></i>
            <span>Pass mark: <strong>{{ $exam->pass_marks }}</strong> out of {{ $exam->total_marks }}.</span>
        </li>
    </ul>
</div>
