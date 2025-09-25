<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Department::query();
        if ($request->filled('q')) {
            $query->search($request->string('q'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        $departments = $query->orderBy('name')->paginate(10)->withQueryString();
        return view('departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code',
            'description' => 'nullable|string|max:2000',
            'dean' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);
        Department::create($validated);
        return redirect()->route('departments.index')->with('success', 'Khoa đã được tạo thành công');
    }

    public function edit(Department $department): View
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code,' . $department->id,
            'description' => 'nullable|string|max:2000',
            'dean' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);
        $department->update($validated);
        return redirect()->route('departments.index')->with('success', 'Khoa đã được cập nhật thành công');
    }

    public function show(Department $department): View
    {
        return view('departments.show', compact('department'));
    }

    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Khoa đã được xóa thành công');
    }
}
