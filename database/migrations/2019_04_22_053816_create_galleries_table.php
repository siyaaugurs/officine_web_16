<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');
			$table->unsignedBigInteger('workshops_id');
            $table->foreign('workshops_id')->references('id')->on('workshops');
			$table->string("image_name");
			$table->string('image_url');
			$table->char('type' , 1);
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
        Schema::dropIfExists('galleries');
    }
}
