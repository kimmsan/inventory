<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->id();
            $table->double('quantity', 12, 2)->nullable();
            $table->double('purchase_price', 12, 2)->nullable();
            $table->double('unit_cost', 12, 2)->nullable();
            $table->double('tax_amount', 12, 2)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('purchase_products');
    }
}
