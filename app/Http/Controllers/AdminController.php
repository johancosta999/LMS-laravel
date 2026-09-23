<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        return view("AdminDashboard");
    }

    public function student_create(){
        return view("StudentRegister");
    }

    public function student_list(){
        return view("StudentList");
    }
    
    public function teacher_list(){
        return view("TeacherList");
    }

    public function teacher_create(){
        return view("TeacherRegister");
    }

    public function module_list(){
        return view("SubjectList");
    }

    public function module_create(){
        return view("SubjectRegister");
    }
}
