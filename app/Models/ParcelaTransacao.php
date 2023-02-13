<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcelaTransacao extends Model
{
    public $timestamp = true;

    protected $table = "parcelas_transacoes";

    protected $fillable = [
        'transacao_id', 'nr_parcela', 'vr_parcela', 'dt_vencimento', 'dt_pagamento', 'ds_pago',
    ];
}
