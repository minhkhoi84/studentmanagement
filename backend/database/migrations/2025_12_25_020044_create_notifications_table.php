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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'student_registered', 'grade_updated', 'class_updated', etc.
            $table->string('title'); // Tiêu đề ngắn gọn
            $table->text('message'); // Nội dung chi tiết
            $table->json('data')->nullable(); // Dữ liệu bổ sung (ids, links, etc.)
            $table->boolean('is_read')->default(false); // Đã đọc chưa
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Admin nào sẽ nhận (null = tất cả admin)
            $table->timestamp('read_at')->nullable(); // Thời gian đọc
            $table->timestamps();
            
            // Index để query nhanh hơn
            $table->index(['is_read', 'created_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
