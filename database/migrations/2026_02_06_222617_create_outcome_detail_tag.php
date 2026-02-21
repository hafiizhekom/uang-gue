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
        Schema::create('outcome_detail_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outcome_detail_id')->constrained('outcome_details')->onDelete('cascade');
            $table->foreignId('master_outcome_detail_tag_id')->constrained('master_outcome_detail_tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outcome_detail_tag');
    }
};
