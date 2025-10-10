<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClassController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $classes = ClassModel::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('search') . '%')
                      ->orWhere('class_code', 'like', '%' . $request->string('search') . '%');
            })
            ->when($request->filled('department_id'), function ($query) use ($request) {
                $query->where('department_id', $request->integer('department_id'));
            })
            ->when($request->filled('academic_year'), function ($query) use ($request) {
                $query->where('academic_year', $request->string('academic_year'));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status'));
            })
            ->with(['department'])
            ->withCount(['students'])
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($classes);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_code' => 'required|string|max:50|unique:classes,code',
            'department_id' => 'required|exists:departments,id',
            'status' => 'nullable|in:active,inactive',
        ]);

        // Map class_code to code for database
        $validated['code'] = $validated['class_code'];
        unset($validated['class_code']);

        $class = ClassModel::create($validated);
        $class->load(['department']);
        
        // Map code back to class_code for response
        $class->class_code = $class->code;

        return response()->json($class, 201);
    }

    public function show(ClassModel $class): JsonResponse
    {
        $class->load(['department', 'students', 'students.grades']);
        return response()->json($class);
    }

    public function update(Request $request, ClassModel $class): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_code' => 'required|string|max:50|unique:classes,code,' . $class->id,
            'department_id' => 'required|exists:departments,id',
            'status' => 'nullable|in:active,inactive',
        ]);

        // Map class_code to code for database
        $validated['code'] = $validated['class_code'];
        unset($validated['class_code']);

        $class->update($validated);
        $class->load(['department']);
        
        // Map code back to class_code for response
        $class->class_code = $class->code;

        return response()->json($class);
    }

    public function destroy(ClassModel $class): JsonResponse
    {
        // Kiểm tra xem có sinh viên nào trong lớp này không
        if ($class->students()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete class with existing students'
            ], 422);
        }

        $class->delete();
        return response()->json(['message' => 'Class deleted successfully']);
    }

    public function students(ClassModel $class): JsonResponse
    {
        $students = $class->students()
            ->with(['department', 'grades', 'attendances'])
            ->orderBy('name')
            ->get();
        
        return response()->json($students);
    }
}