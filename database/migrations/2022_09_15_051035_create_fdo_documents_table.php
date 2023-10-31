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
        Schema::create('fdo_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fdo_id');
            $table->foreign('fdo_id')->references('id')->on('fdos');
            $table->unsignedBigInteger('document_type');
            $table->foreign('document_type')->references('id')->on('document_types');
            $table->string('name');
            $table->string('number');
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
        Schema::dropIfExists('fdo_documents');
    }
};
