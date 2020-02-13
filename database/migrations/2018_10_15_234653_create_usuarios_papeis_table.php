<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosPapeisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios_papeis', function (Blueprint $table) {
            $table->integer('usuario_id')->unsigned();
            $table->integer('papel_id')->unsigned();

            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('papel_id')->references('id')->on('papeis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios_papeis');
    }
}
