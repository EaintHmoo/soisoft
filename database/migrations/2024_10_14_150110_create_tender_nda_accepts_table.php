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
        Schema::create('tender_nda_accepts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bidder_id')->nullable();
            $table->unsignedBigInteger('tender_id')->nullable();
            $table->boolean('is_accept')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_nda_accepts');
    }
};
