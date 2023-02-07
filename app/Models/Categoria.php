<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public $timestamp = true;

    protected $table = "categorias";

    protected $fillable = [
        'nome'
    ];
}
