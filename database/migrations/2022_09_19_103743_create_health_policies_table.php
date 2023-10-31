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
        Schema::create('health_policies', function (Blueprint $table) {
            $table->id();
            $table->text('inward_no')->nullable();
            $table->string('policy_number')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->foreign('agent_id')->references('id')->on('agents');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedBigInteger('irss_branch_id')->nullable();
            $table->foreign('irss_branch_id')->references('id')->on('irss_branches');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('previous_company_id')->nullable();
            $table->foreign('previous_company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('renewal_previous_company_id')->nullable();
            $table->foreign('renewal_previous_company_id')->references('id')->on('companies');
            $table->string('renewal_previous_policy_number')->nullable();
            $table->unsignedBigInteger('company_branch_id')->nullable();
            $table->foreign('company_branch_id')->references('id')->on('company_branches');
            $table->unsignedBigInteger('branch_imd_id')->nullable();
            $table->foreign('branch_imd_id')->references('id')->on('branch_imd_names');
            $table->unsignedBigInteger('sub_product_id')->nullable();
            $table->foreign('sub_product_id')->references('id')->on('sub_products');
            $table->string('policy_type')->nullable();
            $table->tinyInteger('has_previous_policy')->default(1)->comment('1=yes, 2=no');
            $table->string('previous_policy_number')->nullable();
            $table->string('previous_start_date')->nullable();
			$table->string('previous_end_date')->nullable();
            $table->tinyInteger('product_type')->comment('0=individual, 1=floater')->nullable();
            $table->string('business_date')->nullable();
            $table->string('policy_copy')->nullable();
            $table->string('issue_date')->nullable();
			$table->string('start_date')->nullable();
			$table->string('end_date')->nullable();
            $table->string('proposal_dob')->nullable();
            $table->string('policy_tenure')->nullable();
            $table->string('sum_insured')->nullable();
            $table->string('od')->nullable();
            $table->tinyInteger('is_gst_value')->default(1)->comment('1=no, 2=yes');
            $table->string('gst')->nullable();
            $table->string('total_premium')->nullable();
            $table->text('remark')->nullable();
            $table->tinyInteger('add_member')->default(1)->comment('0=yes 1=no');
            $table->tinyInteger('policy_status')->default(1)->comment('1=pending, 2=generated 3=outward_uploaded 4=completed');
            $table->tinyInteger('policy_copy_status')->default(1)->comment('1=pending, 2=uploaded');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=DeActive 2=deleted 3=cancelled');
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
        Schema::dropIfExists('health_policies');
    }
};
