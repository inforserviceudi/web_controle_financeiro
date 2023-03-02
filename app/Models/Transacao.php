<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
