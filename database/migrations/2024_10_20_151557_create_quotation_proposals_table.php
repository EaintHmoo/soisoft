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
        Schema::create('quotation_proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bidder_id')->nullable();
            $table->unsignedBigInteger('quotation_id')->nullable();
            $table->string('quotation_fee_receipt', 255)->nullable();
            $table->text('proposal_comment')->nullable();
            $table->json('checklist_before_submit')->nullable();
            $table->enum('status', 
            ['proposed', 
            'cancelled',
            'nominated', 
            'disqualify', 
            'awarded'])->nullable()->default(null);
            $table->string('cancel_reason')->nullable();
            $table->text('cancel_comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_proposals');
    }
};
