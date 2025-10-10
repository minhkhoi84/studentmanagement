<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $departments = Department::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('search') . '%')
                      ->orWhere('code', 'like', '%' . $request->string('search') . '%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status'));
            })
            ->withCount(['students', 'teachers', 'courses'])
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($departments);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code',
            'description' => 'nullable|string|max:1000',
            'head_of_department' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
        ]);

        $department = Department::create($validated);

        return response()->json($department, 201);
    }

    public function show(Department $department): JsonResponse
    {
        $department->load(['students', 'teachers', 'courses']);
        return response()->json($department);
    }

    public function update(Request $request, Department $department): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code,' . $department->id,
            'description' => 'nullable|string|max:1000',
            'head_of_department' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
        ]);

        $department->update($validated);

        return response()->json($department);
    }

    public function destroy(Department $department): JsonResponse
    {
        // Kiểm tra xem có sinh viên, giảng viên hoặc môn học nào thuộc khoa này không
        if ($department->students()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete department with existing students'
            ], 422);
        }

        if ($department->teachers()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete department with existing teachers'
            ], 422);
        }

        if ($department->courses()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete department with existing courses'
            ], 422);
        }

        $department->delete();
        return response()->json(['message' => 'Department deleted successfully']);
    }
}