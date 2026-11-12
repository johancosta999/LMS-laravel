<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $request->validate([
            'title' => ['required'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'exam_date' => ['required', 'date'],
            'total_marks' => ['required', 'integer', 'min:1'],
            'pass_marks' => ['required', 'integer', 'min:0', 'lte:total_marks'],
        ]);

        Exam::query()->create([
            'teacher_id' => $this->currentTeacher()->id,
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'exam_date' => $request->exam_date,
            'total_marks' => $request->total_marks,
            'pass_marks' => $request->pass_marks,
            'description' => $request->description,
        ]);

        return redirect()->route('exam.exam_list')->with('success', 'Exam created successfully');
    }

    public function edit($id){
        $exam = $this->findMyExam($id);
        $subjects = Subject::all();

        return view('ExamUpdate', compact('exam', 'subjects'));
    }

    public function updateExam(Request $request){
        $request->validate([
            'title' => ['required'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'exam_date' => ['required', 'date'],
            'total_marks' => ['required', 'integer', 'min:1'],
            'pass_marks' => ['required', 'integer', 'min:0', 'lte:total_marks'],
        ]);

        $exam = $this->findMyExam($request->id);

        $exam->update([
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'exam_date' => $request->exam_date,
            'total_marks' => $request->total_marks,
            'pass_marks' => $request->pass_marks,
            'description' => $request->description,
        ]);

        return redirect()->route('exam.exam_list')->with('success', 'Exam updated successfully');
    }

    public function deleteExam($id){
        $exam = $this->findMyExam($id);
        $exam->delete(); // results are deleted too (cascade)

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
