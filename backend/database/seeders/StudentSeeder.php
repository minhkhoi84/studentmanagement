<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Carbon\Carbon;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'name' => 'Trần Tuấn Kiệt',
                'email' => 'kiet.tran@example.com',
                'student_code' => 'SV001',
                'class' => 'CNTT01',
                'date_of_birth' => Carbon::parse('2000-05-15'),
                'gender' => 'male',
                'mobile' => '0985841743',
                'address' => 'Sơn La',
                'nationality' => 'Việt Nam',
                'major' => 'Công nghệ thông tin',
                'status' => 'active',
                'father_name' => 'Trần Văn Nam',
                'father_phone' => '0987654321',
                'father_occupation' => 'Kỹ sư',
                'mother_name' => 'Nguyễn Thị Lan',
                'mother_phone' => '0987654322',
                'mother_occupation' => 'Giáo viên',
                'notes' => 'Sinh viên xuất sắc, có tiềm năng phát triển tốt trong lĩnh vực lập trình.',
            ],
            [
                'name' => 'Nguyễn Thị Mai',
                'email' => 'mai.nguyen@example.com',
                'student_code' => 'SV002',
                'class' => 'CNTT01',
                'date_of_birth' => Carbon::parse('2001-08-22'),
                'gender' => 'female',
                'mobile' => '0985841744',
                'address' => 'Hà Nội',
                'nationality' => 'Việt Nam',
                'major' => 'Công nghệ thông tin',
                'status' => 'active',
                'father_name' => 'Nguyễn Văn Hùng',
                'father_phone' => '0987654323',
                'father_occupation' => 'Bác sĩ',
                'mother_name' => 'Trần Thị Hoa',
                'mother_phone' => '0987654324',
                'mother_occupation' => 'Y tá',
            ],
            [
                'name' => 'Lê Minh Đức',
                'email' => 'duc.le@example.com',
                'student_code' => 'SV003',
                'class' => 'CNTT02',
                'date_of_birth' => Carbon::parse('1999-12-10'),
                'gender' => 'male',
                'mobile' => '0985841745',
                'address' => 'TP. Hồ Chí Minh',
                'nationality' => 'Việt Nam',
                'major' => 'Kỹ thuật phần mềm',
                'status' => 'active',
                'father_name' => 'Lê Văn Tài',
                'father_phone' => '0987654325',
                'father_occupation' => 'Doanh nhân',
                'mother_name' => 'Phạm Thị Nga',
                'mother_phone' => '0987654326',
                'mother_occupation' => 'Kế toán',
                'notes' => 'Có kinh nghiệm thực tế trong phát triển web.',
            ],
        ];

        foreach ($students as $studentData) {
            Student::updateOrCreate(
                ['student_code' => $studentData['student_code']],
                $studentData
            );
        }
    }
}








