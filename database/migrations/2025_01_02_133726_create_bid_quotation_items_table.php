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
        Schema::create('bid_quotation_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_item_id')->nullable();
            $table->unsignedBigInteger('bidder_id')->nullable();
            $table->integer('quantity')->default(0)->nullable();
            $table->decimal('unit_price',15 ,2)->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bid_quotation_items');
    }
};
