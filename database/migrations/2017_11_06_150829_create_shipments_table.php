<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('investor_id', false, 10)->nullable();
            $table->integer('volume');
            $table->float('latitude');
            $table->float('longitude');
            $table->timestamps();
        });

        Schema::table('shipments', function($table) {
            $table->foreign('investor_id')->references('id')->on('investors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
}
