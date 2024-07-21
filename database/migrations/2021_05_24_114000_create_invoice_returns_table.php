<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_no');
            $table->string('reason');
            $table->string('slug');
            $table->double('total_return', 12, 2)->nullable();
            $table->date('date')->nullable();
            $table->string('note')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->timestamps();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('transaction_id')->nullable()->constrained();
            $table->unsignedBigInteger('created_by');

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade')->onUpdate('no action');
            $table->foreign('transaction_id')->references('id')->on('account_transactions')->onDelete('set null')->onUpdate('no action');
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
        Schema::dropIfExists('invoice_returns');
    }
}
