<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacoes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('empresa_id');
            $table->integer('conta_id');
            $table->integer('categoria_id');
            $table->integer('subcategoria_id');
            $table->timestamp('dt_transacao')->nullable();
            $table->timestamp('dt_competencia')->nullable();
            $table->string('descricao', 150)->nullable();
            $table->double('vr_total', 10,2);
            $table->integer('recebido_de')->nullable();  //  RECEBEU PAGAMENTO DO CONTATO
            $table->integer('pago_a')->nullable();  // FEZ PAGAMENTO AO CONTATO
            $table->integer('conta_origem_id')->nullable();  // CONTA DE ORIGEM NA TRANSFERENCIA
            $table->integer('conta_destino_id')->nullable();  // CONTA DE DESTINO NA TRANSFERENCIA
            $table->string('tipo_pagamento', 1)->nullable();  /// V - À VISTA / P - CRIAR PARCELAS / R - REPETIR TRANSAÇÃO
            $table->string('forma_pagamento', 1)->nullable(); /// D - DINHEIRO / C - CHEQUE / B - BOLETO / A - CARTAO CREDITO / E - CARTAO DEBITO / T - TRANSFERENCIA / P - PROMISSORIA / F - DEBITO AUTOMATICO
            $table->string('ds_pago', 1)->default('N');  // S - SIM / N - NAO
            $table->string('repetir_transacao', 1)->default('N');  // N - NAO / A - SEMANAL / B - QUINZENAL / C - MENSAL / D - BIMESTRAL / E - TRIMESTRAL / F - SEMESTRAL / G - ANUAL
            $table->string('nr_documento', 30)->nullable();
            $table->string('comentarios', 200)->nullable();

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
        Schema::dropIfExists('transacoes');
    }
}
