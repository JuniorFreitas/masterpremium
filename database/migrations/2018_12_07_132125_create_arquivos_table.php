<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArquivosTable extends Migration
{
    public function up()
    {
        Schema::create('arquivos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quem_enviou')->nullable();
            $table->foreign('quem_enviou')->references('id')->on('users')->onDelete('cascade');
            //coloquei nesse casso cascade pq o usuário pode ser um futuro cliente pelo site. Se for reprovado, deve excluir os uploads dele

            $table->string('nome', 100);
            $table->boolean('imagem');
            $table->string('layout', 25)->nullable();
            $table->string('extensao', 5);
            $table->string('file', 100);
            $table->string('thumb', 100)->nullable();
            $table->bigInteger('bytes');
            $table->boolean('temporario');
            $table->string('chave', 90)->nullable();
            //Mesmo com varias abas do navegador aberto, cada aba tem uma chave de operação unica. Assim nao confundi cada upload
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
        Schema::dropIfExists('arquivos');
    }
}
