<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Hệ thống
            ['name' => 'truy-cap-he-thong', 'display_name' => 'Truy cập hệ thống', 'group' => 'he-thong'],
            ['name' => 'quan-tri-all', 'display_name' => 'Quản trị tất cả', 'group' => 'he-thong'],
            
            // Quản lý sinh viên
            ['name' => 'xem-danh-sach-sinh-vien', 'display_name' => 'Xem danh sách sinh viên', 'group' => 'quan-ly-sinh-vien'],
            ['name' => 'them-moi-sinh-vien', 'display_name' => 'Thêm mới sinh viên', 'group' => 'quan-ly-sinh-vien'],
            ['name' => 'chinh-sua-thong-tin-sinh-vien', 'display_name' => 'Chỉnh sửa thông tin sinh viên', 'group' => 'quan-ly-sinh-vien'],
            ['name' => 'chi-tiet-thong-tin-sinh-vien', 'display_name' => 'Chi tiết thông tin sinh viên', 'group' => 'quan-ly-sinh-vien'],
            ['name' => 'xoa-sinh-vien', 'display_name' => 'Xóa sinh viên', 'group' => 'quan-ly-sinh-vien'],
            ['name' => 'import-excel-danh-sach-sinh-vien', 'display_name' => 'Import Excel danh sách sinh viên', 'group' => 'quan-ly-sinh-vien'],
            ['name' => 'export-excel-danh-sach-sinh-vien', 'display_name' => 'Export Excel danh sách sinh viên', 'group' => 'quan-ly-sinh-vien'],
            
            // Quản lý khoa
            ['name' => 'quan-ly-danh-sach-khoa', 'display_name' => 'Quản lý danh sách khoa', 'group' => 'quan-ly-khoa'],
            ['name' => 'them-moi-thong-tin-khoa', 'display_name' => 'Thêm mới thông tin khoa', 'group' => 'quan-ly-khoa'],
            ['name' => 'chinh-sua-thong-tin-khoa', 'display_name' => 'Chỉnh sửa thông tin khoa', 'group' => 'quan-ly-khoa'],
            ['name' => 'xoa-thong-tin-khoa', 'display_name' => 'Xóa thông tin khoa', 'group' => 'quan-ly-khoa'],
            
            // Quản lý lớp
            ['name' => 'quan-ly-danh-sach-lop', 'display_name' => 'Quản lý danh sách lớp', 'group' => 'quan-ly-lop'],
            ['name' => 'them-thong-tin-lop', 'display_name' => 'Thêm thông tin lớp', 'group' => 'quan-ly-lop'],
            ['name' => 'chinh-sua-thong-tin-lop', 'display_name' => 'Chỉnh sửa thông tin lớp', 'group' => 'quan-ly-lop'],
            ['name' => 'xoa-thong-tin-lop', 'display_name' => 'Xóa thông tin lớp', 'group' => 'quan-ly-lop'],
            
            // Quản lý thành viên
            ['name' => 'quan-ly-danh-sach-thanh-vien', 'display_name' => 'Quản lý danh sách thành viên', 'group' => 'quan-ly-thanh-vien'],
            ['name' => 'them-thanh-vien', 'display_name' => 'Thêm thành viên', 'group' => 'quan-ly-thanh-vien'],
            ['name' => 'chinh-sua-thanh-vien', 'display_name' => 'Chỉnh sửa thành viên', 'group' => 'quan-ly-thanh-vien'],
            ['name' => 'xoa-thanh-vien', 'display_name' => 'Xóa thành viên', 'group' => 'quan-ly-thanh-vien'],
            
            // Quản lý vai trò
            ['name' => 'xem-danh-sach-vai-tro', 'display_name' => 'Xem danh sách vai trò', 'group' => 'quan-ly-vai-tro'],
            ['name' => 'them-moi-vai-tro', 'display_name' => 'Thêm mới vai trò', 'group' => 'quan-ly-vai-tro'],
            ['name' => 'chinh-sua-vai-tro', 'display_name' => 'Chỉnh sửa vai trò', 'group' => 'quan-ly-vai-tro'],
            ['name' => 'xoa-vai-tro', 'display_name' => 'Xóa vai trò', 'group' => 'quan-ly-vai-tro'],
            
            // Quản lý môn học
            ['name' => 'xem-danh-sach-mon-hoc', 'display_name' => 'Xem danh sách môn học', 'group' => 'quan-ly-mon-hoc'],
            ['name' => 'them-moi-mon-hoc', 'display_name' => 'Thêm mới môn học', 'group' => 'quan-ly-mon-hoc'],
            ['name' => 'chinh-sua-mon-hoc', 'display_name' => 'Chỉnh sửa môn học', 'group' => 'quan-ly-mon-hoc'],
            ['name' => 'xoa-mon-hoc', 'display_name' => 'Xóa môn học', 'group' => 'quan-ly-mon-hoc'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
