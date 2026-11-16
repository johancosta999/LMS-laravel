<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use App\Models\Teacher;

class SubjectController extends Controller
{
    public function module_list(){
        $modules = Subject::all();
        return view("SubjectList", compact("modules"));
    }

    public function module_create(){
        $module_id = IdGenerator::next('subjects');
        $teachers = Teacher::all();
        return view("SubjectRegister", compact("module_id", "teachers"));
    }

    public function createModule(Request $request){
        $request->validate([
            'enrol_key' => 'required',
            'assigned_lecturers' => 'required|array',
        ]);

        try{
            Subject::query() -> create([
                // generated again on save so it can't be stale / edited in the browser
                "module_id"=> IdGenerator::next('subjects'),
                "name"=> $request->name,
                "lectures_count"=> $request->lectures_count,
                // selected lecturer names saved as "Name A, Name B"
                "assigned_lecturers"=> implode(', ', $request->assigned_lecturers),
                "enrol_key"=> $request->enrol_key,
            ]);

            return redirect() -> route("subject.module_list");
        } catch (\Exception $e) {
            return $e;
        } 
    }

    public function edit($id){
        $module = Subject::query()
            ->where("id", $id) 
            ->first();

        $teachers = Teacher::all();
        // names already assigned, so their checkboxes start ticked
        $assigned = array_map('trim', explode(',', $module->assigned_lecturers));

        return view("SubjectUpdate", compact("module", "teachers", "assigned"));
    }

    public function updateSubject(Request $request) {
        $request->validate([
            'assigned_lecturers' => 'required|array',
        ]);

        try{
            // module_id is not updated: it is generated once when the module is created
            Subject::query()
            ->where("id", $request->id)
            ->update([
                "name"=> $request->name,
                "lectures_count"=> $request->lectures_count,
                "assigned_lecturers"=> implode(', ', $request->assigned_lecturers),
                "enrol_key"=> $request->enrol_key,
            ]);;
            return redirect() -> route("subject.module_list");
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function deleteSubject($id) {
        try{
            Subject::query()
                ->where("id", $id)
                ->delete();
            return redirect() -> route("subject.module_list");

        } catch (\Exception $e) {
            return $e;
        }
    }

    // ---------- student side ----------

    // logged in user -> matching student record (same email)
    private function currentStudent(){
        return Student::query()
            ->where("email", Auth::user()->email)
            ->firstOrFail();
    }

    // all modules in the system + which ones this student already joined
    public function student_modules(){
        $modules = Subject::all();
        $enrolledIds = $this->currentStudent()->subjects()->pluck('subjects.id')->toArray();

        return view("StudentModules", compact("modules", "enrolledIds"));
    }

    public function enrol(Request $request, $id){
        $module = Subject::query()->findOrFail($id);

        if (!$module->enrol_key || $request->enrol_key !== $module->enrol_key) {
            return back()->with('error', 'Wrong enrolment key for ' . $module->name);
        }

        // syncWithoutDetaching = no duplicate if already enrolled
        $this->currentStudent()->subjects()->syncWithoutDetaching([$module->id]);

        return back()->with('success', 'You are now enrolled in ' . $module->name);
    }

    // only the modules this student is enrolled in
    public function assigned_modules(){
        $modules = $this->currentStudent()->subjects;

        return view("StudentAssignedModules", compact("modules"));
    }

    // one enrolled module: details + its exams with this student's marks
    public function view_module($id){
        $student = $this->currentStudent();

        // findOrFail on the relation = 404 if the student is not enrolled in it
        $module = $student->subjects()->findOrFail($id);

        $exams = Exam::query()
            ->where('subject_id', $module->id)
            ->orderBy('exam_date')
            ->get();

        // exam_id => marks of this student
        $marks = ExamResult::query()
            ->where('student_id', $student->id)
            ->whereIn('exam_id', $exams->pluck('id'))
            ->pluck('marks', 'exam_id');

        return view("StudentModuleView", compact("module", "exams", "marks"));
    }
}
