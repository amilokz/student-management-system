<?php
// database/migrations/2024_01_01_000002_create_courses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('credits');
            $table->integer('duration_hours');
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            $table->timestamps();
        });

        // Pivot table for student-course enrollment
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('enrollment_date');
            $table->enum('status', ['enrolled', 'completed', 'dropped'])->default('enrolled');
            $table->decimal('grade', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_student');
        Schema::dropIfExists('courses');
    }
};