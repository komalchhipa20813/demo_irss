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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('leave_type');
            $table->string('from_date');
            $table->string('to_date');
            $table->string('leave_type_day')->comment('F=Fullday, H=Halfday');
            $table->unsignedBigInteger('work_handover_user_id')->nullable();
            $table->foreign('work_handover_user_id')->references('id')->on('users');
            $table->string('leave_reason');
            $table->tinyInteger('status')->default(1)->comment('1=Pending, 2=Approved , 3=Not Approved ,4=Rollback');
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
        Schema::dropIfExists('leave_applications');
    }
};
