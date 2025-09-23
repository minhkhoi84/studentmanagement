<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Student extends Model
{
    use HasFactory;
    
    protected $table = 'students';
    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        // Basic Information
        'name',
        'email',
        'student_code',
        'class',
        'date_of_birth',
        'gender',
        'mobile',
        'phone',
        'address',
        'emergency_contact',
        
        // Academic Information
        'major',
        'semester',
        'gpa',
        'status',
        
        // Personal Information
        'nationality',
        'religion',
        'medical_conditions',
        
        // Family Information
        'father_name',
        'father_phone',
        'father_occupation',
        'mother_name',
        'mother_phone',
        'mother_occupation',
        
        // Academic History
        'enrollment_date',
        'previous_school',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
        'gpa' => 'decimal:2',
        'semester' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'medical_conditions', // Sensitive information
    ];

    /**
     * Get the student's full name with student code.
     */
    protected function fullNameWithCode(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name . ' (' . $this->student_code . ')',
        );
    }

    /**
     * Get the student's age from date of birth.
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->date_of_birth ? $this->date_of_birth->age : null,
        );
    }

    /**
     * Get the student's status badge color.
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'graduated' => 'primary',
            'suspended' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get the student's gender display.
     */
    public function getGenderDisplayAttribute()
    {
        return match($this->gender) {
            'male' => 'Nam',
            'female' => 'Nữ',
            'other' => 'Khác',
            default => 'Không xác định'
        };
    }

    /**
     * Scope a query to only include active students.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to search students by name, email, or student code.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('student_code', 'like', "%{$term}%")
              ->orWhere('mobile', 'like', "%{$term}%");
        });
    }

    /**
     * Scope a query to filter by class.
     */
    public function scopeByClass($query, $class)
    {
        return $query->where('class', $class);
    }

    /**
     * Scope a query to filter by major.
     */
    public function scopeByMajor($query, $major)
    {
        return $query->where('major', $major);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
