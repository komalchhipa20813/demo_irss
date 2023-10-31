<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motor_policy_vehicals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('policy_id')->nullable();
            $table->foreign('policy_id')->references('id')->on('motor_policies');
            $table->tinyInteger('new_registration_no')->default(0)->comment('1=New, 0=Old');
            $table->string('registration_no')->nullable();
            $table->string('tp_start_date')->nullable();
            $table->string('tp_end_date')->nullable();
            $table->string('engine_no')->nullable();
            $table->string('chasiss_no')->nullable();
            $table->unsignedBigInteger('make_id')->nullable();
            $table->foreign('make_id')->references('id')->on('makes');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->foreign('model_id')->references('id')->on('product_models');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->foreign('variant_id')->references('id')->on('product_variants');
            $table->string('cc_gvw_no')->nullable();
            $table->string('manufacturing_year')->nullable();
            $table->string('seating_capacity')->nullable();
            $table->string('fuel_type')->nullable();
            $table->unsignedBigInteger('hypothication')->nullable();
            $table->foreign('hypothication')->references('id')->on('banks');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=DeActive 2=deleted');
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
        Schema::dropIfExists('motor_policy_vehicals');
    }
};
