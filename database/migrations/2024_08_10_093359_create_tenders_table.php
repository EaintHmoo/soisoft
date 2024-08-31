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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('buyer_id')
            //     ->nullable()
            //     ->references('id')
            //     ->on('users')
            //     ->onDelete('set null');
            
            $table->string('tender_no', 50); //uuid
            $table->string('department_id')
                ->nullable()
                ->references('id')
                ->on('departments')
                ->onDelete('set null');

            $table->string('project_id')
                ->nullable()
                ->references('id')
                ->on('projects')
                ->onDelete('set null');


            // $table->string('tender_type', 20)->default('tender'); //tender or rfq
            $table->string('type_of_sourcing', 20)->default('open'); //open or closed
            $table->string('evaluation_type', 20);
            $table->string('tender_title', 100);

            $table->foreignId('tender_category_id')
                ->nullable()
                ->references('id')
                ->on('tender_categories')
                ->onDelete('set null');

            $table->foreignId('tender_sub_category_id')
                ->nullable()
                ->references('id')
                ->on('tender_categories')
                ->onDelete('set null');

            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('currency', 10)->default('usd'); //usd, thb (get currencies from config)
            $table->string('mode_of_submission', 20)->default('online'); //electronically, physically 
            $table->boolean('nda_required')->default(false);
            $table->string('nda_document', 255)->nullable();

            $table->text('internal_details')->nullable();
            $table->text('external_details')->nullable();

            $table->boolean('briefing_information_required')->default(false);
            $table->date('briefing_date')->nullable();
            $table->string('briefing_venue', 255)->nullable(); //location of the briefing session
            $table->text('briefing_details')->nullable();
            $table->json('briefing_documents')->nullable();

            $table->boolean('fees_required')->default(false);
            $table->unsignedInteger('tender_fees')->nullable();
            $table->text('tender_fees_information')->nullable();
            // $table->json('fees_documents')->nullable();

            $table->string('awarding_agency', 100)->nullable();
            $table->json('publication_check_list')->nullable();
            $table->enum('tender_state', ['draft', 'review', 'approved', 'published'])->default('draft');

            $table->timestamps();
        });
        
        //Many to many relationship between tender and user with supplier role
        Schema::create('bidder_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_id');
            $table->unsignedBigInteger('supplier_id');
            $table->timestamps();
        });

        Schema::create('tender_contact', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_id');
            $table->unsignedBigInteger('contact_id');
            $table->timestamps();
        });
        
        Schema::create('tender_document', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_id');
            $table->unsignedBigInteger('document_id');
            $table->timestamps();
        });

        Schema::create('tender_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')
                ->references('id')
                ->on('tenders')
                ->onDelete('cascade');
            
            $table->string('part_number', 50); //eg - PN-001
            $table->string('uom', 20); //get from config (unit of measure)
            $table->unsignedInteger('estimate_quantity')->nullable();
            $table->text('specifications')->nullable();
            $table->text('description')->nullable();
            $table->text('notes_to_supplier')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_items');
        Schema::dropIfExists('tender_document');
        Schema::dropIfExists('tender_contact');
        Schema::dropIfExists('bidder_details');
        Schema::dropIfExists('tenders');
    }
};
