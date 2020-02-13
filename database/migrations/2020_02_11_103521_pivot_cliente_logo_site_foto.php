<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PivotClienteLogoSiteFoto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_logo_foto', function (Blueprint $table) {
            $table->unsignedInteger('arquivo_id');
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('ordem');

            $table->foreign('arquivo_id')->references('id')->on('arquivos')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('cliente_logo_sites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cliente_logo_foto');
    }
}
