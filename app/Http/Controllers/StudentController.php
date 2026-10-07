<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

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
                "password" => $request -> password,
            ]);
            return redirect() -> route("student.student_list");
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function deleteStudent($id) {
        try{
            Student::query()
                ->where("id", $id)
                ->delete();
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
    
}
