<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategoria extends Model
{
    public $timestamp = true;

    protected $table = "subcategorias";

    protected $fillable = [
        'empresa_id', 'categoria_id', 'nome'
    ];

    public function empresa()
    {
        return $this->hasOne(Categoria::class, "id", "empresa_id");
    }

    public function categoria()
    {
        return $this->hasOne(Categoria::class, "id", "categoria_id");
    }
}
