<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkshopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');
			$table->string('title');
			$table->date('workshop_start_date');
			$table->date('workshop_end_date');
			$table->time('workshop_start_time');
			$table->time('workshop_end_time');
			$table->enum('paid_status' , [0 , 1])->nullable();
			$table->enum('address_status',[0 , 1])->nullable();
 			$table->string('address')->nullable();
            $table->string('landmark')->nullable();
			$table->integer('country_id')->nullable();
			$table->integer('city_id')->nullable();
			$table->text("description")->nullable();
			$table->float('amount', 8, 2)->nullable();
			$table->enum('gallery_status',[0 , 1])->nullable();
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
        Schema::dropIfExists('workshops');
    }
}
