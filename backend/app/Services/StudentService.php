<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StudentService
{
    /**
     * Get paginated students with filters.
     */
    public function getPaginatedStudents(Request $request): LengthAwarePaginator
    {
        return Student::query()
            ->when($request->filled('q'), fn($query) => $query->search($request->string('q')))
            ->when($request->filled('gender'), fn($query) => $query->where('gender', $request->string('gender')))
            ->when($request->filled('status'), fn($query) => $query->byStatus($request->string('status')))
            ->when($request->filled('class'), fn($query) => $query->byClass($request->string('class')))
            ->when($request->filled('major'), fn($query) => $query->byMajor($request->string('major')))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Create a new student.
     */
    public function createStudent(array $data): Student
    {
        return Student::create($data);
    }

    /**
     * Update student information.
     */
    public function updateStudent(Student $student, array $data): bool
    {
        return $student->update($data);
    }

    /**
     * Delete a student.
     */
    public function deleteStudent(Student $student): bool
    {
        return $student->delete();
    }

    /**
     * Get student statistics.
     */
    public function getStudentStatistics(): array
    {
        return [
            'total' => Student::count(),
            'active' => Student::where('status', Student::STATUS_ACTIVE)->count(),
            'inactive' => Student::where('status', Student::STATUS_INACTIVE)->count(),
            'graduated' => Student::where('status', Student::STATUS_GRADUATED)->count(),
            'suspended' => Student::where('status', Student::STATUS_SUSPENDED)->count(),
        ];
    }
}










