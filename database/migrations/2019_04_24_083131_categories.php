<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Categories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
       Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->char('category_type' , 2)->nullable();
			$table->unsignedBigInteger('parent_cat_id');
            $table->foreign('parent_cat_id')->references('id')->on('categories');
			$table->string("category_name")->nullable();
			$table->string('description')->nullable();
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
       Schema::dropIfExists('categories');
    }
}
