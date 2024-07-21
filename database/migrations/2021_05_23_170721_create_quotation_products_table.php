<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_products', function (Blueprint $table) {
            $table->id();
            $table->double('quantity', 12, 2)->nullable();
            $table->double('purchase_price', 12, 2)->nullable();
            $table->double('sale_price', 12, 2)->nullable();
            $table->double('unit_cost', 12, 2)->nullable();
            $table->double('tax_amount', 12, 2)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('quotation_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('quotation_products');
    }
}
