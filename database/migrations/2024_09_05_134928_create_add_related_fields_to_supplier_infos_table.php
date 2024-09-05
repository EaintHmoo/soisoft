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
        Schema::table('supplier_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('business_type_id')->nullable();
            $table->unsignedBigInteger('primary_contact_country_id')->nullable();
            $table->unsignedBigInteger('company_contact_country_id')->nullable();
            $table->unsignedBigInteger('supplier_category_id')->nullable();
            $table->unsignedBigInteger('supplier_sub_category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_infos', function (Blueprint $table) {
            $table->dropColumn('business_type_id');
            $table->dropColumn('primary_contact_country_id');
            $table->dropColumn('company_contact_country_id');
            $table->dropColumn('supplier_category_id');
            $table->dropColumn('supplier_sub_category_id');
        });
    }
};
