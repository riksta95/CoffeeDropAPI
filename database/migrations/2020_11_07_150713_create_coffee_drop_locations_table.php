<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoffeeDropLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coffee_drop_locations', function (Blueprint $table) {
            $table->id();
            $table->string('postcode');
            $table->string('longitude');
            $table->string('latitude');
            $table->json('opening_times');
            $table->json('closing_times');
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
        Schema::dropIfExists('coffee_drop_locations');
    }
}
