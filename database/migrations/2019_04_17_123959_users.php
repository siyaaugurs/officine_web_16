<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('f_name',50);
            $table->string('l_name',50);
            $table->string('user_name',50);
            $table->string('email',50);
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('mobile_number');
            $table->string('password');
            $table->enum('is_signed',[0 , 1]);
            $table->integer('roll_id');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('users');

    }
}
