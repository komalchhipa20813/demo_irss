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
        Schema::table('motor_policy_vehicals', function (Blueprint $table) {
            $table->unsignedBigInteger('rto_code_id')->nullable()->after('policy_id');
            $table->foreign('rto_code_id')->references('id')->on('r_t_o_s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motor_policy_vehicals', function (Blueprint $table) {
            //
        });
    }
};
