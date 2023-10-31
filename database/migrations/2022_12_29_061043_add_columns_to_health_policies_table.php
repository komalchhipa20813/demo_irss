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
        Schema::table('health_policies', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable()->after('sub_product_id');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by_id')->nullable()->after('created_by_id');
            $table->foreign('updated_by_id')->references('id')->on('users');
            $table->text('policy_cancel_reason')->nullable()->after('remark');
            $table->text('policy_cancel_remark')->nullable()->after('policy_cancel_reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('health_policies', function (Blueprint $table) {
            //
        });
    }
};
