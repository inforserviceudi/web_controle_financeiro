<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transacao extends Model
{
    public $timestamp = true;

    protected $table = "transacoes";

    // tipo_pagamento
    /// V - À VISTA / P - CRIAR PARCELAS / R - REPETIR TRANSAÇÃO

    // forma_pagamento
    /// D - DINHEIRO / C - CHEQUE / B - BOLETO / A - CARTAO CREDITO
    /// E - CARTAO DEBITO / T - TRANSFERENCIA / P - PROMISSORIA / F - DEBITO AUTOMATICO

    // repetir transação
    // N - NAO / A - SEMANAL / B - QUINZENAL / C - MENSAL / D - BIMESTRAL / E - TRIMESTRAL / F - SEMESTRAL / G - ANUAL
    protected $fillable = [
        'empresa_id', 'conta_id', 'categoria_id', 'subcategoria_id', 'dt_transacao', 'dt_competencia', 'descricao',
        'recebido_de', 'pago_a', 'tipo_pagamento', 'forma_pagamento', 'ds_pago', 'nr_documento', 'comentarios',
        'repetir_transacao', 'vr_total', 'conta_origem_id', 'conta_destino_id'
    ];

    public static function qryRelatorio($nm_select1, $nm_select2, $nm_select3, $empresa_id, $ds_pago, $mostrar_data, $conta_id, $dt_final, $dt_inicial, $tp_relatorio)
    {
        if( $nm_select2 == null || $nm_select3 == null ){
            return Transacao::select($nm_select1, 'parcelas_transacoes.vr_parcela', 'parcelas_transacoes.tipo_transacao')
            ->leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where(function ($q) use ( $ds_pago, $mostrar_data, $conta_id, $dt_final, $dt_inicial, $tp_relatorio ){
                if($tp_relatorio === "D" ){
                    $q->where('transacoes.categoria_id', '<>', 1);
                }elseif($tp_relatorio === "R" ){
                    $q->where('transacoes.categoria_id', '=', 1);
                }

                if( $conta_id !== "T" || $conta_id !== 0 ){
                    $q->where('transacoes.conta_id', $conta_id);
                }

                if( $ds_pago !== "A" ){
                    $q->where('parcelas_transacoes.ds_pago', $ds_pago);
                }

                if( $mostrar_data === "dt_pagamento" ){
                    $q->whereBetween('parcelas_transacoes.dt_pagamento', [ $dt_inicial, $dt_final ]);
                }elseif( $mostrar_data === "dt_competencia" ){
                    $q->whereBetween('transacoes.dt_competencia', [ $dt_inicial, $dt_final ]);
                }
            })
            ->get();
        }else{
            return Transacao::select($nm_select1, $nm_select2, $nm_select3, 'parcelas_transacoes.vr_parcela', 'parcelas_transacoes.tipo_transacao')
            ->leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where(function ($q) use ( $ds_pago, $mostrar_data, $conta_id, $dt_final, $dt_inicial ){

                if( $conta_id > 0 ){
                    $q->where('transacoes.conta_id', $conta_id);
                }

                if( $ds_pago !== "A" ){
                    $q->where('parcelas_transacoes.ds_pago', $ds_pago);
                }

                if( $mostrar_data === "dt_pagamento" ){
                    $q->whereBetween('parcelas_transacoes.dt_pagamento', [ $dt_inicial, $dt_final ]);
                }elseif( $mostrar_data === "dt_competencia" ){
                    $q->whereBetween('transacoes.dt_competencia', [ $dt_inicial, $dt_final ]);
                }
            })
            ->get();
        }
    }

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'id', 'empresa_id');
    }

    public function conta()
    {
        return $this->hasOne(Conta::class, 'id', 'conta_id');
    }

    public function conta_origem()
    {
        return $this->hasOne(Conta::class, 'id', 'conta_origem_id');
    }

    public function conta_destino()
    {
        return $this->hasOne(Conta::class, 'id', 'conta_destino_id');
    }

    public function categoria()
    {
        return $this->hasOne(Categoria::class, 'id', 'categoria_id');
    }

    public function subcategoria()
    {
        return $this->hasOne(SubCategoria::class, 'id', 'subcategoria_id');
    }

    public function recebido()
    {
        return $this->hasOne(Contato::class, 'id', 'recebido_de');
    }

    public function pago()
    {
        return $this->hasOne(Contato::class, 'id', 'pago_a');
    }
}
