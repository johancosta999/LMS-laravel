<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;

class AdminController extends Controller
{
    public function dashboard() {
        // totals
        $studentCount = Student::count();
        $teacherCount = Teacher::count();
        $moduleCount = Subject::count();

        // added this month
        $newStudents = Student::where('created_at', '>=', now()->startOfMonth())->count();
        $newTeachers = Teacher::where('created_at', '>=', now()->startOfMonth())->count();
        $newModules = Subject::where('created_at', '>=', now()->startOfMonth())->count();

        // latest 5 of each
        $recentStudents = Student::latest()->take(5)->get();
        $recentTeachers = Teacher::latest()->take(5)->get();
        $recentModules = Subject::latest()->take(5)->get();

        // how many students per lecturer
        $studentsPerTeacher = $teacherCount > 0 ? round($studentCount / $teacherCount, 1) : 0;

        return view("AdminDashboard", compact(
            'studentCount',
            'teacherCount',
            'moduleCount',
            'newStudents',
            'newTeachers',
            'newModules',
            'recentStudents',
            'recentTeachers',
            'recentModules',
            'studentsPerTeacher'
        ));
    }

}
