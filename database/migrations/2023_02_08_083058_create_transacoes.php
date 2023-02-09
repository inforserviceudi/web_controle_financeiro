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
            $table->integer('recebido_de');  //  RECEBEU PAGAMENTO DO CONTATO
            $table->integer('pago_a');  // FEZ PAGAMENTO AO CONTATO
            $table->float('valor_parcela');
            $table->string('tipo_pagamento', 1)->nullable();  /// V - À VISTA / P - CRIAR PARCELAS / R - REPETIR TRANSAÇÃO
            $table->string('forma_pagamento', 1)->nullable(); /// D - DINHEIRO / C - CHEQUE / B - BOLETO / A - CARTAO CREDITO / E - CARTAO DEBITO / T - TRANSFERENCIA / P - PROMISSORIA / F - DEBITO AUTOMATICO
            $table->integer('nr_parcela')->default(1);  // MÍNIMO DE 1 PARCELA QUE CORRESPONDE AO PAGAMENTO À VISTA OU NO MÁXIMO 12 VEZES
            $table->string('ds_pago', 1)->default('N');  // S - SIM / N - NAO
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
