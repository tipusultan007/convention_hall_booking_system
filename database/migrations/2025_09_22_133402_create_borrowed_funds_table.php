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
        Schema::create('borrowed_funds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lender_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_borrowed', 12, 2);
            $table->date('date_borrowed');
            $table->text('purpose');
            $table->decimal('amount_repaid', 12, 2)->default(0.00);
            $table->decimal('due_amount', 12, 2);
            $table->string('status')->default('Due'); // Due, Partially Repaid, Repaid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowed_funds');
    }
};
