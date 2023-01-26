<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    public $timestamp = true;

    protected $table = "contas";

    protected $fillable = [
        'empresa_id', 'ds_conta', 'ds_conta_principal', 'vr_saldo_inicial', 'tp_saldo_inicial', 'tipo_conta_id'
    ];

    public function empresa()
    {
        return $this->hasOne(Empresa::class, "id", "empresa_id");
    }

    public function tipo_conta()
    {
        return $this->hasOne(TipoConta::class, "id", "tipo_conta_id");
    }
}
