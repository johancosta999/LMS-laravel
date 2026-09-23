<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'dashboard']) -> name('admin.dasboard');

//student routes
Route::get('/student/create', [AdminController::class, 'student_create']) -> name('admin.student_create');
Route::post('/student/save', [AdminController::class,'createStudent']) -> name('admin.createStudent');
Route::get('/student/list', [AdminController::class, 'student_list']) -> name('admin.student_list');

//teacher routes
Route::post('/lecturer/save', [AdminController::class,'createTeacher']) -> name('admin.createTeacher');
Route::get('/lecturer/create', [AdminController::class, 'teacher_create']) -> name('admin.teacher_create');
Route::get('/lecturer/list', [AdminController::class, 'teacher_list']) -> name('admin.teacher_list');

//subject routes
Route::post('/module/save', [AdminController::class,'createModule']) -> name('admin.createModule');
Route::get('/module/create', [AdminController::class, 'module_create']) -> name('admin.module_create');
Route::get('/module/list', [AdminController::class, 'module_list']) -> name('admin.module_list');

