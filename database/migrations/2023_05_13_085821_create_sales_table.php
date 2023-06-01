<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('slot_id')->unsigned();//Get reference below
            $table->foreign('slot_id')->references('id')->on('slots')->onDelete('cascade')->onUpdate('cascade');//Refere to above
            $table->timestamps();
        });
    }

    /* 
    create table "sales" ("id" integer not null primary key autoincrement, "created_at" datetime, "updated_at" datetime, foreign key("slot_id") references "slots"("id") on delete cascade on update cascade)
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
