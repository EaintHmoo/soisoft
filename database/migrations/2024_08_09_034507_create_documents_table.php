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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('document_type', 50); //Specification, T & C , etc (get from config)
            $table->string('document_path', 255);
            $table->boolean('required_resubmit')->default(false); //required to response
            $table->text('question_columns')->nullable();
            $table->text('answer_columns')->nullable();
            $table->boolean('comparable')->default(false); //required to response
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
