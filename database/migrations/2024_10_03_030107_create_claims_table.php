<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id('claim_id');
            $table->foreignId('user_id')->constrained('users');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 8, 2);
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved_admin', 'approved_hr', 'approved_finance', 'rejected'])->default('draft');
            $table->string('claim_type', 10)->default('others');
            $table->timestamp('submitted_at')->nullable();
            $table->string('claim_company');
            $table->decimal('toll_amount')->nullable();
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->timestamp('date_from')->nullable();
            $table->timestamp('date_to')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
