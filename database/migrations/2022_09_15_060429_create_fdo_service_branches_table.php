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
        Schema::create('fdo_service_branches', function (Blueprint $table) {
            $table->unsignedBigInteger('fdo_id');
            $table->foreign('fdo_id')->references('id')->on('fdos');
            $table->unsignedBigInteger('irss_branch_id')->nullable();
            $table->foreign('irss_branch_id')->references('id')->on('irss_branches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fdo_service_branches');
    }
};
