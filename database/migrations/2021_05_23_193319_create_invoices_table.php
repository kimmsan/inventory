<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('slug');
            $table->string('reference')->nullable();
            $table->double('transport', 12, 2)->nullable();
            $table->boolean('discount_type')->nullable()->comment('0 means fixed and 1 means percentage');
            $table->double('discount', 12, 2)->nullable();
            $table->double('sub_total', 12, 2)->nullable();
            $table->string('po_reference')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('delivery_place')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('note')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->boolean('is_paid')->nullable()->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('created_by');

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('no action');
            $table->foreign('tax_id')->references('id')->on('vat_rates')->onDelete('set null')->onUpdate('no action');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
