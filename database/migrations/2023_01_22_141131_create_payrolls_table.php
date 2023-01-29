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
            $table->date('date_from');
            $table->date('date_until');

            $table->decimal('rate')->default(0);
            $table->integer('hours')->default(0);
            $table->decimal('deduction')->default(0);

            $table->string('employee_code');
            $table->string('employee_name');

            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('employee_id')->unsigned();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->timestamps();
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
