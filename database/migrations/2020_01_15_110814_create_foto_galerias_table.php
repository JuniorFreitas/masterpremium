<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFotoGaleriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foto_galerias', function (Blueprint $table) {
            $table->unsignedInteger('arquivo_id');
            $table->unsignedInteger('galeria_id');
            $table->unsignedInteger('ordem');

            $table->foreign('arquivo_id')->references('id')->on('arquivos')->onDelete('cascade');
            $table->foreign('galeria_id')->references('id')->on('galerias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foto_galerias');
    }
}
