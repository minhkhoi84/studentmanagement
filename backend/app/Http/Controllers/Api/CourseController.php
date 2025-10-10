<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $courses = Course::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('search') . '%')
                      ->orWhere('code', 'like', '%' . $request->string('search') . '%');
            })
            ->when($request->filled('teacher_id'), function ($query) use ($request) {
                $query->where('teacher_id', $request->integer('teacher_id'));
            })
            ->when($request->filled('department_id'), function ($query) use ($request) {
                $query->where('department_id', $request->integer('department_id'));
            })
            // ->with(['teacher'])
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($courses);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code',
            'description' => 'nullable|string|max:1000',
            'credits' => 'required|integer|min:1|max:10',
            'teacher_id' => 'nullable|exists:teachers,id',
            'semester' => 'nullable|string|max:20',
            'academic_year' => 'nullable|string|max:10',
            'status' => 'nullable|in:active,inactive',
        ]);

        $course = Course::create($validated);
        // $course->load(['teacher']);

        return response()->json($course, 201);
    }

    public function show(Course $course): JsonResponse
    {
        $course->load(['teacher', 'students', 'grades']);
        return response()->json($course);
    }

    public function update(Request $request, Course $course): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code,' . $course->id,
            'description' => 'nullable|string|max:1000',
            'credits' => 'required|integer|min:1|max:10',
            'teacher_id' => 'nullable|exists:teachers,id',
            'semester' => 'nullable|string|max:20',
            'academic_year' => 'nullable|string|max:10',
            'status' => 'nullable|in:active,inactive',
        ]);

        $course->update($validated);
        // $course->load(['teacher']);

        return response()->json($course);
    }

    public function destroy(Course $course): JsonResponse
    {
        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }

    public function students(Course $course): JsonResponse
    {
        $students = $course->students()->with(['class', 'department'])->get();
        return response()->json($students);
    }

    public function grades(Course $course): JsonResponse
    {
        $grades = $course->grades()->with(['student', 'student.class'])->get();
        return response()->json($grades);
    }
}