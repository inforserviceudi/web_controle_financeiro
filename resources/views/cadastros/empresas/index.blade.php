@extends('layouts.app')

@section('content')
<style>
    section.toggle label,
    section.toggle form{
        color: #393A3D;
        background-color: #FDFDFD;
    }
    form.form{
        padding: 15px;
    }
</style>
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
                <li><span>Empresas</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('empresas.criar.registro') }}" type="button" class="btn btn-default btn-sm mt-xl">
                <i class="fa fa-plus fa-fw"></i>
                Cadastrar nova empresa
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            @foreach ( $empresas as $empresa )
            {{-- <a href="{{ route('empresas.visualizar.selecionado', ['id'=>$empresa->id]) }}" style="text-decoration: none;"> --}}
            <form id="form-empresa-{{ $empresa->id }}" action="{{ route('empresas.sistema.geral') }}" method="post">@csrf
                <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
                <a style="text-decoration: none;" onclick="$('#form-empresa-{{ $empresa->id }}').submit();">
                    <section class="panel panel-featured-left panel-featured-default mt-lg">
                        <div class="panel-body">
                            <div class="widget-summary widget-summary-sm">
                                <div class="widget-summary-col widget-summary-col-icon">
                                    <div class="summary-icon bg-default">
                                        <img class="summary-img" src="{{ asset('images/empresas/'.$empresa->ds_logomarca) }}" alt="{{ $empresa->nm_empresa }}">
                                    </div>
                                </div>
                                <div class="widget-summary-col">
                                    <div class="summary">
                                        <h4 class="title">{{ $empresa->nm_empresa }}</h4>
                                        <div class="info" style="display: flex; justify-content: space-between">
                                            <strong class="amount">Saldo: R$ {{ number_format($empresa->vr_saldo_inicial, 2, ',', '.') }}</strong>
                                            @if ( $empresa->empresa_principal === "S" )
                                                <span class="text-primary">(Principal)</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </a>
            </form>
            @endforeach
        </div>
        <div class="col-md-8">
            <div class="toggle" data-plugin-toggle="">
                <section class="toggle">
                    <label>
                        <span>Dados da empresa</span>
                        <ul>
                            <li><strong>Nome:</strong> {{ $emp_principal->nm_empresa }}</li>
                            <li><strong>Cnpj / Cpf:</strong> {{ $emp_principal->ds_cpf_cnpj }}</li>
                        </ul>
                    </label>
                    <div class="toggle-content" style="display: none;">
                        <form id="form-atualiza-empresa" action="{{ route('empresas.atualiza.registro', ['id'=>$emp_principal->id]) }}" method="post" class="form">
                            <div class="row form-group">
                                <div class="col-md-8">
                                    <label for="nm_empresa" class="text-bold">Nome da empresa <sup class="text-danger text-bold">*</sup> </label>
                                    <input type="text" name="nm_empresa" id="nm_empresa" class="form-control" value="{{ $emp_principal->nm_empresa }}" maxlength="150" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="nm_empresa" class="text-bold">Cpf ou Cnpj</label>
                                    <div class="input-group mb-md">
                                        <div class="input-group-btn">
                                            <select id="tp_doc" class="form-control" style="width: 70px;">
                                                <option value="cnpj" {{ $ds_cpf_cnpj === "cnpj" ? "selected" : "" }}> CNPJ </option>
                                                <option value="cpf" {{ $ds_cpf_cnpj === "cpf" ? "selected" : "" }}> CPF </option>
                                            </select>
                                        </div>
                                        <input type="text" name="ds_cpf_cnpj" id="ds_cpf_cnpj" class="form-control {{ $ds_cpf_cnpj === "cnpj" ? "mask-cnpj" : "mask-cpf" }}" value="{{ $emp_principal->ds_cpf_cnpj }}" placeholder="{{ $ds_cpf_cnpj === "cnpj" ? "00.000.000/0000-00" : "000.000.000-00" }}" maxlength="18">
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="estado_id" class="text-bold">Estado</label>
                                    <select name="estado_id" id="estado_id" class="form-control">
                                        <option value="0" selected disabled>SELECIONE ...</option>
                                        @foreach ($estados as $item)
                                            <option value="{{ $item->id }}" {{ $item->id === $emp_principal->estado_id ? "selected" : "" }}>{{ $item->ds_sigla }} - {{ $item->nm_estado }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="cidade_id" class="text-bold">Cidade</label>
                                    <select name="cidade_id" id="cidade_id" class="form-control">
                                        <option value="0" selected disabled>SELECIONE ...</option>
                                        @foreach ($cidades as $item)
                                            <option value="{{ $item->id }}" {{ $item->id === $emp_principal->cidade_id ? "selected" : "" }}>{{ $item->nm_cidade }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label for="vr_saldo_inicial" class="text-bold">Saldo inicial em conta</label>
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon"> R$ </span>
                                        <input type="text" id="vr_saldo_inicial" name="vr_saldo_inicial" class="form-control mask-valor" value="{{ number_format($emp_principal->vr_saldo_inicial, 2, ',', '.') }}" placeholder="000,00" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row mt-xl">
                                        <div class="col-md-12">
                                            <label class="checkbox-inline">
                                                <input type="radio" id="tp_saldo_inicial1" name="tp_saldo_inicial" value="P" {{ $emp_principal->tp_saldo_inicial === "P" ? "checked" : "" }}> Positivo
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="radio" id="tp_saldo_inicial2"  name="tp_saldo_inicial"value="N" {{ $emp_principal->tp_saldo_inicial === "N" ? "checked" : "" }}> Negativo
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="radio" id="tp_saldo_inicial3" name="tp_saldo_inicial" value="Z" {{ $emp_principal->tp_saldo_inicial === "Z" ? "checked" : "" }}> Saldo zerado <small>(ou não desejo informar ainda)</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label for="qt_funcionarios" class="text-bold">Qtd de funcionários</label>
                                    <input type="number" id="qt_funcionarios" name="qt_funcionarios" class="spinner-input form-control" value="{{ $emp_principal->qt_funcionarios }}" min="0" max="1000" maxlength="4">
                                    <p>
                                        <code>máximo permitido: 1000</code>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="text-bold">Coloque a marca da sua empresa</label>
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
                                    <button type="button" class="btn btn-success btn-sm btn-spin-pencil btn-atualiza-empresa" title="Atualizar registro" onclick="submitForm('form-atualiza-empresa')">
                                        Atualizar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>

            @if( $emp_principal->empresa_principal === "N" )
            <section class="panel panel-featured-left panel-featured-primary mt-lg" style="height: 80px;">
                <div class="panel-body" style="height: 80px;">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Empresa principal</h4>
                                <div class="info" style="display:flex; justify-content:space-between;">
                                    <p>Ao confirmar, esta será sua empresa principal.</p>
                                    <a type="button" href="{{ route('empresas.principal', ['id'=>$emp_principal->id]) }}" class="btn btn-link text-bold">Tornar esta empresa como principal</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @endif

            <section class="panel panel-featured-left panel-featured-primary mt-lg" style="height: 80px;">
                <div class="panel-body" style="height: 80px;">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Permissões de acesso</h4>
                                <div class="info" style="display:flex; justify-content:space-between;">
                                    <p>Você tem 0 usuário(s) com permissão de acesso.</p>
                                    <a href="" class="btn btn-link text-bold">Adicione usuários e gerencie permissões.</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <a class="mb-xs mr-xs modal-basic btn btn-danger btn-sm text-bold" href="#modalNoTitle">Excluir esta empresa</a>

            <div id="modalNoTitle" class="modal-block mfp-hide">
                <section class="panel">
                    <div class="panel-body">
                        <div class="modal-wrapper">
                            <div class="modal-text">
                                <h3>Deseja excluir esta empresa?</h3>
                            </div>
                        </div>
                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a type="button" href="{{ route('empresas.remove.registro', ['id'=>$emp_principal->id]) }}" class="btn btn-primary btn-sm">Confirmar</a>
                                <button class="btn btn-default btn-sm modal-dismiss">Cancelar</button>
                            </div>
                        </div>
                    </footer>
                </section>
            </div>
        </div>
    </div>
</section>
@endsection
