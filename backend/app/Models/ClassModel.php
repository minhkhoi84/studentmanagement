<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';
    
    protected $fillable = [
        'name',
        'code',
        'department_id',
        'max_students',
        'status'
    ];

    protected $casts = [
        'max_students' => 'integer',
        'department_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class', 'name');
    }

    public function courses()
    {
        // Giả sử có relationship giữa class và courses thông qua students
        return $this->hasManyThrough(Course::class, Student::class, 'class', 'id', 'name', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('code', 'like', "%{$term}%")
              ->orWhere('teacher_id', 'like', "%{$term}%");
        });
    }
}
