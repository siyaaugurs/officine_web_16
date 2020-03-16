<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('price_addon_percentage', 8, 2)->nullable();
            $table->enum('storage_driver', ['ftp', 'sftp', 'public'])->default('public');
            $table->string('ftp_host')->nullable();
            $table->string('ftp_port')->nullable();
            $table->string('ftp_username')->nullable();
            $table->string('ftp_password')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
