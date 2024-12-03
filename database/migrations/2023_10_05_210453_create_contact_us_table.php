<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ContactUs', function (Blueprint $table) {
            $table->id('con_id');
            $table->unsignedBigInteger('worker_ID')->nullable();
            $table->unsignedBigInteger('cus_ID')->nullable();
            $table->unsignedBigInteger('ad_ID')->nullable();
            $table->string('con_subject');
            $table->text('con_message');
            $table->dateTime('con_dateTime');
            $table->text('con_replay')->nullable();
            $table->foreign('ad_ID')->references('ad_ID')->on('Admin')->onDelete('SET NULL');
            $table->foreign('cus_ID')->references('cus_ID')->on('Customer')->onDelete('CASCADE');
            $table->foreign('worker_ID')->references('worker_ID')->on('Worker')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ContactUs');
    }
}
