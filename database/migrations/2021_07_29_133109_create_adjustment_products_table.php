<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjustmentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustment_products', function (Blueprint $table) {
            $table->id();
            $table->boolean('type')->nullable()->comment('1 is increment and 0 is decrement');
            $table->double('purchase_price', 12, 2)->nullable();
            $table->double('quantity', 12, 2)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('adjustment_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('adjustment_id')->references('id')->on('inventory_adjustments')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('adjustment_products');
    }
}
