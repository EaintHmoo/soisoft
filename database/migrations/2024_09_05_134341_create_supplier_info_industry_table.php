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
        Schema::create('supplier_info_industry', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_info_id')->nullable();
            $table->unsignedBigInteger('supplier_industry_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_info_industry');
    }
};
