<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TẠO DỮ LIỆU MẪU ===\n";

// Tạo khoa
$department = \App\Models\Department::firstOrCreate([
    'name' => 'Khoa Công nghệ thông tin',
    'code' => 'CNTT',
    'description' => 'Khoa Công nghệ thông tin',
    'status' => 'active'
]);

echo "✅ Department: {$department->name}\n";

// Tạo lớp
$class = \App\Models\ClassModel::firstOrCreate([
    'name' => 'Lớp CNTT01',
    'code' => 'CNTT01',
    'department_id' => $department->id,
    'status' => 'active'
]);

echo "✅ Class: {$class->name}\n";

// Tạo sinh viên
$students = [
    ['name' => 'Nguyễn Văn A', 'student_code' => 'SV001', 'email' => 'a@example.com'],
    ['name' => 'Trần Thị B', 'student_code' => 'SV002', 'email' => 'b@example.com'],
    ['name' => 'Lê Văn C', 'student_code' => 'SV003', 'email' => 'c@example.com'],
    ['name' => 'Phạm Thị D', 'student_code' => 'SV004', 'email' => 'd@example.com'],
    ['name' => 'Hoàng Văn E', 'student_code' => 'SV005', 'email' => 'e@example.com'],
];

foreach ($students as $studentData) {
    $student = \App\Models\Student::firstOrCreate([
        'email' => $studentData['email']
    ], [
        'name' => $studentData['name'],
        'student_code' => $studentData['student_code'],
        'class' => $class->name,
        'address' => 'Hà Nội',
        'mobile' => '0123456789',
        'status' => 'active'
    ]);
    echo "✅ Student: {$student->name}\n";
}

// Tạo giảng viên
$teacher = \App\Models\Teacher::firstOrCreate([
    'email' => 'teacher@example.com'
], [
    'name' => 'Thầy Nguyễn Văn Giảng',
    'teacher_code' => 'GV001',
    'department' => 'Công nghệ thông tin',
    'status' => 'active'
]);

echo "✅ Teacher: {$teacher->name}\n";

// Tạo môn học
$course = \App\Models\Course::firstOrCreate([
    'course_code' => 'CNTT001'
], [
    'name' => 'Lập trình Web',
    'description' => 'Môn học lập trình web',
    'credits' => 3,
    'teacher_id' => $teacher->id,
    'department_id' => $department->id,
    'status' => 'active'
]);

echo "✅ Course: {$course->name}\n";

// Tạo điểm số
$students = \App\Models\Student::all();
foreach ($students as $student) {
    $grade = \App\Models\Grade::firstOrCreate([
        'student_id' => $student->id,
        'course_id' => $course->id,
        'grade_type' => 'final',
        'semester' => 'HK1',
        'academic_year' => '2024-2025'
    ], [
        'score' => rand(3, 10),
        'max_score' => 10,
        'total_score' => rand(3, 10)
    ]);
    echo "✅ Grade for {$student->name}: {$grade->total_score}\n";
}

// Tạo điểm danh
foreach ($students as $student) {
    for ($i = 0; $i < rand(1, 8); $i++) {
        $attendance = \App\Models\Attendance::firstOrCreate([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'attendance_date' => now()->subDays($i)->format('Y-m-d')
        ], [
            'status' => rand(0, 1) ? 'present' : 'absent'
        ]);
    }
    echo "✅ Attendance for {$student->name}\n";
}

echo "\n=== HOÀN THÀNH ===\n";
echo "Đã tạo dữ liệu mẫu để test dashboard!\n";
