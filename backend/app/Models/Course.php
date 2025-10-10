<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'credits', 'teacher_id', 'status'
    ];

    protected $casts = [
        'credits' => 'integer',
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
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    // ========================================
    // RELATIONSHIPS - Các mối quan hệ dữ liệu
    // ========================================

    /**
     * Một môn học có nhiều điểm số (grades)
     * Relationship: One-to-Many
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Một môn học có nhiều bản ghi điểm danh (attendances)
     * Relationship: One-to-Many
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Một môn học thuộc về một giáo viên
     * Relationship: Many-to-One
     * Note: Đã có teacher_id trong bảng courses
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Lấy danh sách sinh viên đã đăng ký môn học này
     * Relationship: Many-to-Many (thông qua bảng grades)
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'grades')
                    ->withPivot(['midterm_score', 'final_score', 'total_score', 'grade', 'status', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Lấy danh sách sinh viên đã điểm danh môn học này
     * Relationship: Many-to-Many (thông qua bảng attendances)
     */
    public function enrolledStudents()
    {
        return $this->belongsToMany(Student::class, 'attendances')
                    ->withPivot(['attendance_date', 'status', 'notes'])
                    ->withTimestamps();
    }
}

