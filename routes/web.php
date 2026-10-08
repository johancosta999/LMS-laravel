<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;

use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dasboard');

Route::prefix('student')->group(function () {
    //student routes
    Route::get('create', [StudentController::class, 'student_create'])->name('student.student_create');
    Route::post('/save', [StudentController::class, 'createStudent'])->name('student.createStudent');

    Route::get('/list', [StudentController::class, 'student_list'])->name('student.student_list');

    Route::get('/delete/{id}', [StudentController::class, 'deleteStudent'])->name('student.delete');

    Route::get('/edit/{id}', [StudentController::class, 'edit'])->name('student.edit');
    Route::post('/update', [StudentController::class, 'updateStudent'])->name('student.update');
    Route::get('/image-list', [StudentController::class, 'student_id'])->name('student.idCard');
});

Route::prefix('lecturer')->group(function () {
    //teacher routes
    Route::post('/save', [TeacherController::class, 'createTeacher'])->name('teacher.createTeacher');
    Route::get('/create', [TeacherController::class, 'teacher_create'])->name('teacher.teacher_create');
    Route::get('/list', [TeacherController::class, 'teacher_list'])->name('teacher.teacher_list');
    Route::get('/edit/{id}', [TeacherController::class, 'edit'])->name('teacher.edit');
    Route::post('/update', [TeacherController::class, 'updateTeacher'])->name('teacher.update');
    Route::get('/delete/{id}', [TeacherController::class, 'deleteTeacher'])->name('teacher.delete');
});

Route::prefix('module')->group(function () {

    //subject routes
    Route::post('/save', [SubjectController::class, 'createModule'])->name('subject.createModule');
    Route::get('/create', [SubjectController::class, 'module_create'])->name('subject.module_create');
    Route::get('/list', [SubjectController::class, 'module_list'])->name('subject.module_list');
    Route::get('/edit/{id}', [SubjectController::class, 'edit'])->name('subject.edit');
    Route::post('/update', [SubjectController::class, 'updateSubject'])->name('subject.update');
    Route::get('/delete/{id}', [SubjectController::class, 'deleteSubject'])->name('subject.delete');
});
