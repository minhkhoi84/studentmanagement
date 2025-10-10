<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Student::query();
        
        // Handle search
        $this->handleSearch($query, $request, ['name', 'email', 'student_code']);
        
        // Handle filters
        $query->when($request->filled('class_id'), function ($q) use ($request) {
            $q->where('class', $request->string('class_id'));
        })->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->string('status'));
        });
        
        // Load relationships and order
        $query->with(['classModel'])->orderBy('name');
        
        // Paginate
        $students = $this->handlePagination($query, $request);
        
        return $this->paginatedResponse($students);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_code' => 'required|string|max:50|unique:students,student_code',
            'email' => 'required|email|max:255|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'class' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,graduated',
        ]);

        $student = Student::create($validated);
        $student->load(['classModel']);

        return $this->successResponse($student, 'Student created successfully', 201);
    }

    public function show(Student $student): JsonResponse
    {
        $student->load(['classModel', 'grades', 'attendances']);
        return $this->successResponse($student);
    }

    public function update(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_code' => 'required|string|max:50|unique:students,student_code,' . $student->id,
            'email' => 'required|email|max:255|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'class' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,graduated',
        ]);

        $student->update($validated);
        $student->load(['classModel']);

        return $this->successResponse($student, 'Student updated successfully');
    }

    public function destroy(Student $student): JsonResponse
    {
        $student->delete();
        return $this->successResponse(null, 'Student deleted successfully');
    }

    public function grades(Student $student): JsonResponse
    {
        $grades = $student->grades()->with(['course', 'course.teacher'])->get();
        return $this->successResponse($grades);
    }

    public function attendances(Student $student): JsonResponse
    {
        $attendances = $student->attendances()->with(['course', 'course.teacher'])->get();
        return $this->successResponse($attendances);
    }
}