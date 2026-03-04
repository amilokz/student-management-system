<?php
// database/migrations/2024_01_01_000003_create_attendance_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused']);
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Ensure one attendance record per student per course per day
            $table->unique(['student_id', 'course_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};