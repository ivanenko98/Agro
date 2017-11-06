<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElevatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elevators', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('farmer_id', false, 10)->nullable();
            $table->string('name');
            $table->integer('capacity');
            $table->float('latitude');
            $table->float('longitude');
            $table->timestamps();
        });

        Schema::table('elevators', function($table) {
            $table->foreign('farmer_id')->references('id')->on('farmers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elevators');
    }
}
