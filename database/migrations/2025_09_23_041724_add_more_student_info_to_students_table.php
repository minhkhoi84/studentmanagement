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
        Schema::table('students', function (Blueprint $table) {
            // Academic Information
            $table->string('major')->nullable(); // Chuyên ngành
            $table->integer('semester')->default(1); // Học kỳ hiện tại
            $table->decimal('gpa', 3, 2)->nullable(); // Điểm GPA
            $table->enum('status', ['active', 'inactive', 'graduated', 'suspended'])->default('active');
            
            // Personal Information
            $table->string('nationality')->default('Vietnamese');
            $table->string('religion')->nullable();
            $table->text('medical_conditions')->nullable();
            
            // Family Information
            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_occupation')->nullable();
            
            // Academic History
            $table->date('enrollment_date')->nullable();
            $table->string('previous_school')->nullable();
            $table->text('notes')->nullable(); // Ghi chú
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'major',
                'semester', 
                'gpa',
                'status',
                'nationality',
                'religion',
                'medical_conditions',
                'father_name',
                'father_phone', 
                'father_occupation',
                'mother_name',
                'mother_phone',
                'mother_occupation',
                'enrollment_date',
                'previous_school',
                'notes'
            ]);
        });
    }
};
