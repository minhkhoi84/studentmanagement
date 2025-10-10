<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'teacher_code', 'phone', 'department', 'qualification', 'nationality', 'class_assigned', 'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ========================================
    // RELATIONSHIPS - Các mối quan hệ dữ liệu
    // ========================================

    /**
     * Một giáo viên có nhiều môn học (courses)
     * Relationship: One-to-Many
     * Note: Đã có teacher_id trong bảng courses
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Một giáo viên thuộc về một user account
     * Relationship: One-to-One (thông qua email)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    /**
     * Một giáo viên thuộc về một khoa (department)
     * Relationship: Many-to-One
     * Note: Sử dụng cột 'department' (tên khoa) để liên kết với bảng departments
     */
    public function departmentModel()
    {
        return $this->belongsTo(Department::class, 'department', 'name');
    }

    /**
     * Lấy danh sách sinh viên mà giáo viên đang dạy
     * Relationship: Many-to-Many (thông qua bảng courses và grades)
     * Note: Đã có teacher_id trong bảng courses
     */
    public function students()
    {
        return $this->hasManyThrough(
            Student::class,
            Course::class,
            'teacher_id', // Foreign key on courses table
            'id', // Foreign key on students table
            'id', // Local key on teachers table
            'id' // Local key on courses table
        );
    }

    /**
     * Lấy tất cả điểm số của các môn học mà giáo viên dạy
     * Relationship: Has Many Through (thông qua courses)
     * Note: Đã có teacher_id trong bảng courses
     */
    public function grades()
    {
        return $this->hasManyThrough(
            Grade::class,
            Course::class,
            'teacher_id', // Foreign key on courses table
            'course_id', // Foreign key on grades table
            'id', // Local key on teachers table
            'id' // Local key on courses table
        );
    }

    /**
     * Lấy tất cả điểm danh của các môn học mà giáo viên dạy
     * Relationship: Has Many Through (thông qua courses)
     * Note: Đã có teacher_id trong bảng courses
     */
    public function attendances()
    {
        return $this->hasManyThrough(
            Attendance::class,
            Course::class,
            'teacher_id', // Foreign key on courses table
            'course_id', // Foreign key on attendances table
            'id', // Local key on teachers table
            'id' // Local key on courses table
        );
    }

    /**
     * Scope để tìm kiếm giáo viên theo tên, email, hoặc mã giáo viên
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('teacher_code', 'like', "%{$term}%")
              ->orWhere('department', 'like', "%{$term}%");
        });
    }
}


