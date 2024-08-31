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
        Schema::create('tender_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key')->nullable()->unique();
            $table->string('name');
            $table->string('slug');
            $table->bigInteger('parent_id')->default('-1');
            $table->unsignedInteger('order')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_categories');
    }
};
