<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParametros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametros', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('usuario_id');
            $table->string('ver_aba_recebimento')->default("N");
            $table->string('incluir_movimentacao_recebimento')->default("N");
            $table->string('excluir_movimentacao_recebimento')->default("N");
            $table->string('alterar_movimentacao_recebimento')->default("N");
            $table->string('ver_aba_despfixa')->default("N");
            $table->string('incluir_movimentacao_despfixa')->default("N");
            $table->string('excluir_movimentacao_despfixa')->default("N");
            $table->string('alterar_movimentacao_despfixa')->default("N");
            $table->string('ver_aba_despvariavel')->default("N");
            $table->string('incluir_movimentacao_despvariavel')->default("N");
            $table->string('excluir_movimentacao_despvariavel')->default("N");
            $table->string('alterar_movimentacao_despvariavel')->default("N");
            $table->string('ver_aba_pessoas')->default("N");
            $table->string('incluir_movimentacao_pessoas')->default("N");
            $table->string('excluir_movimentacao_pessoas')->default("N");
            $table->string('alterar_movimentacao_pessoas')->default("N");
            $table->string('ver_aba_impostos')->default("N");
            $table->string('incluir_movimentacao_impostos')->default("N");
            $table->string('excluir_movimentacao_impostos')->default("N");
            $table->string('alterar_movimentacao_impostos')->default("N");
            $table->string('ver_aba_transferencia')->default("N");
            $table->string('incluir_movimentacao_transferencia')->default("N");
            $table->string('excluir_movimentacao_transferencia')->default("N");
            $table->string('alterar_movimentacao_transferencia')->default("N");
            $table->string('permissao_usuarios')->default("N");
            $table->string('importar_arquivos')->default("N");
            $table->string('excluir_arquivos')->default("N");
            $table->string('upload_arquivos')->default("N");
            $table->string('ver_arquivos')->default("N");
            $table->string('fazer_backup')->default("N");
            $table->string('alterar_dados_empresa')->default("N");
            $table->string('gerenciar_clientes_fornecedores')->default("N");
            $table->string('ver_relatorios')->default("N");
            $table->string('ver_tela_resumo')->default("N");
            $table->string('gerenciar_categorias')->default("N");
            $table->string('gerencias_contas')->default("N");
            $table->string('ver_saldo')->default("N");

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
        Schema::dropIfExists('parametros');
    }
}
