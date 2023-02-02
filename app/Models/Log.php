<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $timestamp =true;

    protected $table = "logs";

    protected $fillable = [
        'empresa_id', 'usuario_id', 'ds_acao', 'ds_mensagem'
    ];

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'id', 'empresa_id');
    }

    public function usuario()
    {
        return $this->hasOne('App\User', 'id', 'usuario_id');
    }
}
