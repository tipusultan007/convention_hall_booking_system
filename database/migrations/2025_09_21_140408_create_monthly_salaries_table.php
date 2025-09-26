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
        Schema::create('monthly_salaries', function (Blueprint $table) {
            $table->id();
        $table->foreignId('worker_id')->constrained()->onDelete('cascade');
        $table->date('salary_month'); // e.g., 2025-09-01 for September 2025
        $table->decimal('total_salary', 10, 2);
        $table->decimal('paid_amount', 10, 2)->default(0.00);
        $table->decimal('due_amount', 10, 2);
        $table->string('status'); // Unpaid, Partially Paid, Paid
        $table->timestamps();
        $table->unique(['worker_id', 'salary_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_salaries');
    }
};
