<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PolizzaCarStatusMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polizza_car_status', function (Blueprint $table) {
            $table->increments('id');


            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('color')->nullable();
            $table->string('icon')->nullable();

            $table->integer('company_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polizza_car_status');
    }
}
