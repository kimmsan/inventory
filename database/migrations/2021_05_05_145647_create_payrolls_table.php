<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('salary_month')->nullable();
            $table->string('deduction_reason')->nullable();
            $table->double('deduction_amount', 12, 2)->nullable();
            $table->double('mobile_bill', 12, 2)->nullable();
            $table->double('food_bill', 12, 2)->nullable();
            $table->double('bonus', 12, 2)->nullable();
            $table->double('commission', 12, 2)->nullable();
            $table->double('advance', 12, 2)->nullable();
            $table->double('festival_bonus', 12, 2)->nullable();
            $table->double('travel_allowance', 12, 2)->nullable();
            $table->double('others', 12, 2)->nullable();
            $table->date('salary_date')->nullable();
            $table->string('note')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('created_by');

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('payrolls');
    }
}
