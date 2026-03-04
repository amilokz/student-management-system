<?php
// database/migrations/2024_01_01_000001_create_students_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('status', ['active', 'inactive', 'graduated', 'suspended'])->default('active');
            $table->date('enrollment_date')->nullable();
            $table->date('graduation_date')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->text('medical_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};