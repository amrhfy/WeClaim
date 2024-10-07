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
        Schema::create('claim_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('claims', 'claim_id')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users');
            $table->enum('status', ['approved', 'rejected']);
            $table->text('comments')->nullable();
            $table->timestamp('reviewed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_reviews');
    }
};
