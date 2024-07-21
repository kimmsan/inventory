<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->double('amount', 12, 2)->nullable();
            $table->boolean('type')->comment('0 is Debit and 1 is Credit');
            $table->date('date')->nullable();
            $table->string('note')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->timestamps();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('transaction_id')->nullable()->constrained();
            $table->unsignedBigInteger('created_by');

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('non_invoice_payments');
    }
}
