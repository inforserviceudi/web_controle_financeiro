<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    public $timestamp = true;

    protected $table = "estados";

    protected $fillable = [
        'ds_sigla', 'nm_estado'
    ];
}
