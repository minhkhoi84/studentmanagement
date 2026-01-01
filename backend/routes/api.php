<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ========================================
// AUTHENTICATION ROUTES
// ========================================
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// ========================================
// PUBLIC API ROUTES (for testing)
// ========================================

// Statistics
Route::get('/statistics', function () {
    return response()->json([
        'totalStudents' => \App\Models\Student::count(),
        'totalTeachers' => \App\Models\Teacher::count(),
        'totalCourses' => \App\Models\Course::count(),
        'totalDepartments' => \App\Models\Department::count(),
        'totalClasses' => \App\Models\ClassModel::count(),
        'totalGrades' => \App\Models\Grade::count(),
        'totalAttendances' => \App\Models\Attendance::count(),
        'totalUsers' => \App\Models\User::count(),
    ]);
});

// Permissions
Route::get('/permissions', function () {
    $permissions = \App\Models\Permission::orderBy('group')->orderBy('name')->get();
    return response()->json($permissions);
});

// ========================================
// RESOURCE ROUTES
// ========================================

// Students
Route::apiResource('students', StudentController::class);
Route::get('/students/{student}/grades', [StudentController::class, 'grades']);
Route::get('/students/{student}/attendances', [StudentController::class, 'attendances']);

// Teachers
Route::apiResource('teachers', TeacherController::class);
Route::get('/teachers/{teacher}/courses', [TeacherController::class, 'courses']);

// Courses
Route::apiResource('courses', CourseController::class);
Route::get('/courses/{course}/students', [CourseController::class, 'students']);
Route::get('/courses/{course}/grades', [CourseController::class, 'grades']);

// Grades
Route::apiResource('grades', GradeController::class);

// Departments
Route::apiResource('departments', DepartmentController::class);
Route::get('/departments/{department}/students', [DepartmentController::class, 'students']);
Route::get('/departments/{department}/teachers', [DepartmentController::class, 'teachers']);

// Classes
Route::apiResource('classes', ClassController::class);
Route::get('/classes/{class}/students', [ClassController::class, 'students']);

// Attendance
Route::apiResource('attendances', AttendanceController::class);
Route::post('/attendances/bulk', [AttendanceController::class, 'bulkCreate']);
Route::get('/attendance-stats', [AttendanceController::class, 'getAttendanceStats']);

// Users
Route::apiResource('users', UserController::class);
Route::put('/users/{user}/permissions', [UserController::class, 'updatePermissions']);

// ========================================
// PROTECTED ROUTES
// ========================================
Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/user', function (Illuminate\Http\Request $request) {
        return $request->user();
    });
    
    // User permissions
    Route::get('/user/permissions', function (Illuminate\Http\Request $request) {
        return $request->user()->permissions;
    });
    
    // Notifications (chỉ admin)
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
    });
});