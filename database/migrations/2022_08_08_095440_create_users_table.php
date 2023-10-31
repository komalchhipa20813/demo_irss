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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->unsignedBigInteger('irss_branch_id')->nullable();
            $table->foreign('irss_branch_id')->references('id')->on('irss_branches');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->foreign('designation_id')->references('id')->on('designations');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('code')->nullable();
			$table->string('prefix')->nullable();
			$table->string('first_name')->nullable();
			$table->string('middle_name')->nullable();
			$table->string('last_name')->nullable();
            $table->string('phone')->nullable();
			$table->string('image')->nullable();
			$table->text('address')->nullable();
			$table->tinyInteger('gender')->comment('0 for male 1 for female')->nullable();
			$table->string('dob')->nullable();
            $table->string('anniversary_date')->nullable();
			$table->string('joining_date')->nullable();
			$table->string('salary')->nullable();
			$table->string('account_number')->nullable();
			$table->string('ifsc_code')->nullable();
			$table->string('bank_name')->nullable();
			$table->string('holder_name')->nullable();
			$table->tinyInteger('status')->default(1)->comment('1=Active, 0=DeActive 2=deleted');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
