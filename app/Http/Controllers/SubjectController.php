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
}
