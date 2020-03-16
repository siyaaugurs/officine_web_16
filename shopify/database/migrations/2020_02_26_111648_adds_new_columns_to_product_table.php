<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddsNewColumnsToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('stock_no', 150)->nullable(true);
            $table->string('girdle', 100)->nullable(true);
            $table->string('culet', 100)->nullable(true);
            $table->string('image_link', 255)->nullable(true);
            $table->string('report_link', 255)->nullable(true);
            $table->string('origin', 50)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['girdle', 'culet', 'image_link', 'report_link', 'origin']);
        });
    }
}
