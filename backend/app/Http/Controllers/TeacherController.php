<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View
    {
        $teachers = Teacher::query()
            ->when($request->filled('q'), fn($query) => $query->search($request->string('q')))
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->string('status')))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('teachers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_code' => 'required|string|max:50|unique:teachers,teacher_code',
            'email' => 'required|email:rfc,dns|max:255|unique:teachers,email',
            'phone' => ['nullable','string','max:20','regex:/^[0-9+\-\s()]{8,20}$/'],
            'qualification' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:150',
            'class_assigned' => 'nullable|string|max:50',
            'status' => 'nullable|in:active,inactive',
        ]);
        Teacher::create($validated);
        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully');
    }

    public function edit(Teacher $teacher): View
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_code' => 'required|string|max:50|unique:teachers,teacher_code,' . $teacher->id,
            'email' => 'required|email:rfc,dns|max:255|unique:teachers,email,' . $teacher->id,
            'phone' => ['nullable','string','max:20','regex:/^[0-9+\-\s()]{8,20}$/'],
            'qualification' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:150',
            'class_assigned' => 'nullable|string|max:50',
            'status' => 'nullable|in:active,inactive',
        ]);
        $teacher->update($validated);
        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully');
    }

    public function show(Teacher $teacher): View
    {
        return view('teachers.show', compact('teacher'));
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully');
    }
}



