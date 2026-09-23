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
    
}
