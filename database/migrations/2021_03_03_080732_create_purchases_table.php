<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_no');
            $table->string('slug');
            $table->double('discount', 12, 2)->nullable();
            $table->double('transport', 12, 2)->nullable();
            $table->double('sub_total', 12, 2)->nullable();
            $table->string('po_reference')->nullable();
            $table->string('payment_terms')->nullable();
            $table->date('po_date')->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('note')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->boolean('is_paid')->nullable()->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('created_by');

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('purchases');
    }
}
