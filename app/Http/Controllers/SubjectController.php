<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function module_list(){
        $modules = Subject::all();
        return view("SubjectList", compact("modules"));
    }

    public function module_create(){
        return view("SubjectRegister");
    }

    public function createModule(Request $request){
        try{
            Subject::query() -> create([
                "module_id"=> $request->module_id,
                "name"=> $request->name,
                "lectures_count"=> $request->lectures_count,
                "assigned_lecturers"=> $request->assigned_lecturers,
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

        return view("SubjectUpdate", compact("module"));
    }

    public function updateSubject(Request $request) {
        try{
            Subject::query()
            ->where("id", $request->id)
            ->update([
                "reg_no" => $request->reg_no,
                "name"=> $request->name,
                "phone_number"=> $request->phone_number,
                "email"=> $request->email,
                "address"=> $request->address,
                "birth_date" => $request -> birth_date,
            ]);;
            return redirect() -> route("subject.subject_list");
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function deleteSubject($id) {
        try{
            Subject::query()
                ->where("id", $id)
                ->delete();
            return redirect() -> route("subject.subject_list");

        } catch (\Exception $e) {
            return $e;
        }
    }
}
