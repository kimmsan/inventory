<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_products', function (Blueprint $table) {
            $table->id();
            $table->double('quantity', 12, 2)->nullable();
            $table->double('purchase_price', 12, 2)->nullable();
            $table->double('sale_price', 12, 2)->nullable();
            $table->double('unit_cost', 12, 2)->nullable();
            $table->double('tax_amount', 12, 2)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade')->onUpdate('no action');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_products');
    }
}
