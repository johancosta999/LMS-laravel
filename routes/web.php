<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;

use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dasboard');

Route::prefix('admin')->group(function () {
    //student routes
    Route::get('/student/create', [StudentController::class, 'student_create'])->name('student.student_create');
    Route::post('/student/save', [StudentController::class, 'createStudent'])->name('student.createStudent');

    Route::get('/student/list', [StudentController::class, 'student_list'])->name('student.student_list');

    Route::get('/student/delete/{id}', [StudentController::class, 'deleteStudent'])->name('student.delete');

    Route::get('/student/edit/{id}', [StudentController::class,'edit'])->name('student.edit');
    Route::post('/student/update', [StudentController::class,'updateStudent'])->name('student.update');

    //teacher routes
    Route::post('/lecturer/save', [TeacherController::class, 'createTeacher'])->name('teacher.createTeacher');
    Route::get('/lecturer/create', [TeacherController::class, 'teacher_create'])->name('teacher.teacher_create');
    Route::get('/lecturer/list', [TeacherController::class, 'teacher_list'])->name('teacher.teacher_list');

    //subject routes
    Route::post('/module/save', [SubjectController::class, 'createModule'])->name('subject.createModule');
    Route::get('/module/create', [SubjectController::class, 'module_create'])->name('subject.module_create');
    Route::get('/module/list', [SubjectController::class, 'module_list'])->name('subject.module_list');
});
