{{--
    Exam access + delivery fields shared by ExamCreate and ExamUpdate.
    Expects: $exam (Exam|null). The parent form must use enctype="multipart/form-data"
    and have a #total_marks input (the full marks; for a quiz the question marks must add up to it).
--}}
@php
    $exam = $exam ?? null;
    $examType = old('exam_type', $exam->exam_type ?? 'paper');

    // questions to rebuild the builder with: failed submit > saved questions > one empty question
    // "auto" = mark still follows the default (full marks / number of questions)
    $initialQuestions = old('questions');
    if ($initialQuestions === null && $exam && $exam->isQuiz()) {
        $initialQuestions = $exam->questions->map(fn ($q) => [
            'text' => $q->question,
            'options' => $q->options,
            'correct' => $q->correct_options,
            'marks' => (float) $q->marks,
            'auto' => '0',
        ])->all();
    }
    $initialQuestions = array_values($initialQuestions ?? []);
@endphp

<div class="col-12">
    <hr class="my-1">
    <span class="registration-section-label">Access &amp; delivery</span>
</div>

<div class="col-md-6">
    <div class="mb-3">
        <label for="enrol_key" class="form-label">Exam enrolment key: *</label>
        <input type="text" name="enrol_key" id="enrol_key" class="form-control" autocomplete="off"
            value="{{ old('enrol_key', $exam->enrol_key ?? '') }}" placeholder="Students need this key to start">
        <div class="form-text">Share it with students when the exam begins.</div>
        @error('enrol_key')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="mb-3">
        <label for="duration_minutes" class="form-label">Duration (minutes): *</label>
        <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" min="1" max="1440"
            value="{{ old('duration_minutes', $exam->duration_minutes ?? 60) }}">
        <div class="form-text">The timer starts when a student enters the key.</div>
        @error('duration_minutes')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="col-12">
    <label class="form-label d-block">Exam format: *</label>
    <div class="exam-format-toggle" role="group" aria-label="Exam format">
        <input type="radio" class="btn-check" name="exam_type" id="exam_type_paper" value="paper" @checked($examType === 'paper')>
        <label class="btn btn-outline-primary" for="exam_type_paper"><i class="bi bi-file-earmark-pdf"></i> Paper (PDF)</label>

        <input type="radio" class="btn-check" name="exam_type" id="exam_type_quiz" value="quiz" @checked($examType === 'quiz')>
        <label class="btn btn-outline-primary" for="exam_type_quiz"><i class="bi bi-ui-checks"></i> Online quiz</label>
    </div>
    @error('exam_type')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

{{-- Paper: upload a pdf --}}
<div class="col-12" id="paper-section">
    <div class="mb-3">
        <label for="paper" class="form-label">Exam paper (PDF, max 20 MB): {{ $exam && $exam->paper_path ? '' : '*' }}</label>
        <input type="file" name="paper" id="paper" class="form-control" accept="application/pdf">
        @if ($exam && $exam->paper_path)
            <div class="form-text">
                A paper is already uploaded &mdash;
                <a href="{{ route('exam.paper', $exam->id) }}" target="_blank" rel="noopener">view current paper</a>.
                Choose a new file only to replace it.
            </div>
        @endif
        @error('paper')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Quiz: multiple choice question builder --}}
<div class="col-12" id="quiz-section">
    <div class="mb-2">
        <strong>Questions</strong>
        <span class="quiz-total-status" id="quiz-total-status">
            Question marks: <span id="quiz-total">0</span> / <span id="quiz-full">0</span> full marks
        </span>
    </div>

    <div id="marks-mismatch" class="alert alert-danger py-2 d-none" role="alert"></div>
    @error('questions')
        <div class="alert alert-danger py-2">{{ $message }}</div>
    @enderror
    @if ($errors->has('questions.*'))
        <div class="alert alert-danger py-2">{{ $errors->first('questions.*') }}</div>
    @endif

    <p class="form-text mt-0">
        Fill in at least two options per question and click <span class="badge-new"><i class="bi bi-check-lg"></i> Correct</span>
        next to every right answer &mdash; a question can have more than one. Students only get the marks when they pick
        exactly the correct answers. Empty options are ignored.
        Each question gets <em>full marks &divide; number of questions</em> by default; a mark you type yourself stays as you set it.
    </p>
    <div id="question-list" class="d-flex flex-column gap-3"></div>

    <button type="button" class="builder-add-question w-100 mt-3" id="add-question">
        <i class="bi bi-plus-lg"></i> Add question
    </button>
</div>

