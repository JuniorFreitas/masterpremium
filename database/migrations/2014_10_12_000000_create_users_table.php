<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 255);
            $table->string('logradouro', 255)->nullable();
            $table->string('complemento', 255)->nullable();
            $table->string('bairro', 255)->nullable();
            $table->string('municipio', 255)->nullable();
            $table->string('uf', 255)->nullable();
            $table->string('cep')->nullable();
            $table->string('login')->nullable();
            $table->string('password')->nullable();
            $table->string('tipo');
            $table->integer('grupo_id')->unsigned();
            $table->string('cadastrou');
            $table->boolean('ativo');
            $table->boolean('temp')->default(0);
            $table->dateTime('ultimo_acesso')->nullable();
            $table->timestamps();
            $table->rememberToken();
//            $table->timestamp('email_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
