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
        Schema::table('tenders', function (Blueprint $table) {
            $table->unsignedInteger('end_in_days')->nullable();
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->unsignedInteger('end_in_days')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn('end_in_days');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('end_in_days');
        });
    }
};
