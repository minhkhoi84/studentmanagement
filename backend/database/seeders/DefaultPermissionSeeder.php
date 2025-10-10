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
