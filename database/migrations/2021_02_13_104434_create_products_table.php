<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('code');
            $table->string('model')->nullable();
            $table->string('barcode_symbology')->nullable();
            $table->string('tax_type')->nullable();
            $table->double('purchase_price', 12, 2)->nullable();
            $table->double('regular_price', 12, 2)->nullable();
            $table->double('discount', 5, 2)->nullable();
            $table->double('inventory_count', 12, 2)->nullable();
            $table->unsignedTinyInteger('alert_qty')->nullable();
            $table->string('note')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('sub_cat_id');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();

            $table->foreign('sub_cat_id')->references('id')->on('product_sub_categories')->onDelete('cascade')->onUpdate('no action');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null')->onUpdate('no action');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null')->onUpdate('no action');
            $table->foreign('tax_id')->references('id')->on('vat_rates')->onDelete('set null')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
