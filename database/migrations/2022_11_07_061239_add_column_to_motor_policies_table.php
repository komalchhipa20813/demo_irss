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
        Schema::table('motor_policies', function (Blueprint $table) {
            $table->unsignedBigInteger('outward_id')->after('sub_product_id')->nullable();
            $table->foreign('outward_id')->references('id')->on('generated_outwards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motor_policies', function (Blueprint $table) {
            //
        });
    }
};
