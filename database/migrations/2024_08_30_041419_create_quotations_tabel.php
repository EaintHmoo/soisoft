<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_type', 50);
            $table->string('department_id')
                ->nullable()
                ->references('id')
                ->on('departments')
                ->onDelete('set null');
            $table->string('project_id') //sourcing for
                ->nullable()
                ->references('id')
                ->on('projects')
                ->onDelete('set null');
            $table->string('reference_no', 50);
            $table->string('quotation_title', 100);
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');

            //sourcing information
            $table->string('evaluation_type', 20);
            $table->boolean('is_open_sourcing')->default(true); //open or closed
            $table->string('mode_of_submission', 20)->default('online'); //electronically, physically 
            $table->string('currency', 10)->default('usd');

            //Nda
            $table->boolean('nda_required')->default(false);
            $table->string('nda_document', 255)->nullable();

            //delivery info
            $table->string('delivery_contact_person')->nullable();
            $table->string('delivery_address')->nullable();
            $table->boolean('is_partial_delivery')->default(true);

            //briefing info
            $table->boolean('briefing_information_required')->default(false);
            $table->date('briefing_date')->nullable();
            $table->string('briefing_venue', 255)->nullable(); //location of the briefing session
            $table->text('briefing_details')->nullable();
            $table->json('briefing_documents')->nullable();

            $table->json('publication_check_list')->nullable();
            $table->enum('quotation_state', ['draft', 'review', 'approved', 'published'])->default('draft');            

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('quotation_category', function(Blueprint $table) {
            $table->unsignedBigInteger('quotation_id');
            $table->unsignedBigInteger('category_id');
        });

        Schema::create('quotation_document', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id');
            $table->unsignedBigInteger('document_id'); 
            $table->timestamps();
        });

        Schema::create('quotation_contact', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id');
            $table->unsignedBigInteger('contact_id');
            $table->timestamps();
        });

        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')
                ->references('id')
                ->on('quotations')
                ->onDelete('cascade');
            $table->string('type', 20)->default('goods'); //goods, services
            $table->unsignedInteger('quantity')->default(0);
            $table->string('uom', 100);
            $table->foreignId('category_id')
                ->nullable()
                ->references('id')
                ->on('categories')
                ->onDelete('set null');
            $table->string('expected_delivery_date', 50);
            $table->string('delivery_terms', 50);
            $table->string('payment_terms', 50);
            $table->string('payment_mode', 50);
            $table->text('description')->nullable();
            $table->text('remark')->nullable();

            //cost guide
            $table->decimal('company_estimated_unit_price')->nullable();
            $table->decimal('historical_unit_price')->nullable();

            //delivery info
            $table->boolean('same_as_header_address')->default(false);
            $table->string('delivery_contact_person')->nullable();
            $table->string('delivery_address')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_document');
        Schema::dropIfExists('quotation_contact');
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotation_category');
        Schema::dropIfExists('quotations');
    }
};
