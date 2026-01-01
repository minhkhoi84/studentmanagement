<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GradeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $grades = Grade::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->string('search') . '%')
                      ->orWhere('student_code', 'like', '%' . $request->string('search') . '%');
                })->orWhereHas('course', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->string('search') . '%')
                      ->orWhere('course_code', 'like', '%' . $request->string('search') . '%');
                });
            })
            ->when($request->filled('student_id'), function ($query) use ($request) {
                $query->where('student_id', $request->integer('student_id'));
            })
            ->when($request->filled('course_id'), function ($query) use ($request) {
                $query->where('course_id', $request->integer('course_id'));
            })
            ->when($request->filled('grade_type'), function ($query) use ($request) {
                $query->where('grade_type', $request->string('grade_type'));
            })
            ->when($request->filled('semester'), function ($query) use ($request) {
                $query->where('semester', $request->string('semester'));
            })
            ->with(['student', 'course', 'course.teacher'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->integer('per_page', 15));

        return response()->json($grades);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'midterm_score' => 'required|numeric|min:0|max:10',
            'final_score' => 'required|numeric|min:0|max:10',
            'total_score' => 'nullable|numeric|min:0|max:10',
            'semester' => 'required|string|max:20',
            'academic_year' => 'required|string|max:10',
            'notes' => 'nullable|string|max:500',
        ]);

        // Kiểm tra xem đã có điểm chưa
        $existingGrade = Grade::where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->where('semester', $validated['semester'])
            ->where('academic_year', $validated['academic_year'])
            ->first();

        if ($existingGrade) {
            return response()->json([
                'message' => 'Điểm cho sinh viên này trong môn học và học kỳ này đã tồn tại'
            ], 422);
        }

        // Tự động tính điểm tổng kết nếu chưa có
        if (!isset($validated['total_score'])) {
            $validated['total_score'] = ($validated['final_score'] * 0.6) + ($validated['midterm_score'] * 0.4);
        }

        // Tự động xếp loại
        $total = $validated['total_score'];
        if ($total >= 8.5) $validated['grade'] = 'A';
        elseif ($total >= 7.0) $validated['grade'] = 'B';
        elseif ($total >= 5.5) $validated['grade'] = 'C';
        elseif ($total >= 4.0) $validated['grade'] = 'D';
        else $validated['grade'] = 'F';

        // Tự động set status
        $validated['status'] = $total >= 4.0 ? 'passed' : 'failed';

        $grade = Grade::create($validated);
        $grade->load(['student', 'course', 'course.teacher']);

        // Tạo thông báo cho admin
        Notification::create([
            'type' => 'grade_created',
            'title' => 'Điểm mới được nhập',
            'message' => "Điểm cho sinh viên {$grade->student->name} môn {$grade->course->name} vừa được nhập ({$grade->grade})",
            'data' => [
                'grade_id' => $grade->id,
                'student_name' => $grade->student->name,
                'course_name' => $grade->course->name,
                'total_score' => $grade->total_score,
                'grade' => $grade->grade,
            ],
            'user_id' => null,
        ]);

        return response()->json($grade, 201);
    }

    public function show(Grade $grade): JsonResponse
    {
        $grade->load(['student', 'course', 'course.teacher']);
        return response()->json($grade);
    }

    public function update(Request $request, Grade $grade): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'midterm_score' => 'required|numeric|min:0|max:10',
            'final_score' => 'required|numeric|min:0|max:10',
            'total_score' => 'nullable|numeric|min:0|max:10',
            'semester' => 'required|string|max:20',
            'academic_year' => 'required|string|max:10',
            'notes' => 'nullable|string|max:500',
        ]);

        // Tự động tính điểm tổng kết nếu chưa có
        if (!isset($validated['total_score'])) {
            $validated['total_score'] = ($validated['final_score'] * 0.6) + ($validated['midterm_score'] * 0.4);
        }

        // Tự động xếp loại
        $total = $validated['total_score'];
        if ($total >= 8.5) $validated['grade'] = 'A';
        elseif ($total >= 7.0) $validated['grade'] = 'B';
        elseif ($total >= 5.5) $validated['grade'] = 'C';
        elseif ($total >= 4.0) $validated['grade'] = 'D';
        else $validated['grade'] = 'F';

        // Tự động set status
        $validated['status'] = $total >= 4.0 ? 'passed' : 'failed';

        $grade->update($validated);
        $grade->load(['student', 'course', 'course.teacher']);

        // Tạo thông báo cho admin
        Notification::create([
            'type' => 'grade_updated',
            'title' => 'Điểm được cập nhật',
            'message' => "Điểm của sinh viên {$grade->student->name} môn {$grade->course->name} đã được cập nhật ({$grade->grade})",
            'data' => [
                'grade_id' => $grade->id,
                'student_name' => $grade->student->name,
                'course_name' => $grade->course->name,
                'total_score' => $grade->total_score,
                'grade' => $grade->grade,
            ],
            'user_id' => null,
        ]);

        return response()->json($grade);
    }

    public function destroy(Grade $grade): JsonResponse
    {
        $grade->delete();
        return response()->json(['message' => 'Grade deleted successfully']);
    }

    public function calculateTotalGrade(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'semester' => 'required|string|max:20',
            'academic_year' => 'required|string|max:10',
        ]);

        $grades = Grade::where('student_id', $request->student_id)
            ->where('course_id', $request->course_id)
            ->where('semester', $request->semester)
            ->where('academic_year', $request->academic_year)
            ->get();

        if ($grades->isEmpty()) {
            return response()->json([
                'message' => 'No grades found for this student and course'
            ], 404);
        }

        $totalGrade = $grades->first()->calculateTotalGrade();

        return response()->json([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'semester' => $request->semester,
            'academic_year' => $request->academic_year,
            'total_grade' => $totalGrade,
            'grades' => $grades,
        ]);
    }
}