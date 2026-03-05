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
        Schema::create('outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('master_period_id')->constrained()->onDelete('cascade');
            $table->date('date')->nullable()->index();
            $table->string('title');
            $table->decimal('amount', 15, 2)->default(0);
            $table->boolean('has_detail')->default(false);

            // Relasi ke Master Tables yang baru dinamain ulang
            $table->foreignId('master_outcome_category_id')->nullable()->index()->constrained('master_outcome_categories')->onDelete('set null');
            $table->foreignId('master_outcome_type_id')->nullable()->index()->constrained('master_outcome_types')->onDelete('set null');
            $table->foreignId('master_payment_id')->nullable()->index()->constrained('master_payments')->onDelete('set null');
            

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
        Schema::dropIfExists('outcomes');
    }
};
