<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::query();
        if ($request->filled('q')) {
            $query->search($request->string('q'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        $courses = $query->orderBy('name')->paginate(10)->withQueryString();
        return view('courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses,code',
            'description' => 'nullable|string|max:2000',
            'credits' => 'required|integer|min:1|max:10',
            'status' => 'nullable|in:active,inactive',
        ]);
        Course::create($validated);
        return redirect()->route('courses.index')->with('success', 'Course created successfully');
    }

    public function edit(Course $course): View
    {
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses,code,' . $course->id,
            'description' => 'nullable|string|max:2000',
            'credits' => 'required|integer|min:1|max:10',
            'status' => 'nullable|in:active,inactive',
        ]);
        $course->update($validated);
        return redirect()->route('courses.index')->with('success', 'Course updated successfully');
    }

    public function show(Course $course): View
    {
        return view('courses.show', compact('course'));
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully');
    }
}

