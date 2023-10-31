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
        Schema::create('generated_outwards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('irss_branch_id')->nullable();
            $table->foreign('irss_branch_id')->references('id')->on('irss_branches');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('company_branch_id')->nullable();
            $table->foreign('company_branch_id')->references('id')->on('company_branches');
            $table->unsignedBigInteger('branch_imd_id')->nullable();
            $table->foreign('branch_imd_id')->references('id')->on('branch_imd_names');
            $table->string('outward_no')->nullable();
            $table->string('pdf')->nullable();
            $table->tinyInteger('generated_outward_status')->default(1)->comment('1=pending 2=sent');
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
        Schema::dropIfExists('generated_outwards');
    }
};
