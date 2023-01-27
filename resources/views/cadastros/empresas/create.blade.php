@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Empresas</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li>
                    <a href="{{ route('empresas.index') }}">
                        <span>Empresas</span>
                    </a>
                </li>
                <li><span>Nova Empresa</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="row mt-xl">
        <div class="col-md-12 text-right">
            <a href="{{ route('empresas.index') }}" type="button" class="btn btn-default btn-sm">
                Voltar
            </a>
        </div>
        <div class="col-md-8 col-md-offset-2">
            <section class="panel panel-featured">
                <header class="panel-heading" style="display: flex; justify-content: space-between;">
                    <h2 class="panel-title">Informe alguns dados de seu negócio!</h2>
                    <p><code> <sup class="text-bold">*</sup> Obrigatório</code></p>
                </header>
                <div class="panel-body">
                    <form id="form-nova-empresa" action="{{ route('empresas.insere.registro') }}" method="post" class="form">
                        <div class="row form-group">
                            <div class="col-md-8">
                                <label for="nm_empresa" class="text-bold">Nome da empresa <sup class="text-danger text-bold">*</sup> </label>
                                <input type="text" name="nm_empresa" id="nm_empresa" class="form-control" maxlength="150" required>
                            </div>
                            <div class="col-md-4">
                                <label for="nm_empresa" class="text-bold">Cpf ou Cnpj</label>
                                <div class="input-group mb-md">
                                    <div class="input-group-btn">
                                        <select id="tp_doc" class="form-control" style="width: 70px;">
                                            <option value="cnpj"> CNPJ </option>
                                            <option value="cpf"> CPF </option>
                                        </select>
                                    </div>
                                    <input type="text" name="ds_cpf_cnpj" id="ds_cpf_cnpj" class="form-control mask-cnpj" placeholder="00.000.000/0000-00" maxlength="18">
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label for="estado_id" class="text-bold">Estado</label>
                                <select name="estado_id" id="estado_id" class="form-control js-single">
                                    <option value="0" selected disabled>SELECIONE ...</option>
                                    @foreach ($estados as $item)
                                        <option value="{{ $item->id }}">{{ $item->ds_sigla }} - {{ $item->nm_estado }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="cidade_id" class="text-bold">Cidade</label>
                                <select name="cidade_id" id="cidade_id" class="form-control js-single">
                                    <option value="0" selected disabled>SELECIONE ...</option>
                                    @foreach ($cidades as $item)
                                        <option value="{{ $item->id }}">{{ $item->nm_cidade }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label for="vr_saldo_inicial" class="text-bold">Saldo inicial em conta</label>
                                <div class="input-group mb-md">
                                    <span class="input-group-addon"> R$ </span>
                                    <input type="text" id="vr_saldo_inicial" name="vr_saldo_inicial" class="form-control mask-valor" placeholder="000,00" maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row mt-xl">
                                    <div class="col-md-12">
                                        <label class="checkbox-inline">
                                            <input type="radio" id="tp_saldo_inicial1" name="tp_saldo_inicial" value="P"> Positivo
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="radio" id="tp_saldo_inicial2"  name="tp_saldo_inicial"value="N"> Negativo
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="radio" id="tp_saldo_inicial3" name="tp_saldo_inicial" value="Z" checked> Saldo zerado <small>(ou não desejo informar ainda)</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label for="vr_saldo_inicial" class="text-bold">Qtd de funcionários</label>
                                <input type="number" id="vr_saldo_inicial" name="vr_saldo_inicial" class="spinner-input form-control" value="0" min="0" max="1000" maxlength="4">
                                <p>
                                    <code>máximo permitido: 1000</code>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label for="vr_saldo_inicial" class="text-bold">Coloque a marca da sua empresa</label>
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="input-append">
                                        <div class="uneditable-input">
                                            <span class="fileupload-preview"></span>
                                        </div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileupload-exists">Mudar</span>
                                            <span class="fileupload-new">Selecionar</span>
                                            <input type="file" name="arquivo" id="arquivo"/>
                                        </span>
                                        <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remover</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('empresas.index') }}" type="button" class="btn btn-default btn-sm">
                                    Voltar
                                </a>
                                <button type="button" class="btn btn-success btn-sm btn-spin" title="Salvar registro" onclick="submitForm('form-nova-empresa')">
                                    <i class="fa fa-check fa-fw"></i>
                                    Salvar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>
@endsection
