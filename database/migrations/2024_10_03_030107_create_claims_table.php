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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('title');
            $table->text('description');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved_admin', 'approved_hr', 'approved_finance', 'rejected'])->default('draft');
            $table->enum('claim_type', ['mileage', 'items', 'others'])->default('others');
            $table->timestamp('submitted_at')->nullable();
            // Claim places column -> where the claim was made for. option is - (Malaysia Heritage Studios, Zoo Teruntum, Silverlake Outlet, Zoo Melaka, PSKT)
            $table->enum('claim_places', ['Malaysia Heritage Studios', 'Zoo Melaka', 'Zoo Teruntum', 'Silverlake Outlet Mall', 'PSKT']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
