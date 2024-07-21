<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reason')->nullable();
            $table->string('slug')->nullable();
            $table->double('amount', 12, 2);
            $table->boolean('type')->comment('0 is Debit and 1 is Credit');
            $table->string('cheque_no')->nullable();
            $table->string('receipt_no')->nullable();
            $table->dateTime('transaction_date', 0);
            $table->string('note')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->timestamps();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('created_by');

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('account_transactions');
    }
}
