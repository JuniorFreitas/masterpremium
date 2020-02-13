<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePapeisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('papeis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 150);
            $table->string('descricao', 150);
            $table->string('email', 150);
            $table->string('ativo', 1)->default('s');

        });

        Schema::create('papeis_habilidades', function (Blueprint $table) {

            $table->integer('papel_id')->unsigned();
            $table->integer('habilidade_id')->unsigned();

            $table->foreign('papel_id')->references('id')->on('papeis');
            $table->foreign('habilidade_id')->references('id')->on('habilidades');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('papeis_habilidades');
        Schema::dropIfExists('papeis');
    }
}
