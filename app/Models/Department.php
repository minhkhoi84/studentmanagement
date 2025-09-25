<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'dean', 'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('code', 'like', "%{$term}%")
              ->orWhere('dean', 'like', "%{$term}%");
        });
    }

    // ========================================
    // RELATIONSHIPS - Các mối quan hệ dữ liệu
    // ========================================

    /**
     * Một khoa có nhiều lớp học (classes)
     * Relationship: One-to-Many
     */
    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    /**
     * Một khoa có nhiều giáo viên (teachers)
     * Relationship: One-to-Many
     * Note: Sử dụng cột 'department' (tên khoa) để liên kết
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'department', 'name');
    }

    /**
     * Lấy danh sách sinh viên thuộc khoa này
     * Relationship: Has Many Through (thông qua classes)
     * Note: Cần có relationship giữa Student và Class
     */
    public function students()
    {
        return $this->hasManyThrough(
            Student::class,
            ClassModel::class,
            'department_id', // Foreign key on classes table
            'class', // Foreign key on students table (tên lớp)
            'id', // Local key on departments table
            'name' // Local key on classes table
        );
    }

    /**
     * Lấy tất cả môn học thuộc khoa này
     * Relationship: Has Many Through (thông qua teachers và courses)
     * Note: Đã có teacher_id trong bảng courses
     */
    public function courses()
    {
        return $this->hasManyThrough(
            Course::class,
            Teacher::class,
            'department', // Foreign key on teachers table (tên khoa)
            'teacher_id', // Foreign key on courses table
            'name', // Local key on departments table
            'id' // Local key on teachers table
        );
    }

    /**
     * Lấy tất cả điểm số của sinh viên thuộc khoa này
     * Relationship: Has Many Through (thông qua students và grades)
     */
    public function grades()
    {
        return $this->hasManyThrough(
            Grade::class,
            Student::class,
            'class', // Foreign key on students table (tên lớp)
            'student_id', // Foreign key on grades table
            'name', // Local key on departments table (thông qua classes)
            'id' // Local key on students table
        );
    }

    /**
     * Lấy tất cả điểm danh của sinh viên thuộc khoa này
     * Relationship: Has Many Through (thông qua students và attendances)
     */
    public function attendances()
    {
        return $this->hasManyThrough(
            Attendance::class,
            Student::class,
            'class', // Foreign key on students table (tên lớp)
            'student_id', // Foreign key on attendances table
            'name', // Local key on departments table (thông qua classes)
            'id' // Local key on students table
        );
    }

    /**
     * Scope để lấy thống kê của khoa
     */
    public function scopeWithStats($query)
    {
        return $query->withCount(['classes', 'teachers', 'students']);
    }

    /**
     * Scope để lấy khoa có nhiều sinh viên nhất
     */
    public function scopeMostStudents($query)
    {
        return $query->withCount('students')->orderBy('students_count', 'desc');
    }
}
