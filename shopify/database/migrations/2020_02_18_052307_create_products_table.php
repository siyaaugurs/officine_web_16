<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lab', 10)->nullable(true);
            $table->string('report_no', 150)->nullable(true);
            $table->string('shape', 25);
            $table->decimal('carats', 8, 2);
            $table->string('color', 10);
            $table->string('clarity', 10);
            $table->string('cut', 10)->nullable(true);
            $table->string('polish', 10);
            $table->string('symmetry', 10);
            $table->string('fluorescence', 10)->nullable(true);
            $table->string('measurements');
            $table->decimal('table_percentage', 8, 2)->nullable(true);
            $table->decimal('depth_percentage', 8, 2)->nullable(true);
            $table->string('ratio')->nullable(true);
            $table->string('video_link', 255)->nullable(true);
            $table->decimal('total_amount', 8, 2);
            $table->decimal('final_price', 8, 2)->nullable(true);
            $table->boolean('published')->default(false);
//            $table->decimal('rap_price', 8, 2)->nullable(true);
//            $table->string('discount')->nullable(true);
//            $table->decimal('per_carat_amount', 8, 2)->nullable(true);
            $table->timestamps();
            $table->unsignedBigInteger('csv_data_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('api_id')->nullable();
            $table->string('product_id')->nullable();

            $table->foreign('csv_data_id')->references('id')->on('csv_data');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('api_id')->references('id')->on('diamond_apis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
