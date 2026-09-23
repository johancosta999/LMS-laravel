<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'dashboard']) -> name('admin.dasboard');
Route::get('/student/create', [AdminController::class, 'student_create']) -> name('admin.student_create');