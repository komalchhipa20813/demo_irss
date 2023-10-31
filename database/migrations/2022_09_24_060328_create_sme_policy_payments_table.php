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
        Schema::create('sme_policy_payments', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('payment_type')->comment('1=Cash, 2=Cheque 3=Demand Draft 4=Online Payment 5=Cash/Cheque 6=Cheque/Demand Draft 7=Cash/Demand Draft 8=Cash/Online Payment 9=Online Payment/Cheque 10=Online Payment/Demand Draft ')->nullable();
            $table->unsignedBigInteger('policy_id')->nullable();
            $table->foreign('policy_id')->references('id')->on('sme_policies');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->decimal('amount',20,2)->default('0');
            $table->string('account_number')->nullable();
            $table->string('number')->nullable()->comment('draft_no,dd_no,transaction_no');
            $table->string('payment_date')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=DeActive 2=deleted');
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
        Schema::dropIfExists('payments');
    }
};