<template id="question-template">
    <div class="builder-question question-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong class="question-number"></strong>
            <button type="button" class="btn btn-sm builder-remove remove-question" title="Remove question">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-md-9">
                <label class="form-label small mb-1">Question</label>
                <textarea class="form-control q-text" rows="2" placeholder="Type the question"></textarea>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Marks for this question</label>
                <div class="input-group">
                    <input type="number" class="form-control q-marks" min="0.01" step="0.01">
                    <button type="button" class="btn btn-outline-secondary q-marks-reset d-none" title="Back to the default mark">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
                <div class="form-text q-marks-hint"></div>
                <input type="hidden" class="q-auto">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1">Answers &mdash; mark every correct one</label>
                <div class="q-options"></div>
                <div class="small text-muted q-correct-count"></div>
                <div class="text-danger small q-correct-missing d-none">Select at least one correct answer for this question.</div>
            </div>
        </div>
    </div>
</template>

@push('script')
<script>
(function () {
    const OPTION_COUNT = 4;
    const initial = @json($initialQuestions);
    const list = document.getElementById('question-list');
    const template = document.getElementById('question-template');
    const fullMarksInput = document.getElementById('total_marks');
    const quizTotal = document.getElementById('quiz-total');
    const quizFull = document.getElementById('quiz-full');
    const totalStatus = document.getElementById('quiz-total-status');
    const mismatch = document.getElementById('marks-mismatch');
    const paperSection = document.getElementById('paper-section');
    const quizSection = document.getElementById('quiz-section');
    const form = list.closest('form');
    let uid = 0;

    const round2 = (n) => Math.round(n * 100) / 100;
    const fullMarks = () => parseFloat(fullMarksInput.value) || 0;
    const cards = () => Array.from(list.querySelectorAll('.question-card'));
    const isAuto = (card) => card.querySelector('.q-auto').value === '1';

    function isQuiz() {
        return document.getElementById('exam_type_quiz').checked;
    }

    // default mark = full marks / number of questions, only for questions the teacher has not edited.
    // the rounding remainder (e.g. 100 / 3) goes to the last default question so untouched quizzes add up exactly.
    function applyDefaults() {
        const all = cards();
        if (!all.length) return;
        const each = round2(fullMarks() / all.length);
        const autos = all.filter(isAuto);
        autos.forEach(function (card) { card.querySelector('.q-marks').value = each; });
        if (autos.length === all.length && autos.length) {
            const remainder = round2(fullMarks() - each * all.length);
            const last = autos[autos.length - 1].querySelector('.q-marks');
            last.value = round2(each + remainder);
        }
        all.forEach(function (card) {
            card.querySelector('.q-marks-hint').textContent = isAuto(card) ? 'Default (' + each + ')' : 'Set by you';
            card.querySelector('.q-marks-reset').classList.toggle('d-none', isAuto(card));
        });
    }

    function questionTotal() {
        return round2(cards().reduce(function (sum, card) {
            return sum + (parseFloat(card.querySelector('.q-marks').value) || 0);
        }, 0));
    }

    function updateTotals() {
        const total = questionTotal(), full = fullMarks();
        quizTotal.textContent = total;
        quizFull.textContent = full;
        const ok = Math.abs(total - full) < 0.005;
        totalStatus.className = 'quiz-total-status ' + (ok ? 'is-ok' : 'is-off');
        if (ok) mismatch.classList.add('d-none');
    }

    // keep input names in order (questions[0][...], questions[1][...]) after add / remove
    function renumber() {
        cards().forEach(function (card, i) {
            card.querySelector('.question-number').textContent = 'Question ' + (i + 1);
            card.querySelector('.q-text').name = 'questions[' + i + '][text]';
            card.querySelector('.q-marks').name = 'questions[' + i + '][marks]';
            card.querySelector('.q-auto').name = 'questions[' + i + '][auto]';
            card.querySelectorAll('.q-option').forEach(function (input, o) {
                input.name = 'questions[' + i + '][options][' + o + ']';
            });
            card.querySelectorAll('.q-correct').forEach(function (box) {
                box.name = 'questions[' + i + '][correct][]';
            });
        });
        applyDefaults();
        updateTotals();
    }

    function highlightCorrect(card) {
        card.querySelectorAll('.q-correct').forEach(function (box) {
            box.closest('.input-group').querySelector('.q-option').classList.toggle('border-success', box.checked);
        });
        const count = card.querySelectorAll('.q-correct:checked').length;
        card.querySelector('.q-correct-count').textContent =
            count > 1 ? count + ' correct answers — students will tick all that apply.' : '';
        if (count) {
            card.querySelector('.q-correct-missing').classList.add('d-none');
        }
    }

    function addQuestion(data) {
        data = data || {};
        const card = template.content.firstElementChild.cloneNode(true);
        const auto = data.auto !== undefined ? String(data.auto) === '1' : data.marks === undefined;
        card.querySelector('.q-text').value = data.text || '';
        card.querySelector('.q-auto').value = auto ? '1' : '0';
        if (!auto) card.querySelector('.q-marks').value = data.marks;

        // saved / old() correct answers: a list of option indexes
        const correct = [].concat(data.correct === undefined || data.correct === null ? [] : data.correct).map(String);

        const options = card.querySelector('.q-options');
        for (let o = 0; o < OPTION_COUNT; o++) {
            const id = 'q-correct-' + (++uid);
            const row = document.createElement('div');
            row.className = 'input-group mb-1';
            row.innerHTML =
                '<span class="input-group-text">' + String.fromCharCode(65 + o) + '</span>' +
                '<input type="text" class="form-control q-option" placeholder="Option ' + String.fromCharCode(65 + o) + '">' +
                '<input type="checkbox" class="btn-check q-correct" id="' + id + '" value="' + o + '" autocomplete="off">' +
                '<label class="btn builder-correct" for="' + id + '" title="Mark as a correct answer"><i class="bi bi-check-lg"></i> Correct</label>';
            row.querySelector('.q-option').value = (data.options && data.options[o]) || '';
            if (correct.indexOf(String(o)) !== -1) {
                row.querySelector('.q-correct').checked = true;
            }
            row.querySelector('.q-correct').addEventListener('change', function () { highlightCorrect(card); });
            options.appendChild(row);
        }
        highlightCorrect(card);

        // typing a mark makes it the teacher's own; the other questions keep their values
        card.querySelector('.q-marks').addEventListener('input', function () {
            card.querySelector('.q-auto').value = '0';
            card.querySelector('.q-marks-hint').textContent = 'Set by you';
            card.querySelector('.q-marks-reset').classList.remove('d-none');
            updateTotals();
        });
        card.querySelector('.q-marks-reset').addEventListener('click', function () {
            card.querySelector('.q-auto').value = '1';
            renumber();
        });
        card.querySelector('.remove-question').addEventListener('click', function () {
            card.remove();
            renumber();
        });

        list.appendChild(card);
        renumber();
    }

    function toggleType() {
        const quiz = isQuiz();
        paperSection.classList.toggle('d-none', quiz);
        quizSection.classList.toggle('d-none', !quiz);
        // disabled inputs are not submitted, so the quiz builder only posts when it is used
        quizSection.querySelectorAll('input, textarea').forEach(function (el) { el.disabled = !quiz; });
        paperSection.querySelectorAll('input').forEach(function (el) { el.disabled = quiz; });
        if (quiz && !list.children.length) addQuestion();
    }

    // stop the submit and tell the teacher what is wrong with the quiz
    form.addEventListener('submit', function (e) {
        if (!isQuiz()) return;

        let firstProblem = null;
        cards().forEach(function (card) {
            const missing = !card.querySelector('.q-correct:checked');
            card.querySelector('.q-correct-missing').classList.toggle('d-none', !missing);
            if (missing && !firstProblem) firstProblem = card;
        });

        const total = questionTotal(), full = fullMarks();
        const diff = round2(total - full);
        if (Math.abs(diff) >= 0.005) {
            mismatch.innerHTML = '<i class="bi bi-exclamation-triangle"></i> The question marks add up to <strong>' + total +
                '</strong>, which is <strong>' + Math.abs(diff) + ' ' + (diff > 0 ? 'more' : 'less') +
                '</strong> than the full marks (<strong>' + full + '</strong>). Change the question marks or the full marks.';
            mismatch.classList.remove('d-none');
            firstProblem = mismatch;
        }

        if (firstProblem) {
            e.preventDefault();
            firstProblem.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    document.getElementById('add-question').addEventListener('click', function () {
        addQuestion();
        toggleType();
        // jump straight to the new question
        const added = list.lastElementChild;
        added.scrollIntoView({ behavior: 'smooth', block: 'center' });
        added.querySelector('.q-text').focus({ preventScroll: true });
    });
    document.querySelectorAll('input[name="exam_type"]').forEach(function (radio) {
        radio.addEventListener('change', toggleType);
    });
    fullMarksInput.addEventListener('input', renumber);

    initial.forEach(addQuestion);
    toggleType();
})();
</script>
@endpush
