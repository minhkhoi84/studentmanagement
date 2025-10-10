<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Bước 1: Chuyển tất cả users có role 'admin' thành 'user'
        \DB::table('users')->where('role', 'admin')->update(['role' => 'user']);
        
        // Bước 2: Sửa enum column để chỉ còn super_admin và user
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'user') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục lại enum với admin role
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'user') NOT NULL DEFAULT 'user'");
    }
};
