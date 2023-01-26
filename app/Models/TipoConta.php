<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoConta extends Model
{
    public $timestamp =true;

    protected $table = "tipos_contas";

    protected $fillable = [
        'tp_conta'
    ];
}
