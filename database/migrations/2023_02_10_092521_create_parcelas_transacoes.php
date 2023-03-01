<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParcelasTransacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcelas_transacoes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('transacao_id');
            $table->string('tipo_transacao', 1)->nullable();  /// R - RECEBIMENTO / D - DESPESAS
            $table->integer('nr_parcela');
            $table->float('vr_parcela')->nullable();
            $table->timestamp('dt_vencimento')->nullable();
            $table->timestamp('dt_pagamento')->nullable();
            $table->string('ds_pago')->default('N');

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
        Schema::dropIfExists('parcelas_transacoes');
    }
}
