<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission;

class DefaultPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy tất cả permissions
        $allPermissions = Permission::all();
        
        // Super admin có tất cả quyền
        $superAdmin = User::where('role', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync($allPermissions->pluck('id'));
            $this->command->info('Super admin permissions updated: ' . $superAdmin->permissions()->count());
        }
        
        // Admin có quyền cơ bản
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $adminPermissions = Permission::whereIn('name', [
                'truy-cap-he-thong',
                'xem-danh-sach-sinh-vien',
                'xem-danh-sach-khoa',
                'xem-danh-sach-lop',
                'xem-danh-sach-mon-hoc',
                'xem-danh-sach-thanh-vien',
                'them-moi-khoa',
                'chinh-sua-khoa',
                'xoa-khoa',
                'them-moi-lop',
                'chinh-sua-lop',
                'xoa-lop',
                'them-moi-sinh-vien',
                'chinh-sua-thong-tin-sinh-vien',
                'xoa-sinh-vien',
                'them-moi-mon-hoc',
                'chinh-sua-mon-hoc',
                'xoa-mon-hoc'
            ])->get();
            
            $admin->permissions()->sync($adminPermissions->pluck('id'));
            $this->command->info('Admin permissions updated: ' . $admin->permissions()->count());
        }
        
        // User thường chỉ có quyền xem
        $users = User::where('role', 'user')->get();
        foreach ($users as $user) {
            $userPermissions = Permission::whereIn('name', [
                'truy-cap-he-thong',
                'xem-danh-sach-sinh-vien',
                'xem-danh-sach-khoa',
                'xem-danh-sach-lop',
                'xem-danh-sach-mon-hoc',
                'view-teachers',
                'view-grades',
                'view-attendances'
            ])->get();
            
            $user->permissions()->sync($userPermissions->pluck('id'));
            $this->command->info('User permissions updated for: ' . $user->name . ' - ' . $user->permissions()->count() . ' permissions');
        }
    }
}
