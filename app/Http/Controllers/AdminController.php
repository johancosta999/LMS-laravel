<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;

class AdminController extends Controller
{
    public function dashboard() {
        return view("AdminDashboard");
    }

    public function student_create(){
        return view("StudentRegister");
    }

    public function student_list(){
        $students = Student::all();
        return view("StudentList", compact("students"));
    }
    
    public function teacher_list(){
        $teachers = Teacher::all();
        return view("TeacherList", compact("teachers"));
    }

    public function teacher_create(){
        return view("TeacherRegister");
    }

    public function module_list(){
        $modules = Subject::all();
        return view("SubjectList", compact("modules"));
    }

    public function module_create(){
        return view("SubjectRegister");
    }

    public function createStudent(Request $request) {
        try{
            Student::query() ->create([
                "reg_no" => $request->reg_no,
                "name"=> $request->name,
                "phone_number"=> $request->phone_number,
                "email"=> $request->email,
                "address"=> $request->address,
                "birth_date" => $request -> birth_date,
                "password" => $request -> password,
            ]);
            return redirect() -> route("admin.student_list");
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function createTeacher(Request $request) {
        try{
            Teacher::query() -> create([
                "lecturer_id"=> $request->lecturer_id,
                "name"=> $request->name,
                "phone_number"=> $request->phone_number,
                "email"=> $request->email,
                "address"=> $request->address,
                "age"=> $request -> age,
                "subjects"=> $request -> subjects,
                "password"=> $request -> password,
            ]);

            return redirect() -> route("admin.teacher_list");
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    public function createModule(Request $request){
        try{
            Subject::query() -> create([
                "module_id"=> $request->module_id,
                "name"=> $request->name,
                "lectures_count"=> $request->lectures_count,
                "assigned_lecturers"=> $request->assigned_lecturers,
            ]);

            return redirect() -> route("admin.module_list");
        } catch (\Exception $e) {
            return $e;
        } 

    }

}
