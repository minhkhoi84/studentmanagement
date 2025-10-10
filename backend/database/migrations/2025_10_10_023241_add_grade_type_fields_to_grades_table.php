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
        Schema::table('grades', function (Blueprint $table) {
            // Kiểm tra và thêm các cột nếu chưa tồn tại
            if (!Schema::hasColumn('grades', 'grade_type')) {
                $table->enum('grade_type', ['midterm', 'final', 'assignment', 'quiz', 'project'])->default('final')->after('course_id');
            }
            if (!Schema::hasColumn('grades', 'score')) {
                $table->decimal('score', 5, 2)->default(0)->after('grade_type');
            }
            if (!Schema::hasColumn('grades', 'max_score')) {
                $table->decimal('max_score', 5, 2)->default(100)->after('score');
            }
            if (!Schema::hasColumn('grades', 'semester')) {
                $table->string('semester', 20)->nullable()->after('max_score');
            }
            if (!Schema::hasColumn('grades', 'academic_year')) {
                $table->string('academic_year', 10)->nullable()->after('semester');
            }
        });
        
        // Xóa unique constraint nếu tồn tại
        try {
            Schema::table('grades', function (Blueprint $table) {
                $table->dropUnique(['student_id', 'course_id']);
            });
        } catch (\Exception $e) {
            // Ignore nếu constraint không tồn tại
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục unique constraint cũ
        Schema::table('grades', function (Blueprint $table) {
            $table->unique(['student_id', 'course_id']);
        });
        
        // Xóa các cột đã thêm
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn(['grade_type', 'score', 'max_score', 'semester', 'academic_year']);
        });
    }
};
