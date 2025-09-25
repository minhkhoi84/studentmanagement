<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ClassController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('home'); })->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'permission:truy-cap-he-thong'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Protected app routes with proper permissions
Route::middleware('auth')->group(function () {
<<<<<<< Updated upstream
    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('classes', ClassController::class);
=======
    // Student management routes
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index')->middleware('permission:xem-danh-sach-sinh-vien');
        Route::get('/create', [StudentController::class, 'create'])->name('create')->middleware('permission:them-moi-sinh-vien');
        Route::post('/', [StudentController::class, 'store'])->name('store')->middleware('permission:them-moi-sinh-vien');
        Route::get('/{student}', [StudentController::class, 'show'])->name('show')->middleware('permission:xem-danh-sach-sinh-vien');
        Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit')->middleware('permission:chinh-sua-thong-tin-sinh-vien');
        Route::put('/{student}', [StudentController::class, 'update'])->name('update')->middleware('permission:chinh-sua-thong-tin-sinh-vien');
        Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy')->middleware('permission:xoa-sinh-vien');
    });
    
    // Teacher management routes
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('index')->middleware('permission:view-teachers');
        Route::get('/create', [TeacherController::class, 'create'])->name('create')->middleware('permission:create-teachers');
        Route::post('/', [TeacherController::class, 'store'])->name('store')->middleware('permission:create-teachers');
        Route::get('/{teacher}', [TeacherController::class, 'show'])->name('show')->middleware('permission:view-teachers');
        Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])->name('edit')->middleware('permission:edit-teachers');
        Route::put('/{teacher}', [TeacherController::class, 'update'])->name('update')->middleware('permission:edit-teachers');
        Route::delete('/{teacher}', [TeacherController::class, 'destroy'])->name('destroy')->middleware('permission:delete-teachers');
    });
    
    // Course management routes
    Route::get('courses', [CourseController::class, 'index'])->name('courses.index')->middleware('permission:xem-danh-sach-mon-hoc');
    Route::get('courses/create', [CourseController::class, 'create'])->name('courses.create')->middleware('permission:them-moi-mon-hoc');
    Route::post('courses', [CourseController::class, 'store'])->name('courses.store')->middleware('permission:them-moi-mon-hoc');
    Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show')->middleware('permission:xem-danh-sach-mon-hoc');
    Route::get('courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit')->middleware('permission:chinh-sua-mon-hoc');
    Route::put('courses/{course}', [CourseController::class, 'update'])->name('courses.update')->middleware('permission:chinh-sua-mon-hoc');
    Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy')->middleware('permission:xoa-mon-hoc');
    
    // Department management routes
    Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index')->middleware('permission:view-departments');
    Route::get('departments/create', [DepartmentController::class, 'create'])->name('departments.create')->middleware('permission:create-departments');
    Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store')->middleware('permission:create-departments');
    Route::get('departments/{department}', [DepartmentController::class, 'show'])->name('departments.show')->middleware('permission:view-departments');
    Route::get('departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit')->middleware('permission:edit-departments');
    Route::put('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update')->middleware('permission:edit-departments');
    Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('permission:delete-departments');
    
    // Class management routes
    Route::get('classes', [ClassController::class, 'index'])->name('classes.index')->middleware('permission:view-classes');
    Route::get('classes/create', [ClassController::class, 'create'])->name('classes.create')->middleware('permission:create-classes');
    Route::post('classes', [ClassController::class, 'store'])->name('classes.store')->middleware('permission:create-classes');
    Route::get('classes/{class}', [ClassController::class, 'show'])->name('classes.show')->middleware('permission:view-classes');
    Route::get('classes/{class}/edit', [ClassController::class, 'edit'])->name('classes.edit')->middleware('permission:edit-classes');
    Route::put('classes/{class}', [ClassController::class, 'update'])->name('classes.update')->middleware('permission:edit-classes');
    Route::delete('classes/{class}', [ClassController::class, 'destroy'])->name('classes.destroy')->middleware('permission:delete-classes');
    
    // Grade management routes
    Route::get('grades', [GradeController::class, 'index'])->name('grades.index')->middleware('permission:view-grades');
    Route::get('grades/create', [GradeController::class, 'create'])->name('grades.create')->middleware('permission:create-grades');
    Route::post('grades', [GradeController::class, 'store'])->name('grades.store')->middleware('permission:create-grades');
    Route::get('grades/{grade}', [GradeController::class, 'show'])->name('grades.show')->middleware('permission:view-grades');
    Route::get('grades/{grade}/edit', [GradeController::class, 'edit'])->name('grades.edit')->middleware('permission:edit-grades');
    Route::put('grades/{grade}', [GradeController::class, 'update'])->name('grades.update')->middleware('permission:edit-grades');
    Route::delete('grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy')->middleware('permission:delete-grades');
    
    // Attendance management routes
    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index')->middleware('permission:view-attendances');
    Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create')->middleware('permission:create-attendances');
    Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store')->middleware('permission:create-attendances');
    Route::get('attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show')->middleware('permission:view-attendances');
    Route::get('attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit')->middleware('permission:edit-attendances');
    Route::put('attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update')->middleware('permission:edit-attendances');
    Route::delete('attendances/{attendance}', [AttendanceController::class, 'destroy'])->name('attendances.destroy')->middleware('permission:delete-attendances');
    
    // User management routes (chỉ super_admin và admin)
    Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:view-users');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create-users');
    Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('permission:create-users');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:view-users');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit-users');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit-users');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete-users');
    
    // Role management routes (chỉ super_admin và admin)
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:manage-permissions');
    Route::put('roles/{user}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:manage-permissions');
    
>>>>>>> Stashed changes
});

// API Statistics route (không cần auth)
Route::get('api/statistics', [\App\Http\Controllers\Api\StatisticsController::class, 'index'])->name('api.statistics');
