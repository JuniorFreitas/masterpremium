<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_banners', function (Blueprint $table) {
            $table->unsignedInteger('banner_id');
            $table->unsignedInteger('arquivo_id');

            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade');
            $table->foreign('arquivo_id')->references('id')->on('arquivos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pivot_banners');
    }
}
