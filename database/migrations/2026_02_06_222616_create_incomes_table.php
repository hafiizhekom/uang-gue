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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('master_period_id')->constrained()->onDelete('cascade');
            $table->date('date')->index();
            $table->string('title');
            $table->decimal('amount', 15, 2);
            
            // Relasi ke Master Type
            $table->foreignId('master_income_type_id')
                ->nullable()
                ->constrained('master_income_types')
                ->onDelete('set null');

            $table->foreignId('master_payment_id')->nullable()->index()->constrained('master_payments')->onDelete('set null');
            
            // Relasi ke User
            

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
        Schema::dropIfExists('incomes');
    }
};
