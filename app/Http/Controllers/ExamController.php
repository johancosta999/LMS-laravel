<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;

class ExamController extends Controller
{
    // the teacher record of the logged in user (same email)
    private function currentTeacher(){
        return Teacher::query()
            ->where('email', Auth::user()->email)
            ->firstOrFail();
    }

    // only let a teacher open their own exams
    private function findMyExam($id){
        return Exam::query()
            ->where('id', $id)
            ->where('teacher_id', $this->currentTeacher()->id)
            ->firstOrFail();
    }

    public function exam_list(){
        $exams = Exam::query()
            ->with('subject')
            ->withCount('results')
            ->where('teacher_id', $this->currentTeacher()->id)
            ->orderBy('exam_date', 'desc')
            ->get();

        return view('ExamList', compact('exams'));
    }

    public function exam_create(){
        $subjects = Subject::all();
        return view('ExamCreate', compact('subjects'));
    }

    public function createExam(Request $request){
        $exam = new Exam();
        $exam->teacher_id = $this->currentTeacher()->id;
        $this->saveExam($request, $exam);

        $message = $exam->isQuiz()
            ? 'Online quiz created with ' . $exam->questions()->count() . ' questions'
            : 'Exam created successfully';

        return redirect()->route('exam.exam_list')->with('success', $message);
    }

    public function edit($id){
        $exam = $this->findMyExam($id);
        $exam->load('questions');
        $subjects = Subject::all();

        return view('ExamUpdate', compact('exam', 'subjects'));
    }

    public function updateExam(Request $request){
        $exam = $this->findMyExam($request->id);
        $this->saveExam($request, $exam);

        return redirect()->route('exam.exam_list')->with('success', 'Exam updated successfully');
    }

