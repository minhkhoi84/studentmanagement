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
            ['name' => 'xem-danh-sach-khoa', 'display_name' => 'Xem danh sách khoa', 'group' => 'quan-ly-khoa'],
            ['name' => 'them-moi-khoa', 'display_name' => 'Thêm mới khoa', 'group' => 'quan-ly-khoa'],
            ['name' => 'chinh-sua-khoa', 'display_name' => 'Chỉnh sửa khoa', 'group' => 'quan-ly-khoa'],
            ['name' => 'xoa-khoa', 'display_name' => 'Xóa khoa', 'group' => 'quan-ly-khoa'],
            
            // Quản lý lớp
            ['name' => 'xem-danh-sach-lop', 'display_name' => 'Xem danh sách lớp', 'group' => 'quan-ly-lop'],
            ['name' => 'them-moi-lop', 'display_name' => 'Thêm mới lớp', 'group' => 'quan-ly-lop'],
            ['name' => 'chinh-sua-lop', 'display_name' => 'Chỉnh sửa lớp', 'group' => 'quan-ly-lop'],
            ['name' => 'xoa-lop', 'display_name' => 'Xóa lớp', 'group' => 'quan-ly-lop'],
            
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
            
            // Quản lý điểm
            ['name' => 'view-grades', 'display_name' => 'Xem danh sách điểm', 'group' => 'quan-ly-diem'],
            ['name' => 'create-grades', 'display_name' => 'Thêm mới điểm', 'group' => 'quan-ly-diem'],
            ['name' => 'edit-grades', 'display_name' => 'Chỉnh sửa điểm', 'group' => 'quan-ly-diem'],
            ['name' => 'delete-grades', 'display_name' => 'Xóa điểm', 'group' => 'quan-ly-diem'],
            
            // Quản lý điểm danh
            ['name' => 'view-attendances', 'display_name' => 'Xem danh sách điểm danh', 'group' => 'quan-ly-diem-danh'],
            ['name' => 'create-attendances', 'display_name' => 'Thêm mới điểm danh', 'group' => 'quan-ly-diem-danh'],
            ['name' => 'edit-attendances', 'display_name' => 'Chỉnh sửa điểm danh', 'group' => 'quan-ly-diem-danh'],
            ['name' => 'delete-attendances', 'display_name' => 'Xóa điểm danh', 'group' => 'quan-ly-diem-danh'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
