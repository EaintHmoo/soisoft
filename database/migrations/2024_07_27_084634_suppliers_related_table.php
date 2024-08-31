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
        Schema::create('supplier_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('supplier_type', 20); //individual or coprorate
            $table->string('business_type', 50); //Construction,etc ... 
            $table->string('registration_number', 50)->nullable(); //for corporate type
            $table->string('vat_number', 50)->nullable(); //for corporate type
            $table->string('company_name', 100)->nullable(); //for corporate type
            $table->string('primary_contact_full_name', 100)->nullable();
            $table->string('primary_contact_designation', 100)->nullable();
            $table->string('primary_contact_phone', 20)->nullable();
            $table->string('primary_contact_email', 100)->nullable();
            $table->string('primary_contact_address1', 255)->nullable();
            $table->string('primary_contact_address2', 255)->nullable();
            $table->string('primary_contact_province', 100)->nullable();
            $table->string('primary_contact_city', 100)->nullable();
            $table->string('primary_contact_postal_code', 20)->nullable();
            $table->string('primary_contact_country', 100)->nullable();

            $table->string('supplier_industry', 255)->nullable(); //office furniture, technology and electronic, etc...

            $table->string('company_contact_full_name', 100)->nullable();
            $table->string('company_contact_designation', 100)->nullable();
            $table->string('company_contact_phone', 20)->nullable();
            $table->string('company_contact_email', 100)->nullable();
            $table->string('company_contact_address1', 255)->nullable();
            $table->string('company_contact_address2', 255)->nullable();
            $table->string('company_contact_province', 100)->nullable();
            $table->string('company_contact_city', 100)->nullable();
            $table->string('company_contact_postal_code', 20)->nullable();
            $table->string('company_contact_country', 100)->nullable();

            $table->string('individual_contact_full_name', 100)->nullable();
            $table->string('individual_contact_designation', 100)->nullable();
            $table->string('individual_contact_phone', 20)->nullable();
            $table->string('individual_contact_email', 100)->nullable();
            $table->string('individual_contact_address', 255)->nullable();
            $table->string('category', 100)->nullable();//construction, electronic, etc... 

            $table->timestamps();
        });

        Schema::create('supplier_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('buyer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
            $table->text('comment_text'); //good supplier, timely delivery
            $table->timestamps();
        });

        Schema::create('supplier_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('buyer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
            $table->string('tag_text', 50); //Reliable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_tags');
        Schema::dropIfExists('supplier_comments');
        Schema::dropIfExists('supplier_infos');
    }
};
