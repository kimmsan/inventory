<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_products', function (Blueprint $table) {
            $table->id();
            $table->double('purchase_price', 12, 2)->nullable();
            $table->double('quantity', 12, 2)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('return_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('return_id')->references('id')->on('purchase_returns')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('purchase_return_products');
    }
}
