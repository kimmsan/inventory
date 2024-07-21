<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no');
            $table->string('slug');
            $table->double('amount', 12, 2)->nullable();
            $table->double('interest', 12, 2)->nullable();
            $table->date('date')->nullable();
            $table->string('note')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('created_by');

            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade')->onUpdate('no action');
            $table->foreign('transaction_id')->references('id')->on('account_transactions')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('loan_payments');
    }
}
