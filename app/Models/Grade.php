<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'course_id', 'midterm_score', 'final_score', 'total_score', 'grade', 'status', 'notes'
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
}
