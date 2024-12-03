<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Schedule', function (Blueprint $table) {
            $table->id('sch_ID');
            $table->boolean('sch_Status')->default(true);
            $table->string('sch_Day')->unique();
            $table->time('sch_Time_From')->nullable();
            $table->time('sch_Time_To')->nullable();
            $table->unsignedBigInteger('worker_ID');
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
        Schema::dropIfExists('Schedule');
    }
}
