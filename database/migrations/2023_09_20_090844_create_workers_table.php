<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Worker', function (Blueprint $table) {
            $table->id('worker_ID');
            $table->enum('worker_Status', ['pending', 'confirmed', 'rejected', 'banned'])->default('pending');
            $table->string('worker_ResidenceNum');
            $table->string('worker_NationalID');
            $table->string('worker_Fname');
            $table->string('worker_Lname');
            $table->string('worker_Email')->unique();
            $table->string('password');
            $table->string('worker_Phone1');
            $table->string('worker_Phone2')->nullable();
            $table->string('worker_Occupation');
            $table->text('worker_Skill')->nullable();
            $table->text('worker_Experience')->nullable();
            $table->integer('worker_accountNum')->nullable();
            $table->string('worker_Iban')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Worker');
    }
}
