<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    public function up()
    {
        Schema::create('Feedback', function (Blueprint $table) {
            $table->id('feed_ID');
            $table->unsignedBigInteger('ad_ID')->nullable();
            $table->unsignedBigInteger('cus_ID');
            $table->unsignedBigInteger('worker_ID');
            $table->dateTime('feed_time');
            $table->tinyInteger('rate')->default(0);
            $table->text('comment');
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
        Schema::dropIfExists('Feedback');
    }
}
