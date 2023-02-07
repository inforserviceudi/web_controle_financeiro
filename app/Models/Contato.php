<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    public $timestamps = true;

    protected $table = "contatos";

    protected $fillable = [
        'empresa_id', 'tp_contato', 'nm_fantasia', 'rz_social', 'tp_pessoa', 'cpf_cnpj', 'ds_insc_estadual', 'ds_email',
        'ds_telefone', 'ds_celular', 'nm_contato_emp', 'ds_endereco', 'nr_endereco', 'ds_complemento', 'ds_bairro',
        'ds_cep', 'estado_id', 'cidade_id', 'ds_descricao'
    ];

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'id', 'empresa_id');
    }

    public function estado()
    {
        return $this->hasOne(Estado::class, 'id', 'estado_id');
    }

    public function cidade()
    {
        return $this->hasOne(Cidade::class, 'id', 'cidade_id');
    }
}
