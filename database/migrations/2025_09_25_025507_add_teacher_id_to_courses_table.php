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
        Schema::table('courses', function (Blueprint $table) {
            // Thêm cột teacher_id để liên kết với bảng teachers
            $table->unsignedBigInteger('teacher_id')->nullable()->after('credits');
            
            // Thêm foreign key constraint
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
            
            // Thêm index để tối ưu performance
            $table->index('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Xóa foreign key constraint trước
            $table->dropForeign(['teacher_id']);
            
            // Xóa index
            $table->dropIndex(['teacher_id']);
            
            // Xóa cột teacher_id
            $table->dropColumn('teacher_id');
        });
    }
};
