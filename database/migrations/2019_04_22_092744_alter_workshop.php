<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWorkshop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
  
            Schema::table("workshops" , function(Blueprint $table){
                $table->string('enctype_id')->after('id');
            });
      
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
          Schema::table("workshops" , function(Blueprint $table){
                $table->dropColumn('votes');
            });
    }
}
