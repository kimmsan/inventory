<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceTansfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_tansfers', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
            $table->string('slug');
            $table->double('amount', 12, 2);
            $table->date('date')->nullable();
            $table->string('note')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->timestamps();
            $table->unsignedBigInteger('debit_id');
            $table->unsignedBigInteger('credit_id');
            $table->unsignedBigInteger('created_by');

            $table->foreign('debit_id')->references('id')->on('account_transactions')->onDelete('cascade')->onUpdate('no action');
            $table->foreign('credit_id')->references('id')->on('account_transactions')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('balance_tansfers');
    }
}
