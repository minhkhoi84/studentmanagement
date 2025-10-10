<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AttendanceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $attendances = Attendance::query()
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
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status'));
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('attendance_date', '>=', $request->date('date_from'));
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('attendance_date', '<=', $request->date('date_to'));
            })
            ->with(['student', 'course', 'course.teacher'])
            ->orderBy('attendance_date', 'desc')
            ->paginate($request->integer('per_page', 15));

        return response()->json($attendances);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string|max:500',
        ]);

        // Kiểm tra xem đã có điểm danh cho ngày này chưa
        $existingAttendance = Attendance::where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'message' => 'Điểm danh cho ngày này đã tồn tại'
            ], 422);
        }

        $attendance = Attendance::create($validated);
        $attendance->load(['student', 'course']);

        return response()->json($attendance, 201);
    }

    public function show(Attendance $attendance): JsonResponse
    {
        $attendance->load(['student', 'course', 'course.teacher']);
        return response()->json($attendance);
    }

    public function update(Request $request, Attendance $attendance): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendance->update($validated);
        $attendance->load(['student', 'course', 'course.teacher']);

        return response()->json($attendance);
    }

    public function destroy(Attendance $attendance): JsonResponse
    {
        $attendance->delete();
        return response()->json(['message' => 'Attendance deleted successfully']);
    }

    public function bulkCreate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'attendance_date' => 'required|date',
            'attendances' => 'required|array|min:1',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.notes' => 'nullable|string|max:500',
        ]);

        $createdAttendances = [];
        $errors = [];

        foreach ($validated['attendances'] as $attendanceData) {
            try {
                // Kiểm tra xem đã có điểm danh cho ngày này chưa
                $existingAttendance = Attendance::where('student_id', $attendanceData['student_id'])
                    ->where('course_id', $validated['course_id'])
                    ->whereDate('attendance_date', $validated['attendance_date'])
                    ->first();

                if ($existingAttendance) {
                    $student = \App\Models\Student::find($attendanceData['student_id']);
                    $errors[] = "Điểm danh cho {$student->name} trong ngày này đã tồn tại";
                    continue;
                }

                $attendance = Attendance::create([
                    'student_id' => $attendanceData['student_id'],
                    'course_id' => $validated['course_id'],
                    'attendance_date' => $validated['attendance_date'],
                    'status' => $attendanceData['status'],
                    'notes' => $attendanceData['notes'] ?? null,
                ]);

                $attendance->load(['student', 'course']);
                $createdAttendances[] = $attendance;

            } catch (\Exception $e) {
                $errors[] = "Lỗi khi tạo điểm danh cho sinh viên {$attendanceData['student_id']}: " . $e->getMessage();
            }
        }

        return response()->json([
            'created_attendances' => $createdAttendances,
            'errors' => $errors,
            'message' => count($createdAttendances) . ' attendances created successfully'
        ], 201);
    }

    public function getAttendanceStats(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'nullable|exists:students,id',
            'course_id' => 'nullable|exists:courses,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $query = Attendance::query();

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('attendance_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('attendance_date', '<=', $request->date_to);
        }

        // Đếm theo từng trạng thái
        $total = $query->count();
        $present = (clone $query)->where('status', 'present')->count();
        $absent = (clone $query)->where('status', 'absent')->count();
        $late = (clone $query)->where('status', 'late')->count();
        $excused = (clone $query)->where('status', 'excused')->count();

        $attendanceRate = $total > 0 ? round((($present + $late) / $total) * 100, 2) : 0;

        return response()->json([
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'attendance_rate' => $attendanceRate,
        ]);
    }
}