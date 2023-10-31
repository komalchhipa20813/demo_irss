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
        Schema::create('raise_queries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('motor_policy_id')->nullable();
            $table->foreign('motor_policy_id')->references('id')->on('motor_policies');
            $table->unsignedBigInteger('health_policy_id')->nullable();
            $table->foreign('health_policy_id')->references('id')->on('health_policies');
            $table->unsignedBigInteger('sme_policy_id')->nullable();
            $table->foreign('sme_policy_id')->references('id')->on('sme_policies');
            $table->tinyInteger('policy_type')->comment('1=Motor , 2= Health ,3=SME');
            $table->string('ticket_no');
            $table->text('details');
            $table->timestamp('raised_on');
            $table->string('remark')->nullable();
            $table->string('tat')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable();
            $table->foreign('closed_by')->references('id')->on('users');
            $table->string('closed_date');
            $table->tinyInteger('status')->default(1)->comment('1=Pending, 2=Solved , 3=Discrepency ,4=Deleted');
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
        Schema::dropIfExists('raise_queries');
    }
};
