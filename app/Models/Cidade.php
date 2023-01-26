<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    public $timestamp = true;

    protected $table = "cidades";

    protected $fillable = [
        'nm_cidade', 'estado_id'
    ];
}
