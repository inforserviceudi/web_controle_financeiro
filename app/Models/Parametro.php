<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    public $timestamp = true;

    protected $table = "parametros";

    protected $fillable = [
        'usuario_id', 'ver_aba_recebimento', 'incluir_movimentacao_recebimento', 'excluir_movimentacao_recebimento',
        'alterar_movimentacao_recebimento', 'ver_aba_despfixa', 'incluir_movimentacao_despfixa', 'excluir_movimentacao_despfixa',
        'alterar_movimentacao_despfixa', 'ver_aba_despvariavel', 'incluir_movimentacao_despvariavel', 'excluir_movimentacao_despvariavel',
        'alterar_movimentacao_despvariavel', 'ver_aba_pessoas', 'incluir_movimentacao_pessoas', 'excluir_movimentacao_pessoas',
        'alterar_movimentacao_pessoas', 'ver_aba_impostos', 'incluir_movimentacao_impostos', 'excluir_movimentacao_impostos',
        'alterar_movimentacao_impostos', 'ver_aba_transferencia', 'incluir_movimentacao_transferencia',
        'excluir_movimentacao_transferencia', 'alterar_movimentacao_transferencia', 'permissao_usuarios', 'importar_arquivos',
        'excluir_arquivos', 'upload_arquivos', 'ver_arquivos', 'fazer_backup', 'alterar_dados_empresa', 'gerenciar_clientes_fornecedores',
        'ver_relatorios', 'ver_tela_resumo', 'gerenciar_categorias', 'gerencias_contas', 'ver_saldo',
    ];

    public function usuario()
    {
        return $this->hasOne('App\User', 'id', 'usuario_id');
    }
}
