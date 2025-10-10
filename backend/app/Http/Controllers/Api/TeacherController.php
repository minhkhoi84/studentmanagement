<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teachers = Teacher::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('search') . '%')
                      ->orWhere('email', 'like', '%' . $request->string('search') . '%')
                      ->orWhere('teacher_code', 'like', '%' . $request->string('search') . '%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status'));
            })
            ->when($request->filled('department'), function ($query) use ($request) {
                $query->where('department', 'like', '%' . $request->string('department') . '%');
            })
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($teachers);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_code' => 'required|string|max:50|unique:teachers,teacher_code',
            'email' => 'required|email|max:255|unique:teachers,email',
            'phone' => 'nullable|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:150',
            'class_assigned' => 'nullable|string|max:50',
            'status' => 'nullable|in:active,inactive',
        ]);

        $teacher = Teacher::create($validated);

        return response()->json($teacher, 201);
    }

    public function show(Teacher $teacher): JsonResponse
    {
        $teacher->load(['courses', 'courses.students']);
        return response()->json($teacher);
    }

    public function update(Request $request, Teacher $teacher): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_code' => 'required|string|max:50|unique:teachers,teacher_code,' . $teacher->id,
            'email' => 'required|email|max:255|unique:teachers,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:150',
            'class_assigned' => 'nullable|string|max:50',
            'status' => 'nullable|in:active,inactive',
        ]);

        $teacher->update($validated);

        return response()->json($teacher);
    }

    public function destroy(Teacher $teacher): JsonResponse
    {
        $teacher->delete();
        return response()->json(['message' => 'Teacher deleted successfully']);
    }

    public function courses(Teacher $teacher): JsonResponse
    {
        $courses = $teacher->courses()->with(['students', 'grades'])->get();
        return response()->json($courses);
    }
}