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
            $table->string('email')->unique()->after('name');
            $table->string('student_code')->unique()->after('email');
            $table->string('class')->after('student_code');
            $table->date('date_of_birth')->nullable()->after('class');
            $table->enum('gender', ['male', 'female', 'other'])->default('male')->after('date_of_birth');
            $table->string('phone')->nullable()->after('mobile');
            $table->text('emergency_contact')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'student_code', 
                'class',
                'date_of_birth',
                'gender',
                'phone',
                'emergency_contact'
            ]);
        });
    }
};
