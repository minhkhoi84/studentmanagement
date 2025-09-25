<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'course_id', 'attendance_date', 'status', 'notes'
    ];

    protected $casts = [
        'attendance_date' => 'date',
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

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // ========================================
    // RELATIONSHIPS - Các mối quan hệ dữ liệu
    // ========================================

    /**
     * Một điểm danh thuộc về một sinh viên
     * Relationship: Many-to-One
     * Note: Đã có sẵn, chỉ thêm comment
     */
    // public function student() - Đã có sẵn

    /**
     * Một điểm danh thuộc về một môn học
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
            'course_id', // Local key on attendances table
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
            'course_id', // Local key on attendances table
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
            'student_id', // Local key on attendances table
            'class' // Local key on students table
        );
    }

    // ========================================
    // SCOPE METHODS - Các phương thức truy vấn
    // ========================================

    /**
     * Scope để lọc điểm danh có mặt
     */
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    /**
     * Scope để lọc điểm danh vắng mặt
     */
    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    /**
     * Scope để lọc điểm danh muộn
     */
    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    /**
     * Scope để lọc điểm danh có phép
     */
    public function scopeExcused($query)
    {
        return $query->where('status', 'excused');
    }

    /**
     * Scope để lọc điểm danh theo khoảng thời gian
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }

    /**
     * Scope để lọc điểm danh theo tháng
     */
    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('attendance_date', $year)
                    ->whereMonth('attendance_date', $month);
    }

    /**
     * Scope để lọc điểm danh theo năm
     */
    public function scopeByYear($query, $year)
    {
        return $query->whereYear('attendance_date', $year);
    }

    /**
     * Scope để lọc điểm danh theo tuần
     */
    public function scopeByWeek($query, $year, $week)
    {
        return $query->whereRaw('YEARWEEK(attendance_date, 1) = ?', [$year . sprintf('%02d', $week)]);
    }

    // ========================================
    // ACCESSOR METHODS - Các phương thức truy cập
    // ========================================

    /**
     * Lấy màu sắc cho trạng thái điểm danh
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'present' => 'success', // Xanh lá
            'late' => 'warning',    // Vàng
            'excused' => 'info',    // Xanh dương
            'absent' => 'danger',   // Đỏ
            default => 'secondary'
        };
    }

    /**
     * Lấy text hiển thị cho trạng thái điểm danh
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'present' => 'Có mặt',
            'late' => 'Muộn',
            'excused' => 'Có phép',
            'absent' => 'Vắng mặt',
            default => 'Không xác định'
        };
    }

    /**
     * Lấy icon cho trạng thái điểm danh
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'present' => '✓', // Checkmark
            'late' => '⏰',   // Clock
            'excused' => '📝', // Note
            'absent' => '✗',  // X mark
            default => '?'
        };
    }

    /**
     * Kiểm tra xem có mặt không
     */
    public function getIsPresentAttribute()
    {
        return $this->status === 'present';
    }

    /**
     * Kiểm tra xem có vắng mặt không
     */
    public function getIsAbsentAttribute()
    {
        return $this->status === 'absent';
    }

    /**
     * Kiểm tra xem có muộn không
     */
    public function getIsLateAttribute()
    {
        return $this->status === 'late';
    }

    /**
     * Kiểm tra xem có phép không
     */
    public function getIsExcusedAttribute()
    {
        return $this->status === 'excused';
    }

    /**
     * Lấy ngày tháng định dạng
     */
    public function getFormattedDateAttribute()
    {
        return $this->attendance_date ? $this->attendance_date->format('d/m/Y') : 'N/A';
    }

    /**
     * Lấy thứ trong tuần
     */
    public function getDayOfWeekAttribute()
    {
        return $this->attendance_date ? $this->attendance_date->format('l') : 'N/A';
    }

    /**
     * Lấy thứ trong tuần (tiếng Việt)
     */
    public function getDayOfWeekVietnameseAttribute()
    {
        if (!$this->attendance_date) return 'N/A';
        
        $days = [
            'Monday' => 'Thứ Hai',
            'Tuesday' => 'Thứ Ba',
            'Wednesday' => 'Thứ Tư',
            'Thursday' => 'Thứ Năm',
            'Friday' => 'Thứ Sáu',
            'Saturday' => 'Thứ Bảy',
            'Sunday' => 'Chủ Nhật'
        ];
        
        return $days[$this->attendance_date->format('l')] ?? 'N/A';
    }
}
