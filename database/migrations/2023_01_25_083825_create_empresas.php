<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('empresa_principal', 1)->default("N");     /// S - SIM / N - NAO
            $table->string('empresa_selecionada', 1)->default("N");     /// S - SIM / N - NAO
            $table->string('nm_empresa', 150);
            $table->string('ds_cpf_cnpj', 18)->nullable();
            $table->integer('estado_id')->nullable();
            $table->integer('cidade_id')->nullable();
            $table->float('vr_saldo_inicial')->default(0);
            $table->string('tp_saldo_inicial', 1)->nullable();  /// P - POSITIVO / N - NEGATIVO / Z - ZERADO
            $table->integer('qt_funcionarios')->nullable();
            $table->string('ds_logomarca', 150)->nullable();        /// LOGOMARCA DA EMPRESA

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
        Schema::dropIfExists('empresas');
    }
}
