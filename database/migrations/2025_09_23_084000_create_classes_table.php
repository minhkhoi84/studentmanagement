<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên lớp: CNTT01
            $table->string('code')->unique(); // Mã lớp
            $table->unsignedBigInteger('department_id')->nullable(); // Thuộc khoa nào
            $table->string('teacher_id')->nullable(); // Giáo viên chủ nhiệm
            $table->integer('max_students')->default(40); // Sĩ số tối đa
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
