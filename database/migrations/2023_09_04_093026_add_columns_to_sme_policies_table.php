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
        Schema::table('sme_policies', function (Blueprint $table) {
            $table->unsignedBigInteger('edited_by_id')->nullable()->after('updated_by_id');
            $table->foreign('edited_by_id')->references('id')->on('users');
            $table->tinyInteger('code_type')->default(1)->comment('1=Agency ,2=Broker')->after('policy_cancel_remark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sme_policies', function (Blueprint $table) {
            //
        });
    }
};
