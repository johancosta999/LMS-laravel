<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamResult;
use App\Models\Student;

// a student sitting an exam: unlock with the key, timed paper / online quiz
class StudentExamController extends Controller
{
    private function currentStudent(){
        return Student::query()
            ->where('email', Auth::user()->email)
            ->firstOrFail();
    }

    // exam must belong to a module the student is enrolled in, otherwise 404
    private function findExam(Student $student, $id){
        $exam = Exam::query()->with('subject')->findOrFail($id);

        abort_unless($student->subjects()->where('subjects.id', $exam->subject_id)->exists(), 404);

        return $exam;
    }

    private function findAttempt(Student $student, Exam $exam){
        return ExamAttempt::query()
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();
    }

    // exam details + key form (or link back to an attempt already started)
    public function show($id){
        $student = $this->currentStudent();
        $exam = $this->findExam($student, $id);
        $attempt = $this->findAttempt($student, $exam);

        return view('StudentExamStart', compact('exam', 'attempt'));
    }

    // check the key and start the timer (only once per student)
    public function start(Request $request, $id){
        $student = $this->currentStudent();
        $exam = $this->findExam($student, $id);

        if (!$this->findAttempt($student, $exam)) {
            if ($exam->enrol_key && $request->input('enrol_key') !== $exam->enrol_key) {
                return back()->with('error', 'Wrong enrolment key for ' . $exam->title);
            }

            ExamAttempt::query()->create([
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'started_at' => now(),
            ]);
        }

        return redirect()->route('student.exam.take', $exam->id);
    }

    public function take($id){
        $student = $this->currentStudent();
        $exam = $this->findExam($student, $id);
        $attempt = $this->findAttempt($student, $exam);

        if (!$attempt) {
            return redirect()->route('student.exam.show', $exam->id);
        }
        $attempt->setRelation('exam', $exam);

        // timer ran out without a submit (tab closed etc.) -> close the quiz with what was saved
        if ($exam->isQuiz() && !$attempt->submitted_at && $attempt->isOver()) {
            $this->finishQuiz($exam, $attempt, []);
        }

        if ($attempt->submitted_at) {
            return redirect()->route('student.exam.show', $exam->id);
        }

        $questions = $exam->isQuiz() ? $exam->questions()->get() : collect();
        $secondsLeft = $attempt->secondsLeft();

        return view('StudentExamTake', compact('exam', 'attempt', 'questions', 'secondsLeft'));
    }

    public function submit(Request $request, $id){
        $student = $this->currentStudent();
        $exam = $this->findExam($student, $id);
        $attempt = $this->findAttempt($student, $exam);

        abort_unless($attempt && $exam->isQuiz(), 404);
        $attempt->setRelation('exam', $exam);

        if ($attempt->submitted_at) {
            return redirect()->route('student.exam.show', $exam->id);
        }

        // answers sent after the timer (+ grace) are not counted
        $answers = $attempt->isOver() ? [] : (array) $request->input('answers', []);
        $late = $attempt->isOver();

        $this->finishQuiz($exam, $attempt, $answers);

        return redirect()->route('student.exam.show', $exam->id)->with(
            $late ? 'error' : 'success',
            $late ? 'Time was up before the quiz was submitted.' : 'Quiz submitted. Your score: ' . $attempt->score . ' / ' . $exam->total_marks
        );
    }

    // after a quiz is submitted: the questions the student got wrong (or skipped) with the correct answers
    public function review($id){
        $student = $this->currentStudent();
        $exam = $this->findExam($student, $id);
        $attempt = $this->findAttempt($student, $exam);

        abort_unless($exam->isQuiz() && $attempt && $attempt->submitted_at, 404);

        // the teacher decides if / when the answers are shown
        if (!$exam->reviewIsOpen()) {
            return redirect()->route('student.exam.show', $exam->id)->with('error', $exam->allowsReview()
                ? 'Your incorrect answers can be reviewed from ' . $exam->review_available_at->format('M jS Y, H:i') . '.'
                : 'Your lecturer has not made the answers available for this quiz.');
        }

        $answers = $attempt->answers ?? [];
        $questions = $exam->questions()->get();

        $wrong = $questions
            ->map(function ($question, $i) use ($answers) {
                $question->number = $i + 1;
                $question->chosen = array_map('intval', (array) ($answers[$question->id] ?? []));
                return $question;
            })
            ->reject(fn ($question) => $question->isCorrect($question->chosen))
            ->values();

        return view('StudentExamReview', [
            'exam' => $exam,
            'attempt' => $attempt,
            'wrong' => $wrong,
            'totalQuestions' => $questions->count(),
        ]);
    }

    // grade the answers and copy the score into the normal exam results
    private function finishQuiz(Exam $exam, ExamAttempt $attempt, array $answers){
        $score = 0;
        $saved = [];

        foreach ($exam->questions as $question) {
            // one or more chosen option indexes per question
            $chosen = array_filter((array) ($answers[$question->id] ?? []), fn ($v) => $v !== null && $v !== '');
            if (!$chosen) {
                continue;
            }
            $saved[$question->id] = array_values(array_map('intval', $chosen));
            if ($question->isCorrect($chosen)) {
                $score += (float) $question->marks;
            }
        }

        $attempt->update([
            'answers' => $saved,
            'score' => $score,
            'submitted_at' => now(),
        ]);

        ExamResult::query()->updateOrCreate(
            ['exam_id' => $exam->id, 'student_id' => $attempt->student_id],
            ['marks' => $score]
        );
    }

    // the pdf paper, only after the key was entered and only while the timer runs
    public function paper($id){
        $student = $this->currentStudent();
        $exam = $this->findExam($student, $id);
        $attempt = $this->findAttempt($student, $exam);

        abort_unless($attempt && !$exam->isQuiz() && $exam->paper_path, 404);
        $attempt->setRelation('exam', $exam);
        abort_if($attempt->isOver(), 403, 'The time for this exam is over.');
        abort_unless(Storage::disk('local')->exists($exam->paper_path), 404);

        return response()->file(Storage::disk('local')->path($exam->paper_path), [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
