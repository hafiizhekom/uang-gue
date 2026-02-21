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
        Schema::create('outcome_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outcome_id')->constrained('outcomes')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->string('title');
            $table->decimal('amount', 15, 2);
            
            // Pakai tabel master payment yang sama
            $table->foreignId('master_outcome_payment_id')->nullable()->constrained('master_outcome_payments')->onDelete('restrict');

            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outcome_details');
    }
};
