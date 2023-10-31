<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('fdos', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('home_irss_branch_id')->nullable();
            $table->foreign('home_irss_branch_id')->references('id')->on('irss_branches');
            $table->unsignedBigInteger('business_category_id')->nullable();
            $table->foreign('business_category_id')->references('id')->on('business_categories');
            $table->string('email')->nullable();;
            $table->string('secondary_email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('prefix')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('secondary_phone')->nullable();
            $table->string('image')->nullable();
            $table->text('office_address')->nullable();
            $table->text('residential_address')->nullable();
            $table->string('city')->nullable();
            $table->tinyInteger('gender')->comment('0 for male 1 for female')->nullable();
            $table->string('dob')->nullable();
            $table->string('anniversary_date')->nullable();
            $table->string('joining_date')->nullable();
            $table->string('effective_from')->nullable();
            $table->string('salary')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('bank_id')->nullable();
            $table->string('holder_name')->nullable();
            $table->string('adharcard_number')->nullable();
            $table->string('pancard_number')->nullable();
            $table->tinyInteger('fdo_status')->default(1)->comment('1=accepted, 0=pending 2=rejected');
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
    public function down() {
        Schema::dropIfExists('fdos');
    }
};
