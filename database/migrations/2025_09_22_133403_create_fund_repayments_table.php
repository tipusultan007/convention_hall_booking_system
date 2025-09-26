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
        Schema::create('fund_repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowed_fund_id')->constrained()->onDelete('cascade');
            $table->decimal('repayment_amount', 12, 2);
            $table->date('repayment_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_repayments');
    }
};
