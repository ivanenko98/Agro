<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('investor_id', false, 10)->nullable();
            $table->integer('elevator_id', false, 10)->nullable();
            $table->integer('capacity');
            $table->timestamps();
        });
        Schema::table('rents', function($table) {
            $table->foreign('investor_id')->references('id')->on('investors');
            $table->foreign('elevator_id')->references('id')->on('elevators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rents');
    }
}
