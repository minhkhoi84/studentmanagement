<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Attendance::with(['student', 'course']);
        
        if ($request->filled('student_id')) {
            $query->byStudent($request->integer('student_id'));
        }
        if ($request->filled('course_id')) {
            $query->byCourse($request->integer('course_id'));
        }
        if ($request->filled('status')) {
            $query->byStatus($request->string('status'));
        }
        if ($request->filled('date')) {
            $query->byDate($request->date('date'));
        }
        
        $attendances = $query->orderByDesc('attendance_date')->paginate(15)->withQueryString();
        $students = Student::active()->orderBy('name')->get();
        $courses = Course::active()->orderBy('name')->get();
        
        return view('attendances.index', compact('attendances', 'students', 'courses'));
    }

    public function create(): View
    {
        $students = Student::active()->orderBy('name')->get();
        $courses = Course::active()->orderBy('name')->get();
        return view('attendances.create', compact('students', 'courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        Attendance::updateOrCreate(
            [
                'student_id' => $validated['student_id'], 
                'course_id' => $validated['course_id'],
                'attendance_date' => $validated['attendance_date']
            ],
            $validated
        );
        
        return redirect()->route('attendances.index')->with('success', 'Điểm danh đã được lưu thành công');
    }

    public function edit(Attendance $attendance): View
    {
        $students = Student::active()->orderBy('name')->get();
        $courses = Course::active()->orderBy('name')->get();
        return view('attendances.edit', compact('attendance', 'students', 'courses'));
    }

    public function update(Request $request, Attendance $attendance): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $attendance->update($validated);
        
        return redirect()->route('attendances.index')->with('success', 'Điểm danh đã được cập nhật thành công');
    }

    public function show(Attendance $attendance): View
    {
        return view('attendances.show', compact('attendance'));
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();
        return redirect()->route('attendances.index')->with('success', 'Điểm danh đã được xóa thành công');
    }
}
