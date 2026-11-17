<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Exam;
use App\Models\Student;
use App\Models\User;

class StudentController extends Controller
{
    
    public function student_create(){
        return view("StudentRegister");
    }

    public function student_list(){
        $students = Student::all();
        return view("StudentList", compact("students"));
    }

    public function createStudent(Request $request) {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
        ]);

        try{
            $imagePath = ImageUploader::imageUploader($request->file("image"), 'Student/profile');

            Student::query() ->create([
                "reg_no" => $request->reg_no,
                "name"=> $request->name,
                "phone_number"=> $request->phone_number,
                "email"=> $request->email,
                "address"=> $request->address,
                "birth_date" => $request -> birth_date,
                "image"=> $imagePath,
                "password" => Hash::make($request->password),
            ]);

            // login account with the student role
            User::query()->create([
                "name" => $request->name,
                "email" => $request->email,
                "role" => "student",
                "password" => Hash::make($request->password),
            ]);

            return redirect() -> route("student.student_list");
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function deleteStudent($id) {
        try{
            $student = Student::query()->where("id", $id)->first();

            // remove the login account too
            User::query()
                ->where("email", $student->email)
                ->where("role", "student")
                ->delete();

            $student->delete();
            return redirect() -> route("student.student_list");

        } catch (\Exception $e) {
            return $e;
        }
    }
    
    public function edit($id){
        $student = Student::query()
            ->where("id", $id) 
            ->first();

        return view("StudentUpdate", compact("student"));
    }

    public function updateStudent(Request $request) {
        try{
            $student = Student::query()->where("id", $request->id)->first();

            // keep the login account name/email same as the student
            User::query()
                ->where("email", $student->email)
                ->where("role", "student")
                ->update([
                    "name" => $request->name,
                    "email" => $request->email,
                ]);

            Student::query()
            ->where("id", $request->id)
            ->update([
                "reg_no" => $request->reg_no,
                "name"=> $request->name,
                "phone_number"=> $request->phone_number,
                "email"=> $request->email,
                "address"=> $request->address,
                "birth_date" => $request -> birth_date,
            ]);;
            return redirect() -> route("student.student_list");
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function dashboard(){
        // logged in user -> matching student record (same email)
        $student = Student::query()
            ->where("email", Auth::user()->email)
            ->first();

        // newly created exams in the modules this student is enrolled in
        $newExams = collect();
        if ($student) {
            $newExams = Exam::query()
                ->with('subject')
                ->whereIn('subject_id', $student->subjects()->pluck('subjects.id'))
                ->recentlyCreated()
                ->latest()
                ->get();
        }

        return view("StudentDashboard", compact("student", "newExams"));
    }

    public function student_id(){
        try{
            $students = Student::all();
            return view("StudentId", compact("students"));

        } catch (\Exception $e) {

        }
    }
    
}
