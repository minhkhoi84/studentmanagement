<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Grade::with(['student', 'course']);
        
        if ($request->filled('student_id')) {
            $query->byStudent($request->integer('student_id'));
        }
        if ($request->filled('course_id')) {
            $query->byCourse($request->integer('course_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        
        $grades = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $students = Student::active()->orderBy('name')->get();
        $courses = Course::active()->orderBy('name')->get();
        
        return view('grades.index', compact('grades', 'students', 'courses'));
    }

    public function create(): View
    {
        $students = Student::active()->orderBy('name')->get();
        $courses = Course::active()->orderBy('name')->get();
        return view('grades.create', compact('students', 'courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'midterm_score' => 'nullable|numeric|min:0|max:10',
            'final_score' => 'nullable|numeric|min:0|max:10',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $grade = Grade::updateOrCreate(
            ['student_id' => $validated['student_id'], 'course_id' => $validated['course_id']],
            $validated
        );
        
        $grade->calculateTotalGrade();
        $grade->save();
        
        return redirect()->route('grades.index')->with('success', 'Điểm đã được lưu thành công');
    }

    public function edit(Grade $grade): View
    {
        $students = Student::active()->orderBy('name')->get();
        $courses = Course::active()->orderBy('name')->get();
        return view('grades.edit', compact('grade', 'students', 'courses'));
    }

    public function update(Request $request, Grade $grade): RedirectResponse
    {
        $validated = $request->validate([
            'midterm_score' => 'nullable|numeric|min:0|max:10',
            'final_score' => 'nullable|numeric|min:0|max:10',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $grade->update($validated);
        $grade->calculateTotalGrade();
        $grade->save();
        
        return redirect()->route('grades.index')->with('success', 'Điểm đã được cập nhật thành công');
    }

    public function show(Grade $grade): View
    {
        return view('grades.show', compact('grade'));
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Điểm đã được xóa thành công');
    }
}
