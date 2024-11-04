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
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('quotation_type', 50)->nullable()->change();
            $table->string('reference_no', 50)->nullable()->change();
            $table->dateTime('start_datetime')->nullable()->change();
            $table->dateTime('end_datetime')->nullable()->change();
            $table->string('evaluation_type', 20)->nullable()->change();
            
            $table->string('mode_of_submission', 20)->default('online')->nullable()->change(); //electronically, physically 
            $table->string('currency', 10)->default('usd')->nullable()->change();
            // $table->boolean('nda_required')->default(false);
            // $table->boolean('is_partial_delivery')->default(true);
            // $table->boolean('briefing_information_required')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            //
        });
    }
};
