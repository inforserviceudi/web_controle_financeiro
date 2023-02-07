<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContatos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contatos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('empresa_id');
            $table->integer('tp_contato'); /// 1 - CLIENTE / 2 - FORNCEDEDOR / 3 - FUNCIONARIO
            $table->string('nm_fantasia', 150);
            $table->string('rz_social', 150);
            $table->string('tp_pessoa', 1); /// F - FISICA / J - JURIDICA
            $table->string('cpf_cnpj', 18)->nullable();
            $table->string('ds_insc_estadual', 50)->nullable();
            $table->string('ds_email', 100)->nullable();
            $table->string('ds_telefone', 14)->nullable();
            $table->string('ds_celular', 15)->nullable();
            $table->string('nm_contato_emp', 150)->nullable();
            $table->string('ds_endereco', 150)->nullable();
            $table->string('nr_endereco', 8)->nullable();
            $table->string('ds_complemento', 50)->nullable();
            $table->string('ds_bairro', 50)->nullable();
            $table->string('ds_cep', 9)->nullable();
            $table->integer('estado_id')->nullable();
            $table->integer('cidade_id')->nullable();
            $table->text('ds_descricao', 500)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contatos');
    }
}
