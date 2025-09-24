<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('home'); });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Protected app routes
Route::middleware('auth')->group(function () {
    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('classes', ClassController::class);
    Route::resource('grades', GradeController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('users', UserController::class);
    
    // Role management routes
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::put('roles/{user}', [RoleController::class, 'update'])->name('roles.update');
});
