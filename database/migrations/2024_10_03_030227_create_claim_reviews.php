<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    /////////////////////////////////////////////////////////////

    public function up(): void
    {
        Schema::create('claim_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('claim', 'id')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users');
            $table->text('remarks_admin')->nullable();
            $table->text('remarks_datuk')->nullable();
            $table->text('remarks_hr')->nullable();
            $table->text('remarks_finance')->nullable();
            $table->timestamp('reviewed_at');
            $table->timestamps();
        });
    }

    /////////////////////////////////////////////////////////////

    public function down(): void
    {
        Schema::dropIfExists('claim_reviews');
    }
};
