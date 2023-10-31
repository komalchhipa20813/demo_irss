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
                $table->dropForeign(['rto_code_id']);
                $table->dropColumn('rto_code_id');
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
            $table->bigIncrements('rto_code_id');
            $table->dropConstrainedForeignId('rto_code_id');
        });

    }
};
