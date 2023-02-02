<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('usuario_id'); /// ID DO USUÁRIO
            $table->integer('empresa_id'); /// ID DA EMPRESA
            $table->string('ds_acao', 1); /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
            $table->string('ds_mensagem', 200); /// MENSAGEM DESCRITIVA DA AÇÃO DO USUÁRIO

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
        Schema::dropIfExists('logs');
    }
}
