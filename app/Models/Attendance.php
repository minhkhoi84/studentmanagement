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
}
