<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

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
                "password"=> $request -> password,
            ]);;
            return redirect() -> route("student.student_list");
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function deleteTeacher($id) {
        try{
            Teacher::query()
                ->where("id", $id)
                ->delete();
            return redirect() -> route("teacher.teacher_list");

        } catch (\Exception $e) {
            return $e;
        }
    }
    
}
