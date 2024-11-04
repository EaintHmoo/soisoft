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
        Schema::create('quotation_addendums', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_id')
                ->index()
                ->references('id')
                ->on('quotations')
                ->cascadeOnDelete();

            $table->string('type', 50);
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('tender_addendums', function (Blueprint $table) {
            $table->id();
            $table->string('tender_id')
                ->index()
                ->references('id')
                ->on('tenders')
                ->cascadeOnDelete();

            $table->string('type', 50);
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_addendums');
        Schema::dropIfExists('tender_addendums');
    }
};
