<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //database.sqlite
        Schema::create('slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('cost');
            $table->integer('quantity');
            $table->timestamps();
        });
        //   $table->foreign('slot_id')->references('id')->on('slots')->onDelete('cascade')->onUpdate('cascade');
    }

// $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slots');
    }
}
