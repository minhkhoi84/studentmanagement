<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->decimal('midterm_score', 4, 2)->nullable(); // Điểm giữa kỳ
            $table->decimal('final_score', 4, 2)->nullable(); // Điểm cuối kỳ
            $table->decimal('total_score', 4, 2)->nullable(); // Điểm tổng kết
            $table->string('grade', 2)->nullable(); // A, B, C, D, F
            $table->enum('status', ['passed', 'failed', 'incomplete'])->default('incomplete');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->unique(['student_id', 'course_id']); // Mỗi sinh viên chỉ có 1 điểm cho 1 môn
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
