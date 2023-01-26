<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('empresa_id');
            $table->string('ds_conta', 150);
            $table->string('ds_conta_principal', 1)->default("N");  /// S - SIM / N - NÃO
            $table->float('vr_saldo_inicial')->default(0);
            $table->string('tp_saldo_inicial', 1)->nullable("P"); /// P - POSITIVO / N - NEGATIVO
            $table->integer('tipo_conta_id');

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
        Schema::dropIfExists('contas');
    }
}
