<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Grade;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        try {
            return response()->json([
                'totalStudents' => \App\Models\Student::count(),
                'totalTeachers' => \App\Models\Teacher::count(),
                'totalCourses' => \App\Models\Course::count(),
                'totalDepartments' => \App\Models\Department::count(),
                'totalClasses' => \App\Models\ClassModel::count(),
                'totalGrades' => \App\Models\Grade::count(),
                'totalAttendances' => \App\Models\Attendance::count(),
                'totalUsers' => \App\Models\User::count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy dữ liệu thống kê',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getAttendanceStatistics()
    {
        // Lấy tất cả sinh viên và đếm số lần nghỉ của mỗi người
        $students = Student::withCount([
            'attendances as absent_count' => function ($query) {
                $query->where('status', 'absent');
            }
        ])->get();

        $stats = [
            'absent_more_than_5' => 0,
            'absent_4' => 0,
            'absent_3' => 0,
            'absent_2' => 0,
            'absent_1_or_less' => 0
        ];

        foreach ($students as $student) {
            $absentCount = $student->absent_count;
            
            if ($absentCount > 5) {
                $stats['absent_more_than_5']++;
            } elseif ($absentCount == 4) {
                $stats['absent_4']++;
            } elseif ($absentCount == 3) {
                $stats['absent_3']++;
            } elseif ($absentCount == 2) {
                $stats['absent_2']++;
            } else {
                $stats['absent_1_or_less']++;
            }
        }

        return $stats;
    }

    private function getGradeStatistics()
    {
        // Lấy tất cả điểm số có total_score
        $grades = Grade::whereNotNull('total_score')->get();

        $stats = [
            'below_5' => 0,
            'between_5_8' => 0,
            'above_8' => 0
        ];

        foreach ($grades as $grade) {
            $score = $grade->total_score;
            
            if ($score < 5) {
                $stats['below_5']++;
            } elseif ($score >= 5 && $score < 8) {
                $stats['between_5_8']++;
            } else {
                $stats['above_8']++;
            }
        }

        return $stats;
    }
}