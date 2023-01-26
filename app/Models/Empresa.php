<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    public $timestamp =true;

    protected $table = "empresas";

    protected $fillable = [
        'empresa_principal', 'nm_empresa', 'ds_cpf_cnpj', 'estado_id', 'cidade_id', 'vr_saldo_inicial', 'tp_saldo_inicial',
        'qt_funcionarios', 'ds_logomarca'
    ];

    public function estado()
    {
        return $this->hasOne(Estado::class, "id", "estado_id");
    }

    public function cidade()
    {
        return $this->hasOne(Estado::class, "id", "cidade_id");
    }
}
