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
        Schema::table('quotation_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('document_by_id')->nullable();
            $table->text('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_documents', function (Blueprint $table) {
            $table->dropColumn('document_by_id');
            $table->dropColumn('comment');
        });
    }
};
