<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use App\Models\Student;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Student::query();

        if ($request->filled('q')) {
            $query->search($request->string('q'));
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->string('gender'));
        }

        if ($request->filled('status')) {
            $query->byStatus($request->string('status'));
        }

        if ($request->filled('class')) {
            $query->byClass($request->string('class'));
        }

        if ($request->filled('major')) {
            $query->byMajor($request->string('major'));
        }

        $students = $query->orderByDesc('created_at')
                          ->paginate(10)
                          ->withQueryString();

        return view('students.index', [
            'students' => $students,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        Student::create($validated);

        return redirect()->route('students.index')
                        ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): View
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student): View
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate($this->validationRules(true, $student->id));

        $student->update($validated);

        return redirect()->route('students.index')
                        ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('students.index')
                        ->with('success', 'Student deleted successfully.');
    }

    /**
     * Build validation rules for create/update requests.
     */
    private function validationRules(bool $isUpdate = false, ?int $studentId = null): array
    {
        $uniqueEmail = 'unique:students,email';
        $uniqueCode = 'unique:students,student_code';
        if ($isUpdate && $studentId !== null) {
            $uniqueEmail .= ',' . $studentId;
            $uniqueCode .= ',' . $studentId;
        }

        return [
            // Basic Information
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email:rfc,dns', 'max:255', $uniqueEmail],
            'student_code' => ['nullable', 'string', 'max:50', $uniqueCode],
            'class' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'mobile' => ['required','string','max:20','regex:/^[0-9+\-\s()]{8,20}$/'],
            'phone' => ['nullable','string','max:20','regex:/^[0-9+\-\s()]{8,20}$/'],
            'address' => 'required|string|max:255',
            'emergency_contact' => 'nullable|string|max:1000',

            // Academic Information
            'major' => 'nullable|string|max:150',
            'semester' => 'nullable|integer|min:1|max:20',
            'gpa' => 'nullable|numeric|min:0|max:4',
            'status' => 'nullable|in:active,inactive,graduated,suspended',

            // Personal Information
            'nationality' => 'nullable|string|max:120',
            'religion' => 'nullable|string|max:120',
            'medical_conditions' => 'nullable|string|max:2000',

            // Family Information
            'father_name' => 'nullable|string|max:255',
            'father_phone' => ['nullable','string','max:20','regex:/^[0-9+\-\s()]{8,20}$/'],
            'father_occupation' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => ['nullable','string','max:20','regex:/^[0-9+\-\s()]{8,20}$/'],
            'mother_occupation' => 'nullable|string|max:255',

            // Academic History
            'enrollment_date' => 'nullable|date',
            'previous_school' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ];
    }
}
