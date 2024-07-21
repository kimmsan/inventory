<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('emp_id');
            $table->string('slug');
            $table->string('designation');
            $table->double('salary', 12, 2)->nullable();
            $table->double('commission', 12, 2)->nullable();
            $table->string('mobile_number');
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('religion')->nullable();
            $table->date('appointment_date')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('address')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade')->onUpdate('no action');
            $table->foreign('user_id')->nullable()->constrained()->on('users')->references('id')->onDelete('cascade')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
