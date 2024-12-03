<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Reservation', function (Blueprint $table) {
            $table->id('res_ID');
            $table->unsignedBigInteger('cus_ID');
            $table->unsignedBigInteger('worker_ID');
            $table->date('res_Date');
            $table->date('res_Time');
            $table->enum('res_Status',
                ['requested done','accept', 'reject', 'on my way', 'arrived', 'mission succeeded', 'payment complete']);
            $table->text('res_notes');
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
        Schema::dropIfExists('Reservation');
    }
}