    // validate + save an exam from the create / update form (paper pdf or online quiz)
    private function saveExam(Request $request, Exam $exam){
        $isQuiz = $request->input('exam_type') === 'quiz';
        $hasPaper = $exam->exists && $exam->paper_path;

        $rules = [
            'title' => ['required'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'exam_date' => ['required', 'date'],
            'exam_type' => ['required', 'in:paper,quiz'],
            'enrol_key' => ['required', 'string', 'max:100'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'total_marks' => ['required', 'integer', 'min:1'],
            'pass_marks' => ['required', 'integer', 'min:0', 'lte:total_marks'],
        ];

        if ($isQuiz) {
            $rules += [
                'questions' => ['required', 'array', 'min:1'],
                'questions.*.text' => ['required', 'string'],
                'questions.*.options' => ['required', 'array'],
                'questions.*.options.*' => ['nullable', 'string'],
                'questions.*.correct' => ['required', 'array', 'min:1'],
                'questions.*.correct.*' => ['integer', 'min:0'],
                'questions.*.marks' => ['required', 'numeric', 'min:0.01', 'decimal:0,2'],
            ];
        } else {
            $rules['paper'] = [$hasPaper ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:20480'];
        }

        $request->validate($rules, [
            'questions.required' => 'Add at least one question to the quiz.',
            'questions.*.text.required' => 'Every question needs some text.',
            'questions.*.correct.required' => 'Select at least one correct answer for every question.',
            'questions.*.marks.*' => 'Every question needs a mark above 0 (up to 2 decimal places).',
            'paper.required' => 'Upload the exam paper as a PDF.',
        ]);

        $questions = $isQuiz ? $this->cleanQuestions($request->input('questions')) : [];
        $totalMarks = (int) $request->total_marks;

        // the question marks of a quiz must add up to the full marks exactly
        if ($isQuiz) {
            $questionTotal = round(array_sum(array_column($questions, 'marks')), 2);
            $diff = round($questionTotal - $totalMarks, 2);
            if (abs($diff) >= 0.005) {
                throw ValidationException::withMessages([
                    'questions' => "The question marks add up to $questionTotal, which is " . abs($diff) . ' '
                        . ($diff > 0 ? 'more' : 'less') . " than the full marks ($totalMarks).",
                ]);
            }
        }

        // students' saved answers point at question ids, so lock the questions once anyone has started
        if ($isQuiz && $exam->exists && $exam->attempts()->exists()) {
            throw ValidationException::withMessages([
                'questions' => 'Students have already started this quiz, so its questions can no longer be changed.',
            ]);
        }

        DB::transaction(function () use ($request, $exam, $isQuiz, $questions, $totalMarks) {
            $oldPaper = $exam->paper_path;

            $exam->fill([
                'subject_id' => $request->subject_id,
                'title' => $request->title,
                'exam_date' => $request->exam_date,
                'total_marks' => $totalMarks,
                'pass_marks' => $request->pass_marks,
                'description' => $request->description,
                'exam_type' => $isQuiz ? 'quiz' : 'paper',
                'enrol_key' => $request->enrol_key,
                'duration_minutes' => $request->duration_minutes,
            ]);

            if ($isQuiz) {
                $exam->paper_path = null;
            } elseif ($request->hasFile('paper')) {
                // private disk: only reachable through a controller, never by a public url
                $exam->paper_path = $request->file('paper')->store('exam-papers', 'local');
            }

            $exam->save();

            $exam->questions()->delete();
            foreach ($questions as $question) {
                $exam->questions()->create($question);
            }

            if ($oldPaper && $oldPaper !== $exam->paper_path) {
                Storage::disk('local')->delete($oldPaper);
            }
        });
    }

    // drop blank options and point the correct answers at the right ones after re-indexing
    private function cleanQuestions(array $input){
        $questions = [];

        foreach (array_values($input) as $i => $q) {
            $options = [];
            $correct = [];
            $correctInput = array_map('intval', (array) $q['correct']);

            foreach (($q['options'] ?? []) as $index => $option) {
                if (trim((string) $option) === '') {
                    continue;
                }
                if (in_array((int) $index, $correctInput, true)) {
                    $correct[] = count($options);
                }
                $options[] = trim($option);
            }

            $number = $i + 1;
            if (count($options) < 2) {
                throw ValidationException::withMessages([
                    'questions' => "Question $number needs at least two answer options.",
                ]);
            }
            if (count($correct) !== count(array_unique($correctInput))) {
                throw ValidationException::withMessages([
                    'questions' => "A correct answer for question $number is an empty option.",
                ]);
            }

            $questions[] = [
                'question' => trim($q['text']),
                'options' => $options,
                'correct_options' => $correct,
                'marks' => round((float) $q['marks'], 2),
            ];
        }

        return $questions;
    }

    // teacher preview of the uploaded paper
    public function paper($id){
        $exam = $this->findMyExam($id);
        abort_unless($exam->paper_path && Storage::disk('local')->exists($exam->paper_path), 404);

        return response()->file(Storage::disk('local')->path($exam->paper_path), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function deleteExam($id){
        $exam = $this->findMyExam($id);
        if ($exam->paper_path) {
            Storage::disk('local')->delete($exam->paper_path);
        }
        $exam->delete(); // results, questions and attempts are deleted too (cascade)

        return redirect()->route('exam.exam_list')->with('success', 'Exam deleted');
    }

    // page to enter marks for every student
    public function marks($id){
        $exam = $this->findMyExam($id);
        $students = Student::query()->orderBy('reg_no')->get();

        // student_id => marks, to fill the inputs
        $marks = ExamResult::query()
            ->where('exam_id', $exam->id)
            ->pluck('marks', 'student_id');

        return view('ExamMarks', compact('exam', 'students', 'marks'));
    }

    public function saveMarks(Request $request, $id){
        $exam = $this->findMyExam($id);

        $request->validate([
            'marks' => ['array'],
            'marks.*' => ['nullable', 'numeric', 'min:0', 'max:' . $exam->total_marks],
        ]);

        foreach ($request->input('marks', []) as $studentId => $mark) {
            if ($mark === null || $mark === '') {
                // empty box = no mark for this student
                ExamResult::query()
                    ->where('exam_id', $exam->id)
                    ->where('student_id', $studentId)
                    ->delete();
                continue;
            }

            ExamResult::query()->updateOrCreate(
                ['exam_id' => $exam->id, 'student_id' => $studentId],
                ['marks' => $mark]
            );
        }

        return redirect()->route('exam.report', $exam->id)->with('success', 'Marks saved successfully');
    }

    public function report($id){
        $exam = $this->findMyExam($id);

        $results = ExamResult::query()
            ->with('student')
            ->where('exam_id', $exam->id)
            ->orderBy('marks', 'desc')
            ->get();

        $average = $results->count() > 0 ? round($results->avg('marks'), 1) : 0;
        $highest = $results->max('marks') ?? 0;
        $lowest = $results->min('marks') ?? 0;
        $passed = $results->where('marks', '>=', $exam->pass_marks)->count();
        $failed = $results->count() - $passed;

        return view('ExamReport', compact('exam', 'results', 'average', 'highest', 'lowest', 'passed', 'failed'));
    }
}
