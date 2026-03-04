<?php
// database/migrations/2024_01_01_000004_create_fees_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('fee_type'); // tuition, library, sports, etc.
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->enum('status', ['paid', 'pending', 'overdue', 'partial'])->default('pending');
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};