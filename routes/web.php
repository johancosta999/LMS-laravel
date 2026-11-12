<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;

use Illuminate\Support\Facades\Route;

// common login for admin, teacher and student
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('/login-check', [LoginController::class, 'loginCheck'])->name('login.check');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// student dashboard (role = student)
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
});

// teacher pages (role = teacher)
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/lecturer/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');

    Route::prefix('lecturer/exams')->group(function () {
        //exam routes
        Route::get('/list', [ExamController::class, 'exam_list'])->name('exam.exam_list');
        Route::get('/create', [ExamController::class, 'exam_create'])->name('exam.exam_create');
        Route::post('/save', [ExamController::class, 'createExam'])->name('exam.createExam');
        Route::get('/edit/{id}', [ExamController::class, 'edit'])->name('exam.edit');
        Route::post('/update', [ExamController::class, 'updateExam'])->name('exam.update');
        Route::get('/delete/{id}', [ExamController::class, 'deleteExam'])->name('exam.delete');

        //marks + report
        Route::get('/marks/{id}', [ExamController::class, 'marks'])->name('exam.marks');
        Route::post('/marks/{id}', [ExamController::class, 'saveMarks'])->name('exam.saveMarks');
        Route::get('/report/{id}', [ExamController::class, 'report'])->name('exam.report');
    });
});

// admin pages (role = admin)
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dasboard');

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
});
