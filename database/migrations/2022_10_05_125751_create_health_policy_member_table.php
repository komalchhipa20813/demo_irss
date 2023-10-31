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
        Schema::create('health_policy_member', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('health_policy_id')->nullable();
            $table->foreign('health_policy_id')->references('id')->on('health_policies');
            $table->string('relation')->nullable();
            $table->string('name')->nullable();
            $table->string('dob')->nullable();
            $table->string('sum_insured')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0=DeActive,1=Active,2=deleted');
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
        Schema::dropIfExists('health_policy_member');
    }
};
