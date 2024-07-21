<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->double('asset_cost', 12, 2);
            $table->boolean('depreciation')->nullable()->default(0);
            $table->boolean('depreciation_type')->nullable()->default(0);
            $table->double('salvage_value', 12, 2)->nullable();
            $table->double('useful_life', 5, 2)->nullable();
            $table->double('daily_depreciation', 12, 2)->nullable();
            $table->string('note')->nullable();
            $table->string('image_path')->nullable();
            $table->date('date')->nullable();
            $table->date('expire_date')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->timestamps();
            $table->unsignedBigInteger('cat_id');
            $table->unsignedBigInteger('created_by');

            $table->foreign('cat_id')->references('id')->on('asset_types')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('assets');
    }
}
