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
        Schema::create('bookings', function (Blueprint $table) {
             $table->id(); // This will be our "Contract No"
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('event_type');
            $table->integer('guests_count')->nullable();
            $table->integer('tables_count')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->decimal('advance_amount', 10, 2)->default(0.00);
            $table->decimal('due_amount', 10, 2)->default(0.00);
            $table->string('status')->default('Pending'); // e.g., Pending, Confirmed, Completed
            $table->text('notes_in_words')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
