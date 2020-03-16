<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('users',function(Blueprint $table){         
            $table->text('provider')->after('email')->nullable();/*Line 14*/
            $table->text('provider_id')->after('email')->nullable();
         });
        //$table->text('provider')->nullable();
           
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
