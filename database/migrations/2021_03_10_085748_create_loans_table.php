<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
            $table->string('slug');
            $table->string('reference_no');
            $table->boolean('loan_type')->nullable();
            $table->double('interest', 12, 2)->nullable();
            $table->double('payable', 12, 2)->nullable();
            $table->boolean('payment_type')->nullable()->comment('0 is Daily 1 is Monthly and 2 is Yearly');
            $table->unsignedTinyInteger('duration')->nullable();
            $table->date('date')->nullable();
            $table->string('note')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->boolean('is_paid')->nullable()->default(0);
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('authority_id');
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('created_by');

            $table->foreign('authority_id')->references('id')->on('loan_authorities')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('loans');
    }
}
