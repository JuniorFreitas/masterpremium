<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PivotTestemunhal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_testemunhals', function (Blueprint $table) {
            $table->unsignedInteger('testemunhal_id');
            $table->unsignedInteger('arquivo_id');

            $table->foreign('testemunhal_id')->references('id')->on('testemunhals')->onDelete('cascade');
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
        Schema::dropIfExists('pivot_testemunhals');
    }
}
