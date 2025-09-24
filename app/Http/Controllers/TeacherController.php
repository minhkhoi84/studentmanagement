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
        $query = Teacher::query();
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        $teachers = $query->orderBy('name')->paginate(10)->withQueryString();
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
            'email' => 'required|email:rfc,dns|max:255|unique:teachers,email',
            'phone' => ['nullable','string','max:20','regex:/^[0-9+\-\s()]{8,20}$/'],
            'department' => 'nullable|string|max:150',
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
            'email' => 'required|email:rfc,dns|max:255|unique:teachers,email,' . $teacher->id,
            'phone' => ['nullable','string','max:20','regex:/^[0-9+\-\s()]{8,20}$/'],
            'department' => 'nullable|string|max:150',
            'status' => 'nullable|in:active,inactive',
        ]);
        $teacher->update($validated);
        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully');
    }
}



