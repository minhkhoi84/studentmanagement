<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'course_id', 'grade_type', 'score', 'max_score', 'semester', 'academic_year',
        'midterm_score', 'final_score', 'total_score', 'grade', 'status', 'notes'
    ];

    protected $casts = [
        'midterm_score' => 'decimal:2',
        'final_score' => 'decimal:2',
        'total_score' => 'decimal:2',
        'student_id' => 'integer',
        'course_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Tự động tính điểm tổng kết và xếp loại
    public function calculateTotalGrade()
    {
        if ($this->midterm_score !== null && $this->final_score !== null) {
            $this->total_score = ($this->midterm_score * 0.4) + ($this->final_score * 0.6);
            
            // Xếp loại
            if ($this->total_score >= 8.5) $this->grade = 'A';
            elseif ($this->total_score >= 7.0) $this->grade = 'B';
            elseif ($this->total_score >= 5.5) $this->grade = 'C';
            elseif ($this->total_score >= 4.0) $this->grade = 'D';
            else $this->grade = 'F';
            
            // Trạng thái
            $this->status = $this->total_score >= 4.0 ? 'passed' : 'failed';
        }
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    // ========================================
    // RELATIONSHIPS - Các mối quan hệ dữ liệu
    // ========================================

    /**
     * Một điểm số thuộc về một sinh viên
     * Relationship: Many-to-One
     * Note: Đã có sẵn, chỉ thêm comment
     */
    // public function student() - Đã có sẵn

    /**
     * Một điểm số thuộc về một môn học
     * Relationship: Many-to-One
     * Note: Đã có sẵn, chỉ thêm comment
     */
    // public function course() - Đã có sẵn

    /**
     * Lấy thông tin giáo viên dạy môn học này
     * Relationship: Has One Through (thông qua course)
     * Note: Đã có teacher_id trong bảng courses
     */
    public function teacher()
    {
        return $this->hasOneThrough(
            Teacher::class,
            Course::class,
            'id', // Foreign key on courses table
            'id', // Foreign key on teachers table
            'course_id', // Local key on grades table
            'teacher_id' // Local key on courses table
        );
    }

    /**
     * Lấy thông tin khoa của môn học này
     * Relationship: Has One Through (thông qua course và teacher)
     * Note: Đã có teacher_id trong bảng courses
     */
    public function department()
    {
        return $this->hasOneThrough(
            Department::class,
            Teacher::class,
            'id', // Foreign key on teachers table
            'name', // Foreign key on departments table
            'course_id', // Local key on grades table
            'department' // Local key on teachers table
        );
    }

    /**
     * Lấy thông tin lớp học của sinh viên
     * Relationship: Has One Through (thông qua student)
     */
    public function classModel()
    {
        return $this->hasOneThrough(
            ClassModel::class,
            Student::class,
            'id', // Foreign key on students table
            'name', // Foreign key on classes table
            'student_id', // Local key on grades table
            'class' // Local key on students table
        );
    }

    // ========================================
    // SCOPE METHODS - Các phương thức truy vấn
    // ========================================

    /**
     * Scope để lọc điểm theo xếp loại
     */
    public function scopeByGrade($query, $grade)
    {
        return $query->where('grade', $grade);
    }

    /**
     * Scope để lọc điểm theo trạng thái
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope để lọc điểm đậu
     */
    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    /**
     * Scope để lọc điểm rớt
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope để lọc điểm theo khoảng điểm
     */
    public function scopeByScoreRange($query, $min, $max)
    {
        return $query->whereBetween('total_score', [$min, $max]);
    }

    /**
     * Scope để lọc điểm cao (A, B)
     */
    public function scopeHighGrades($query)
    {
        return $query->whereIn('grade', ['A', 'B']);
    }

    /**
     * Scope để lọc điểm thấp (D, F)
     */
    public function scopeLowGrades($query)
    {
        return $query->whereIn('grade', ['D', 'F']);
    }

    // ========================================
    // ACCESSOR METHODS - Các phương thức truy cập
    // ========================================

    /**
     * Lấy màu sắc cho xếp loại điểm
     */
    public function getGradeColorAttribute()
    {
        return match($this->grade) {
            'A' => 'success', // Xanh lá
            'B' => 'info',    // Xanh dương
            'C' => 'warning', // Vàng
            'D' => 'orange',  // Cam
            'F' => 'danger',  // Đỏ
            default => 'secondary'
        };
    }

    /**
     * Lấy text hiển thị cho xếp loại
     */
    public function getGradeTextAttribute()
    {
        return match($this->grade) {
            'A' => 'Xuất sắc',
            'B' => 'Giỏi',
            'C' => 'Khá',
            'D' => 'Trung bình',
            'F' => 'Kém',
            default => 'Chưa xếp loại'
        };
    }

    /**
     * Lấy text hiển thị cho trạng thái
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'passed' => 'Đậu',
            'failed' => 'Rớt',
            'incomplete' => 'Chưa hoàn thành',
            default => 'Không xác định'
        };
    }

    /**
     * Kiểm tra xem điểm có đậu không
     */
    public function getIsPassedAttribute()
    {
        return $this->status === 'passed';
    }

    /**
     * Kiểm tra xem điểm có rớt không
     */
    public function getIsFailedAttribute()
    {
        return $this->status === 'failed';
    }
}
