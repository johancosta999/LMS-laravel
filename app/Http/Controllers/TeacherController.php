<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Exam;
use App\Models\Teacher;
use App\Models\User;

class TeacherController extends Controller
{
    public function teacher_list(){
        $teachers = Teacher::all();
        return view("TeacherList", compact("teachers"));
    }

    public function teacher_create(){
        return view("TeacherRegister");
    }

    public function createTeacher(Request $request) {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
        ]);

        try{
            Teacher::query() -> create([
                "lecturer_id"=> $request->lecturer_id,
                "name"=> $request->name,
                "phone_number"=> $request->phone_number,
                "email"=> $request->email,
                "address"=> $request->address,
                "age"=> $request -> age,
                "subjects"=> $request -> subjects,
                "password"=> Hash::make($request -> password),
            ]);

            // login account with the teacher role
            User::query()->create([
                "name" => $request->name,
                "email" => $request->email,
                "role" => "teacher",
                "password" => Hash::make($request->password),
            ]);

            return redirect() -> route("teacher.teacher_list");
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function edit($id){
        $teacher = Teacher::query()
            ->where("id", $id)
            ->first();

        return view("TeacherUpdate", compact("teacher"));
    }

    public function updateTeacher(Request $request) {
        try{
            $teacher = Teacher::query()->where("id", $request->id)->first();

            // keep the login account name/email same as the teacher
            User::query()
                ->where("email", $teacher->email)
                ->where("role", "teacher")
                ->update([
                    "name" => $request->name,
                    "email" => $request->email,
                ]);

            Teacher::query()
            ->where("id", $request->id)
            ->update([
                "lecturer_id"=> $request->lecturer_id,
                "name"=> $request->name,
                "phone_number"=> $request->phone_number,
                "email"=> $request->email,
                "address"=> $request->address,
                "age"=> $request -> age,
                "subjects"=> $request -> subjects,
            ]);
            return redirect() -> route("teacher.teacher_list");
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function deleteTeacher($id) {
        try{
            $teacher = Teacher::query()->where("id", $id)->first();

            // remove the login account too
            User::query()
                ->where("email", $teacher->email)
                ->where("role", "teacher")
                ->delete();

            $teacher->delete();
            return redirect() -> route("teacher.teacher_list");

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function dashboard(){
        // logged in user -> matching teacher record (same email)
        $teacher = Teacher::query()
            ->where("email", Auth::user()->email)
            ->first();

        // latest exams of this teacher
        $exams = collect();
        $examCount = 0;
        if ($teacher) {
            $exams = Exam::query()
                ->with('subject')
                ->where('teacher_id', $teacher->id)
                ->orderBy('exam_date', 'desc')
                ->take(5)
                ->get();
            $examCount = Exam::query()->where('teacher_id', $teacher->id)->count();
        }

        return view("TeacherDashboard", compact("teacher", "exams", "examCount"));
    }

}
