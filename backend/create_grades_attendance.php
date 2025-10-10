<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TẠO ĐIỂM SỐ VÀ ĐIỂM DANH ===\n";

// Lấy sinh viên và môn học
$students = \App\Models\Student::all();
$courses = \App\Models\Course::all();

if ($students->count() == 0) {
    echo "❌ Không có sinh viên nào!\n";
    exit;
}

if ($courses->count() == 0) {
    echo "❌ Không có môn học nào!\n";
    exit;
}

$course = $courses->first();
echo "✅ Sử dụng môn học: {$course->name}\n";

// Tạo điểm số cho sinh viên
foreach ($students as $student) {
    $grade = \App\Models\Grade::firstOrCreate([
        'student_id' => $student->id,
        'course_id' => $course->id
    ], [
        'midterm_score' => rand(3, 10),
        'final_score' => rand(3, 10),
        'total_score' => rand(3, 10),
        'status' => 'passed'
    ]);
    echo "✅ Grade for {$student->name}: {$grade->total_score}\n";
}

// Tạo điểm danh cho sinh viên
foreach ($students as $student) {
    $absentCount = rand(0, 8); // Số buổi nghỉ ngẫu nhiên
    
    for ($i = 0; $i < 10; $i++) { // Tạo 10 buổi học
        $attendance = \App\Models\Attendance::firstOrCreate([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'attendance_date' => now()->subDays($i)->format('Y-m-d')
        ], [
            'status' => $i < $absentCount ? 'absent' : 'present'
        ]);
    }
    echo "✅ Attendance for {$student->name}: {$absentCount} buổi nghỉ\n";
}

echo "\n=== HOÀN THÀNH ===\n";
echo "Đã tạo dữ liệu điểm số và điểm danh!\n";
