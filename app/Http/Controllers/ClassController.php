<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function index(Request $request): View
    {
        $query = ClassModel::with('department');
        if ($request->filled('q')) {
            $query->search($request->string('q'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }
        
        $classes = $query->orderBy('name')->paginate(10)->withQueryString();
        $departments = Department::active()->orderBy('name')->get();
        
        return view('classes.index', compact('classes', 'departments'));
    }

    public function create(): View
    {
        $departments = Department::active()->orderBy('name')->get();
        return view('classes.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:classes,code',
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'nullable|in:active,inactive',
        ]);
        ClassModel::create($validated);
        return redirect()->route('classes.index')->with('success', 'Lớp đã được tạo thành công');
    }

    public function edit(ClassModel $class): View
    {
        $departments = Department::active()->orderBy('name')->get();
        return view('classes.edit', compact('class', 'departments'));
    }

    public function update(Request $request, ClassModel $class): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:classes,code,' . $class->id,
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'nullable|in:active,inactive',
        ]);
        $class->update($validated);
        return redirect()->route('classes.index')->with('success', 'Lớp đã được cập nhật thành công');
    }

    public function show(ClassModel $class): View
    {
        return view('classes.show', compact('class'));
    }

    public function destroy(ClassModel $class): RedirectResponse
    {
        $class->delete();
        return redirect()->route('classes.index')->with('success', 'Lớp đã được xóa thành công');
    }
}
