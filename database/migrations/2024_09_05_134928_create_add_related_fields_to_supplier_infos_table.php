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
            $table->json('supplier_industries')->nullable();
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
            $table->dropColumn('supplier_industries');
            $table->dropColumn('supplier_category_id');
            $table->dropColumn('supplier_sub_category_id');
        });
    }
};
