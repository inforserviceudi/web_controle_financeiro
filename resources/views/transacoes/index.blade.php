@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <style>
        .btn-despesas{
            border-color: #ccc;
            border-radius: 50%;
            padding: 1%;
            margin-left: 3%;
            font-size: 10px;
            background-color: #ccc;
            color: #F5F5F5;
        }
        .open > .dropdown-menu {
            display: block;
            width: 350px;
        }
        .box-despesas{
            margin: 0 15px;
        }
        .box-despesas .box-despesas-titulo{
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .box-despesas .box-despesas-resumo{
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
        }

        .box-despesas .box-despesas-resumo ul li{
            font-size: 11px;
            font-weight: 700;
        }
        .dropdown-transaction {
            left: -118px !important;
            min-width: 100px !important;
            top: -4px;
        }
        .open > .dropdown-transaction {
            width: 115px;
        }
        .bg-gray{ background-color: #eeeeee; }
    </style>
    <header class="page-header">
        <h2>Transações</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Transações</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="panel mt-lg">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="text-uppercase">resultado previsto para o mês</h5>
                    <span style="font-weight:bold; font-size: 2em;">R$ {{ number_format($previsto_mes, 2, ',', '.') }}</span>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-uppercase">recebimentos</h6>
                            <p>Recebido: <span class="text-success text-bold">R$ {{ number_format($recebimento_pago, 2, ',', '.') }}</span></p>
                            <p>Previsto: <span class="text-bold">R$ {{ number_format($recebimentos, 2, ',', '.') }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase">
                                despesas
                                <button tabindex="-1" data-toggle="dropdown" class="btn btn-link dropdown-toggle btn-despesas" type="button" aria-expanded="false">
                                    <span class="fa fa-plus fa-fw"></span>
                                </button>
                                <ul role="menu" class="dropdown-menu pull-right">
                                    <li>
                                        <h5 class="text-center text-bold mb-lg">
                                            Despesas de
                                            {{ \Carbon\Carbon::today()->monthName }} de
                                            {{ \Carbon\Carbon::today()->format('Y') }}
                                        </h5>
                                    </li>
                                    <li>
                                        <div class="box-despesas">
                                            <p class="box-despesas-titulo">Despesas fixas</p>
                                            <div class="box-despesas-resumo">
                                                <ul>
                                                    <li>Pago:</li>
                                                    <li>R$ {{ number_format($desp_fixo_pago, 2, ',', '.') }}</li>
                                                </ul>
                                                <ul>
                                                    <li>Previsto:</li>
                                                    <li>R$ {{ number_format($desp_fixo, 2, ',', '.') }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <div class="box-despesas">
                                            <p class="box-despesas-titulo">Despesas variáveis</p>
                                            <div class="box-despesas-resumo">
                                                <ul>
                                                    <li>Pago:</li>
                                                    <li>R$ {{ number_format($desp_variavel_pago, 2, ',', '.') }}</li>
                                                </ul>
                                                <ul>
                                                    <li>Previsto:</li>
                                                    <li>R$ {{ number_format($desp_variavel, 2, ',', '.') }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <div class="box-despesas">
                                            <p class="box-despesas-titulo">Pessoas</p>
                                            <div class="box-despesas-resumo">
                                                <ul>
                                                    <li>Pago:</li>
                                                    <li>R$ {{ number_format($desp_pessoas_pago, 2, ',', '.') }}</li>
                                                </ul>
                                                <ul>
                                                    <li>Previsto:</li>
                                                    <li>R$ {{ number_format($desp_pessoas, 2, ',', '.') }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <div class="box-despesas">
                                            <p class="box-despesas-titulo">Impostos</p>
                                            <div class="box-despesas-resumo">
                                                <ul>
                                                    <li>Pago:</li>
                                                    <li>R$ {{ number_format($desp_impostos_pago, 2, ',', '.') }}</li>
                                                </ul>
                                                <ul>
                                                    <li>Previsto:</li>
                                                    <li>R$ {{ number_format($desp_impostos, 2, ',', '.') }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </h6>
                            <p style="margin-top: -4%;">Pago: <span class="text-danger text-bold">R$ {{ number_format($despesa_pago, 2, ',', '.') }}</span></p>
                            <p>Previsto: <span class="text-bold">R$ {{ number_format($despesas, 2, ',', '.') }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5"></div>
                <div class="col-md-3">
                    <h5 class="text-uppercase">conta</h5>
                    <a tabindex="-1" data-toggle="dropdown" class="btn btn-link dropdown-toggle text-bold text-default" type="button" aria-expanded="false" style="color: #777777;">
                        {{ $contaP ? $contaP->ds_conta : 'Todas as contas' }}
                        <span class="caret"></span>
                    </a>
                    <ul role="menu" class="dropdown-menu pull-right" style="margin-top: -44%;">
                        @foreach ( $contas as $conta )
                        <li>
                            <form id="form-conta-{{ $conta->id }}" action="{{ route('transacoes.seleciona.conta') }}" method="post">@csrf
                                <input type="hidden" name="empresa_id" value="{{ $empresa_id }}">
                                <input type="hidden" name="conta_id" value="{{ $conta->id }}">
                                <input type="hidden" name="mes_transacao" id="mes_selecionado" value="">
                                <div class="row" onclick="trocaContas('#form-conta-{{ $conta->id }}')" style="cursor: pointer; margin: 3px 5px;">
                                    <div class="col-md-7" style="font-size: 12px;">{{ $conta->ds_conta }}</div>
                                    <div class="col-md-5 text-bold">R$ {{ number_format( $conta->vr_saldo_inicial, 2, ',', '.') }}</div>
                                </div>
                            </form>
                        </li>
                        <li class="divider"></li>
                        @endforeach
                        <li>
                            <form id="form-conta-0" action="{{ route('transacoes.seleciona.conta') }}" method="post">@csrf
                                <input type="hidden" name="empresa_id" value="{{ $empresa_id }}">
                                <input type="hidden" name="conta_id" value="0">
                                <input type="hidden" name="mes_transacao" id="mes_selecionado" value="">
                                <div class="row" onclick="trocaContas('#form-conta-0')" style="cursor: pointer; margin: 3px 5px;">
                                    <div class="col-md-12 text-center">Todas as contas</div>
                                </div>
                            </form>
                        </li>
                    </ul>
                    <br>
                    <span style="font-weight:bold; font-size: 1.75em;">R$ {{ $contaP ? number_format( $contaP->vr_saldo_inicial, 2, ',', '.') : number_format( $saldo_total_conta, 2, ',', '.') }}</span>
                    <br>
                    {{-- SOMAR O SALDO INICIAL DA CONTA MAIS A DIFERENÇA DO PREVISTO DO MÊS E O RECEBIDO DO MÊS --}}
                    <p class="mt-sm">Previsão de fechamento do mês <br> <span class="text-bold">R$ 11.222,33</span> </p>
                    <a href="{{ route('contas.index') }}" class="btn btn-default btn-md mt-sm">
                        <i class="fa fa-plus fa-fw" aria-hidden="true"></i>
                        <span>Adicionar conta</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row mb-sm">
                <div class="col-md-2">
                    <form id="form-mes-transacao" action="{{ route('transacoes.seleciona.conta') }}" method="post">@csrf
                        <input type="hidden" name="empresa_id" value="{{ $empresa_id }}">
                        <input type="hidden" name="conta_id" id="id_conta" value="{{ $contaP ? $contaP->id : 0 }}">
                        <select name="mes_transacao" id="mes_transacao" class="form-control" onchange="this.form.submit()">
                            @for ($i = 0; $i < 12; $i++)
                                <option value="{{ $i+1 }}" {{ ($i+1) === intval($mes_atual) ? 'selected' : '' }}>
                                    {{ $meses[$i] .' '. $ano_atual }}
                                </option>
                            @endfor
                        </select>
                    </form>
                </div>
            </div>
            <div class="tabs">
                <ul class="nav nav-tabs">
                    @foreach ($categorias as $categoria)
                        @if( (strtolower(Auth::user()->permissao) === "admin" ||
                             (strtolower(Auth::user()->permissao) === "user" && $param->ver_aba_recebimento === "S")) &&
                             $categoria->id === 1)
                            <li class="{{ $categoria->id === 1 ? 'active' : '' }}">
                                <a href="#{{ strtolower(str_replace(' ', '_', tirarAcentos($categoria->nome))) }}" data-toggle="tab" aria-expanded="false">
                                    {{ strtoupper($categoria->nome) }}
                                </a>
                            </li>
                        @elseif( (strtolower(Auth::user()->permissao) === "admin" ||
                            (strtolower(Auth::user()->permissao) === "user" && $param->ver_aba_despfixa === "S")) &&
                            $categoria->id === 2)
                           <li class="{{ $categoria->id === 1 ? 'active' : '' }}">
                               <a href="#{{ strtolower(str_replace(' ', '_', tirarAcentos($categoria->nome))) }}" data-toggle="tab" aria-expanded="false">
                                   {{ strtoupper($categoria->nome) }}
                               </a>
                           </li>
                        @elseif( (strtolower(Auth::user()->permissao) === "admin" ||
                            (strtolower(Auth::user()->permissao) === "user" && $param->ver_aba_despvariavel === "S")) &&
                            $categoria->id === 3)
                           <li class="{{ $categoria->id === 1 ? 'active' : '' }}">
                               <a href="#{{ strtolower(str_replace(' ', '_', tirarAcentos($categoria->nome))) }}" data-toggle="tab" aria-expanded="false">
                                   {{ strtoupper($categoria->nome) }}
                               </a>
                           </li>
                        @elseif( (strtolower(Auth::user()->permissao) === "admin" ||
                            (strtolower(Auth::user()->permissao) === "user" && $param->ver_aba_pessoas === "S")) &&
                            $categoria->id === 4)
                           <li class="{{ $categoria->id === 1 ? 'active' : '' }}">
                               <a href="#{{ strtolower(str_replace(' ', '_', tirarAcentos($categoria->nome))) }}" data-toggle="tab" aria-expanded="false">
                                   {{ strtoupper($categoria->nome) }}
                               </a>
                           </li>
                        @elseif( (strtolower(Auth::user()->permissao) === "admin" ||
                            (strtolower(Auth::user()->permissao) === "user" && $param->ver_aba_impostos === "S")) &&
                            $categoria->id === 5)
                           <li class="{{ $categoria->id === 1 ? 'active' : '' }}">
                               <a href="#{{ strtolower(str_replace(' ', '_', tirarAcentos($categoria->nome))) }}" data-toggle="tab" aria-expanded="false">
                                   {{ strtoupper($categoria->nome) }}
                               </a>
                           </li>
                        @endif
                    @endforeach
                    @if( strtolower(Auth::user()->permissao) === "admin" ||
                        (strtolower(Auth::user()->permissao) === "user" && $param->ver_aba_transferencia === "S"))
                        <li class="">
                            <a href="#transferencia" data-toggle="tab" aria-expanded="true">
                                {{ strtoupper('Transferências') }}
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content">
                    @foreach ($categorias as $categoria)
                        @php
                            $vr_total = \App\Models\Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
                                ->where('transacoes.empresa_id', $empresa_id)
                                ->where('categoria_id', $categoria->id)
                                ->where('transacoes.conta_id', $conta_id)
                                ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
                                ->sum('parcelas_transacoes.vr_parcela');
                            $vr_pago = \App\Models\Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
                                ->where('transacoes.empresa_id', $empresa_id)
                                ->where('categoria_id', $categoria->id)
                                ->where('transacoes.conta_id', $conta_id)
                                ->where('parcelas_transacoes.ds_pago', "S")
                                ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
                                ->sum('parcelas_transacoes.vr_parcela');
                            $vr_apagar = \App\Models\Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
                                ->where('transacoes.empresa_id', $empresa_id)
                                ->where('categoria_id', $categoria->id)
                                ->where('transacoes.conta_id', $conta_id)
                                ->where('parcelas_transacoes.ds_pago', "N")
                                ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
                                ->sum('parcelas_transacoes.vr_parcela');
                        @endphp

                        @if( (strtolower(Auth::user()->permissao) === "admin" ||
                            (strtolower(Auth::user()->permissao) === "user" && $param->ver_aba_recebimento === "S")) &&
                            $categoria->id === 1)
                            <div id="{{ strtolower(str_replace(' ', '_', tirarAcentos($categoria->nome))) }}" class="tab-pane {{ $categoria->id === 1 ? 'active' : '' }}">
                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                    (strtolower(Auth::user()->permissao) === "user" && $param->incluir_movimentacao_recebimento === "S"))
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button id="btn_novo_registro" class="btn btn-default btn-sm mt-sm mb-sm modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#0" data-width="modal-lg" data-url="{{ route("transacoes.modal.create-edit") }}" title="Novo registro">
                                                <i class="fa fa-plus fa-fw"></i>
                                                Novo registro
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Descrição</th>
                                                <th>{{ $categoria->id === 1 ? 'Recebido de' : 'Pago a' }}</th>
                                                <th>Valor</th>
                                                <th>Categoria</th>
                                                <th>Pagamento</th>
                                                <th>Parcela</th>
                                                <th>Pago</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($transacoes->where('categoria_id', $categoria->id) as $transacao)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($transacao->dt_vencimento)->format('d/m/Y') }}</td>
                                                    <td>{{ $transacao->descricao }}</td>
                                                    <td>{{ $categoria->id === $transacao->categoria_id && $transacao->categoria_id === 1 ? $transacao->recebido->rz_social : $transacao->pago->rz_social }}</td>
                                                    <td>R$ {{ number_format($transacao->vr_parcela, 2, ',', '.') }}</td>
                                                    <td>{{ $transacao->subcategoria->nome }}</td>
                                                    <td>
                                                        @switch($transacao->tipo_pagamento)
                                                            @case('V')
                                                                <span>À VISTA</span>
                                                                @break
                                                            @case('P')
                                                                <span>PARCELADO</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $transacao->nr_parcela }} / {{ \App\Models\ParcelaTransacao::where('transacao_id', $transacao->id)->count('transacao_id') }}</th>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" name="ds_pago" data-plugin-ios-switch
                                                                {{ $transacao->ds_pago === 'S' ? 'checked' : '' }}
                                                                onclick="informarPagamento('{{ route('transacoes.informar.pagamento') }}', null, '{{ $transacao->parc_transacao_id }}', true);"/>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <div class="input-group-btn">
                                                            <button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="true">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul role="menu" class="dropdown-menu dropdown-transaction pull-right">
                                                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                                                    (strtolower(Auth::user()->permissao) === "user" && $param->alterar_movimentacao_recebimento === "S"))
                                                                    <li>
                                                                        <button type="button" class="btn btn-link btn-sm text-info modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#{{ $transacao->id }}" data-width="modal-lg" data-url="{{ route('transacoes.modal.create-edit') }}" title="Editar informações">
                                                                            <i class="fa fa-pencil fa-fw"></i>
                                                                            Editar
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <button type="button" class="btn btn-link btn-sm text-primary modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#{{ $transacao->id }}" data-width="modal-md" data-url="{{ route('transacoes.modal.parcelas') }}" title="Ver parcelas">
                                                                            <i class="fa fa-list fa-fw"></i>
                                                                            Parcelas
                                                                        </button>
                                                                    </li>
                                                                @endif
                                                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                                                    (strtolower(Auth::user()->permissao) === "user" && $param->excluir_movimentacao_recebimento === "S"))
                                                                    <li>
                                                                        <button class="btn btn-link btn-sm text-danger modal-call" data-id="{{ $transacao->id }}" data-width="modal-md" data-url="{{ route('transacoes.modal.delete') }}" title="Remover registro">
                                                                            <i class="fa fa-trash-o fa-fw"></i>
                                                                            Remover
                                                                        </button>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-bold">Nenhuma transação encontrada !!!</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                               </div>

                                <div class="row bg-gray">
                                    <div class="col-md-12">
                                        <p>Pago: <span class="text-bold">R$ {{ number_format($vr_pago, 2, ',', '.') }}</span></p>
                                        <p>A pagar: <span class="text-bold">R$ {{ number_format($vr_apagar, 2, ',', '.') }}</span></p>
                                        <p>Total: <span class="text-bold">R$ {{ number_format($vr_total, 2, ',', '.') }}</span></p>
                                    </div>
                                </div>
                            </div>
                        @elseif( (strtolower(Auth::user()->permissao) === "admin" ||
                            (strtolower(Auth::user()->permissao) === "user" && $param->ver_aba_despfixa === "S")) &&
                            $categoria->id === 2)
                            <div id="{{ strtolower(str_replace(' ', '_', tirarAcentos($categoria->nome))) }}" class="tab-pane {{ $categoria->id === 1 ? 'active' : '' }}">
                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                    (strtolower(Auth::user()->permissao) === "user" && $param->incluir_movimentacao_despfixa === "S"))
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button id="btn_novo_registro" class="btn btn-default btn-sm mt-sm mb-sm modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#0" data-width="modal-lg" data-url="{{ route("transacoes.modal.create-edit") }}" title="Novo registro">
                                                <i class="fa fa-plus fa-fw"></i>
                                                Novo registro
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Descrição</th>
                                                <th>{{ $categoria->id === 1 ? 'Recebido de' : 'Pago a' }}</th>
                                                <th>Valor</th>
                                                <th>Categoria</th>
                                                <th>Pagamento</th>
                                                <th>Parcela</th>
                                                <th>Pago</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($transacoes->where('categoria_id', $categoria->id) as $transacao)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($transacao->dt_vencimento)->format('d/m/Y') }}</td>
                                                    <td>{{ $transacao->descricao }}</td>
                                                    <td>{{ $categoria->id === $transacao->categoria_id && $transacao->categoria_id === 1 ? $transacao->recebido->rz_social : $transacao->pago->rz_social }}</td>
                                                    <td>R$ {{ number_format($transacao->vr_parcela, 2, ',', '.') }}</td>
                                                    <td>{{ $transacao->subcategoria->nome }}</td>
                                                    <td>
                                                        @switch($transacao->tipo_pagamento)
                                                            @case('V')
                                                                <span>À VISTA</span>
                                                                @break
                                                            @case('P')
                                                                <span>PARCELADO</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $transacao->nr_parcela }} / {{ \App\Models\ParcelaTransacao::where('transacao_id', $transacao->id)->count('transacao_id') }}</th>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" name="ds_pago" data-plugin-ios-switch
                                                                {{ $transacao->ds_pago === 'S' ? 'checked' : '' }}
                                                                onclick="informarPagamento('{{ route('transacoes.informar.pagamento') }}', null, '{{ $transacao->parc_transacao_id }}', true);"/>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <div class="input-group-btn">
                                                            <button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="true">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul role="menu" class="dropdown-menu dropdown-transaction pull-right">
                                                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                                                    (strtolower(Auth::user()->permissao) === "user" && $param->alterar_movimentacao_despfixa === "S"))
                                                                    <li>
                                                                        <button type="button" class="btn btn-link btn-sm text-info modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#{{ $transacao->id }}" data-width="modal-lg" data-url="{{ route('transacoes.modal.create-edit') }}" title="Editar informações">
                                                                            <i class="fa fa-pencil fa-fw"></i>
                                                                            Editar
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <button type="button" class="btn btn-link btn-sm text-primary modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#{{ $transacao->id }}" data-width="modal-md" data-url="{{ route('transacoes.modal.parcelas') }}" title="Ver parcelas">
                                                                            <i class="fa fa-list fa-fw"></i>
                                                                            Parcelas
                                                                        </button>
                                                                    </li>
                                                                @endif
                                                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                                                    (strtolower(Auth::user()->permissao) === "user" && $param->excluir_movimentacao_despfixa === "S"))
                                                                    <li>
                                                                        <button class="btn btn-link btn-sm text-danger modal-call" data-id="{{ $transacao->id }}" data-width="modal-md" data-url="{{ route('transacoes.modal.delete') }}" title="Remover registro">
                                                                            <i class="fa fa-trash-o fa-fw"></i>
                                                                            Remover
                                                                        </button>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-bold">Nenhuma transação encontrada !!!</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                               </div>

                                <div class="row bg-gray">
                                    <div class="col-md-12">
                                        <p>Pago: <span class="text-bold">R$ {{ number_format($vr_pago, 2, ',', '.') }}</span></p>
                                        <p>A pagar: <span class="text-bold">R$ {{ number_format($vr_apagar, 2, ',', '.') }}</span></p>
                                        <p>Total: <span class="text-bold">R$ {{ number_format($vr_total, 2, ',', '.') }}</span></p>
                                    </div>
                                </div>
                            </div>
                        @elseif( (strtolower(Auth::user()->permissao) === "admin" ||
                            (strtolower(Auth::user()->permissao) === "user" && $param->ver_aba_despvariavel === "S")) &&
                            $categoria->id === 3)
                            <div id="{{ strtolower(str_replace(' ', '_', tirarAcentos($categoria->nome))) }}" class="tab-pane {{ $categoria->id === 1 ? 'active' : '' }}">
                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                    (strtolower(Auth::user()->permissao) === "user" && $param->incluir_movimentacao_despvariavel === "S"))
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button id="btn_novo_registro" class="btn btn-default btn-sm mt-sm mb-sm modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#0" data-width="modal-lg" data-url="{{ route("transacoes.modal.create-edit") }}" title="Novo registro">
                                                <i class="fa fa-plus fa-fw"></i>
                                                Novo registro
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Descrição</th>
                                                <th>{{ $categoria->id === 1 ? 'Recebido de' : 'Pago a' }}</th>
                                                <th>Valor</th>
                                                <th>Categoria</th>
                                                <th>Pagamento</th>
                                                <th>Parcela</th>
                                                <th>Pago</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($transacoes->where('categoria_id', $categoria->id) as $transacao)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($transacao->dt_vencimento)->format('d/m/Y') }}</td>
                                                    <td>{{ $transacao->descricao }}</td>
                                                    <td>{{ $categoria->id === $transacao->categoria_id && $transacao->categoria_id === 1 ? $transacao->recebido->rz_social : $transacao->pago->rz_social }}</td>
                                                    <td>R$ {{ number_format($transacao->vr_parcela, 2, ',', '.') }}</td>
                                                    <td>{{ $transacao->subcategoria->nome }}</td>
                                                    <td>
                                                        @switch($transacao->tipo_pagamento)
                                                            @case('V')
                                                                <span>À VISTA</span>
                                                                @break
                                                            @case('P')
                                                                <span>PARCELADO</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $transacao->nr_parcela }} / {{ \App\Models\ParcelaTransacao::where('transacao_id', $transacao->id)->count('transacao_id') }}</th>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" name="ds_pago" data-plugin-ios-switch
                                                                {{ $transacao->ds_pago === 'S' ? 'checked' : '' }}
                                                                onclick="informarPagamento('{{ route('transacoes.informar.pagamento') }}', null, '{{ $transacao->parc_transacao_id }}', true);"/>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <div class="input-group-btn">
                                                            <button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="true">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul role="menu" class="dropdown-menu dropdown-transaction pull-right">
                                                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                                                    (strtolower(Auth::user()->permissao) === "user" && $param->alterar_movimentacao_despvariavel === "S"))
                                                                    <li>
                                                                        <button type="button" class="btn btn-link btn-sm text-info modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#{{ $transacao->id }}" data-width="modal-lg" data-url="{{ route('transacoes.modal.create-edit') }}" title="Editar informações">
                                                                            <i class="fa fa-pencil fa-fw"></i>
                                                                            Editar
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <button type="button" class="btn btn-link btn-sm text-primary modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#{{ $transacao->id }}" data-width="modal-md" data-url="{{ route('transacoes.modal.parcelas') }}" title="Ver parcelas">
                                                                            <i class="fa fa-list fa-fw"></i>
                                                                            Parcelas
                                                                        </button>
                                                                    </li>
                                                                @endif
                                                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                                                    (strtolower(Auth::user()->permissao) === "user" && $param->excluir_movimentacao_despvariavel === "S"))
                                                                    <li>
                                                                        <button class="btn btn-link btn-sm text-danger modal-call" data-id="{{ $transacao->id }}" data-width="modal-md" data-url="{{ route('transacoes.modal.delete') }}" title="Remover registro">
                                                                            <i class="fa fa-trash-o fa-fw"></i>
                                                                            Remover
                                                                        </button>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-bold">Nenhuma transação encontrada !!!</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row bg-gray">
                                    <div class="col-md-12">
                                        <p>Pago: <span class="text-bold">R$ {{ number_format($vr_pago, 2, ',', '.') }}</span></p>
                                        <p>A pagar: <span class="text-bold">R$ {{ number_format($vr_apagar, 2, ',', '.') }}</span></p>
                                        <p>Total: <span class="text-bold">R$ {{ number_format($vr_total, 2, ',', '.') }}</span></p>
                                    </div>
                                </div>
                            </div>
                        @elseif( (strtolower(Auth::user()->permissao) === "admin" ||
                            (strtolower(Auth::user()->permissao) === "user" && $param->ver_aba_pessoas === "S")) &&
                            $categoria->id === 4)
                            <div id="{{ strtolower(str_replace(' ', '_', tirarAcentos($categoria->nome))) }}" class="tab-pane {{ $categoria->id === 1 ? 'active' : '' }}">
                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                    (strtolower(Auth::user()->permissao) === "user" && $param->incluir_movimentacao_pessoas === "S"))
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button id="btn_novo_registro" class="btn btn-default btn-sm mt-sm mb-sm modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#0" data-width="modal-lg" data-url="{{ route("transacoes.modal.create-edit") }}" title="Novo registro">
                                                <i class="fa fa-plus fa-fw"></i>
                                                Novo registro
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Descrição</th>
                                                <th>{{ $categoria->id === 1 ? 'Recebido de' : 'Pago a' }}</th>
                                                <th>Valor</th>
                                                <th>Categoria</th>
                                                <th>Pagamento</th>
                                                <th>Parcela</th>
                                                <th>Pago</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($transacoes->where('categoria_id', $categoria->id) as $transacao)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($transacao->dt_vencimento)->format('d/m/Y') }}</td>
                                                    <td>{{ $transacao->descricao }}</td>
                                                    <td>{{ $categoria->id === $transacao->categoria_id && $transacao->categoria_id === 1 ? $transacao->recebido->rz_social : $transacao->pago->rz_social }}</td>
                                                    <td>R$ {{ number_format($transacao->vr_parcela, 2, ',', '.') }}</td>
                                                    <td>{{ $transacao->subcategoria->nome }}</td>
                                                    <td>
                                                        @switch($transacao->tipo_pagamento)
                                                            @case('V')
                                                                <span>À VISTA</span>
                                                                @break
                                                            @case('P')
                                                                <span>PARCELADO</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $transacao->nr_parcela }} / {{ \App\Models\ParcelaTransacao::where('transacao_id', $transacao->id)->count('transacao_id') }}</th>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" name="ds_pago" data-plugin-ios-switch
                                                                {{ $transacao->ds_pago === 'S' ? 'checked' : '' }}
                                                                onclick="informarPagamento('{{ route('transacoes.informar.pagamento') }}', null, '{{ $transacao->parc_transacao_id }}', true);"/>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <div class="input-group-btn">
                                                            <button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="true">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul role="menu" class="dropdown-menu dropdown-transaction pull-right">
                                                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                                                    (strtolower(Auth::user()->permissao) === "user" && $param->alterar_movimentacao_pessoas === "S"))
                                                                    <li>
                                                                        <button type="button" class="btn btn-link btn-sm text-info modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#{{ $transacao->id }}" data-width="modal-lg" data-url="{{ route('transacoes.modal.create-edit') }}" title="Editar informações">
                                                                            <i class="fa fa-pencil fa-fw"></i>
                                                                            Editar
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <button type="button" class="btn btn-link btn-sm text-primary modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#{{ $transacao->id }}" data-width="modal-md" data-url="{{ route('transacoes.modal.parcelas') }}" title="Ver parcelas">
                                                                            <i class="fa fa-list fa-fw"></i>
                                                                            Parcelas
                                                                        </button>
                                                                    </li>
                                                                @endif
                                                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                                                    (strtolower(Auth::user()->permissao) === "user" && $param->excluir_movimentacao_pessoas === "S"))
                                                                    <li>
                                                                        <button class="btn btn-link btn-sm text-danger modal-call" data-id="{{ $transacao->id }}" data-width="modal-md" data-url="{{ route('transacoes.modal.delete') }}" title="Remover registro">
                                                                            <i class="fa fa-trash-o fa-fw"></i>
                                                                            Remover
                                                                        </button>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-bold">Nenhuma transação encontrada !!!</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                               </div>

                                <div class="row bg-gray">
                                    <div class="col-md-12">
                                        <p>Pago: <span class="text-bold">R$ {{ number_format($vr_pago, 2, ',', '.') }}</span></p>
                                        <p>A pagar: <span class="text-bold">R$ {{ number_format($vr_apagar, 2, ',', '.') }}</span></p>
                                        <p>Total: <span class="text-bold">R$ {{ number_format($vr_total, 2, ',', '.') }}</span></p>
                                    </div>
                                </div>
                            </div>
                        @elseif( (strtolower(Auth::user()->permissao) === "admin" ||
                            (strtolower(Auth::user()->permissao) === "user" && $param->ver_aba_impostos === "S")) &&
                            $categoria->id === 5)
                            <div id="{{ strtolower(str_replace(' ', '_', tirarAcentos($categoria->nome))) }}" class="tab-pane {{ $categoria->id === 1 ? 'active' : '' }}">
                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                    (strtolower(Auth::user()->permissao) === "user" && $param->incluir_movimentacao_impostos === "S"))
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button id="btn_novo_registro" class="btn btn-default btn-sm mt-sm mb-sm modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#0" data-width="modal-lg" data-url="{{ route("transacoes.modal.create-edit") }}" title="Novo registro">
                                                <i class="fa fa-plus fa-fw"></i>
                                                Novo registro
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Descrição</th>
                                                <th>{{ $categoria->id === 1 ? 'Recebido de' : 'Pago a' }}</th>
                                                <th>Valor</th>
                                                <th>Categoria</th>
                                                <th>Pagamento</th>
                                                <th>Parcela</th>
                                                <th>Pago</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($transacoes->where('categoria_id', $categoria->id) as $transacao)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($transacao->dt_vencimento)->format('d/m/Y') }}</td>
                                                    <td>{{ $transacao->descricao }}</td>
                                                    <td>{{ $categoria->id === $transacao->categoria_id && $transacao->categoria_id === 1 ? $transacao->recebido->rz_social : $transacao->pago->rz_social }}</td>
                                                    <td>R$ {{ number_format($transacao->vr_parcela, 2, ',', '.') }}</td>
                                                    <td>{{ $transacao->subcategoria->nome }}</td>
                                                    <td>
                                                        @switch($transacao->tipo_pagamento)
                                                            @case('V')
                                                                <span>À VISTA</span>
                                                                @break
                                                            @case('P')
                                                                <span>PARCELADO</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $transacao->nr_parcela }} / {{ \App\Models\ParcelaTransacao::where('transacao_id', $transacao->id)->count('transacao_id') }}</th>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" name="ds_pago" data-plugin-ios-switch
                                                                {{ $transacao->ds_pago === 'S' ? 'checked' : '' }}
                                                                onclick="informarPagamento('{{ route('transacoes.informar.pagamento') }}', null, '{{ $transacao->parc_transacao_id }}', true);"/>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <div class="input-group-btn">
                                                            <button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="true">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul role="menu" class="dropdown-menu dropdown-transaction pull-right">
                                                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                                                    (strtolower(Auth::user()->permissao) === "user" && $param->alterar_movimentacao_impostos === "S"))
                                                                    <li>
                                                                        <button type="button" class="btn btn-link btn-sm text-info modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#{{ $transacao->id }}" data-width="modal-lg" data-url="{{ route('transacoes.modal.create-edit') }}" title="Editar informações">
                                                                            <i class="fa fa-pencil fa-fw"></i>
                                                                            Editar
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <button type="button" class="btn btn-link btn-sm text-primary modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP ? $contaP->id : '0' }}#{{ $transacao->id }}" data-width="modal-md" data-url="{{ route('transacoes.modal.parcelas') }}" title="Ver parcelas">
                                                                            <i class="fa fa-list fa-fw"></i>
                                                                            Parcelas
                                                                        </button>
                                                                    </li>
                                                                @endif
                                                                @if( strtolower(Auth::user()->permissao) === "admin" ||
                                                                    (strtolower(Auth::user()->permissao) === "user" && $param->excluir_movimentacao_impostos === "S"))
                                                                    <li>
                                                                        <button class="btn btn-link btn-sm text-danger modal-call" data-id="{{ $transacao->id }}" data-width="modal-md" data-url="{{ route('transacoes.modal.delete') }}" title="Remover registro">
                                                                            <i class="fa fa-trash-o fa-fw"></i>
                                                                            Remover
                                                                        </button>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-bold">Nenhuma transação encontrada !!!</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row bg-gray">
                                    <div class="col-md-12">
                                        <p>Pago: <span class="text-bold">R$ {{ number_format($vr_pago, 2, ',', '.') }}</span></p>
                                        <p>A pagar: <span class="text-bold">R$ {{ number_format($vr_apagar, 2, ',', '.') }}</span></p>
                                        <p>Total: <span class="text-bold">R$ {{ number_format($vr_total, 2, ',', '.') }}</span></p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @if( strtolower(Auth::user()->permissao) === "admin" ||
                        (strtolower(Auth::user()->permissao) === "user" && $param->ver_aba_transferencia === "S"))
                        <div id="transferencia" class="tab-pane">
                            @php
                                $vr_total = \App\Models\Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
                                    ->where('transacoes.empresa_id', $empresa_id)
                                    ->where('categoria_id', 0)
                                    ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
                                    ->sum('parcelas_transacoes.vr_parcela');
                                $vr_pago = \App\Models\Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
                                    ->where('transacoes.empresa_id', $empresa_id)
                                    ->where('categoria_id', 0)
                                    ->where('parcelas_transacoes.ds_pago', "S")
                                    ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
                                    ->sum('parcelas_transacoes.vr_parcela');
                                $vr_apagar = \App\Models\Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
                                    ->where('transacoes.empresa_id', $empresa_id)
                                    ->where('categoria_id', 0)
                                    ->where('parcelas_transacoes.ds_pago', "N")
                                    ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
                                    ->sum('parcelas_transacoes.vr_parcela');
                            @endphp
                            @if( strtolower(Auth::user()->permissao) === "admin" ||
                                (strtolower(Auth::user()->permissao) === "user" && $param->incluir_movimentacao_transferencia === "S"))
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button id="btn_novo_registro" class="btn btn-default btn-sm mt-sm mb-sm modal-call" data-id="{{ $empresa_id }}#{{ $contaP ? $contaP->id : 0 }}" data-width="modal-lg" data-url="{{ route("transacoes.modal.transferencias") }}" title="Novo registro">
                                            <i class="fa fa-plus fa-fw"></i>
                                            Novo registro
                                        </button>
                                    </div>
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Descrição</th>
                                            <th>Valor</th>
                                            <th>Conta origem</th>
                                            <th>Conta destino</th>
                                            <th>Pago</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transferencias as $transf)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($transf->dt_transf)->format('d/m/Y') }}</td>
                                                <td>{{ $transf->descricao }}</td>
                                                <td class="{{ ($transf->conta_origem_id == $conta_id) ? 'text-danger' : 'text-success' }}">R$ {{ number_format($transf->vr_parcela, 2, ',', '.') }}</td>
                                                <td>{{ $transf->conta_origem->ds_conta }}</td>
                                                <td>{{ $transf->conta_destino->ds_conta }}</td>
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" name="ds_pago" data-plugin-ios-switch
                                                            {{ $transf->ds_pago === 'S' ? 'checked' : '' }}
                                                            onclick="informarPagamento('{{ route('transacoes.informar.pagamento') }}', null, '{{ $transf->transferencia_id }}', true);"/>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="input-group-btn">
                                                        <button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="true">
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul role="menu" class="dropdown-menu dropdown-transaction pull-right">
                                                            @if( strtolower(Auth::user()->permissao) === "admin" ||
                                                                (strtolower(Auth::user()->permissao) === "user" && $param->alterar_movimentacao_transferencia === "S"))
                                                                <li>
                                                                    <form id="form-transferencia{{ $transf->id }}" action="{{ route('transacoes.duplica.transferencia', ['id'=>$transf->id]) }}" method="post">@csrf</form>
                                                                    <button type="button" class="btn btn-link btn-sm text-info" title="Duplicar transferência" onclick="submitForm('form-transferencia{{ $transf->id }}');">
                                                                        <i class="fa fa-files-o fa-fw"></i>
                                                                        Duplicar
                                                                    </button>
                                                                </li>
                                                            @endif
                                                            @if( strtolower(Auth::user()->permissao) === "admin" ||
                                                                (strtolower(Auth::user()->permissao) === "user" && $param->excluir_movimentacao_transferencia === "S"))
                                                                <li>
                                                                    <button class="btn btn-link btn-sm text-danger modal-call" data-id="{{ $transf->id }}" data-width="modal-md" data-url="{{ route('transacoes.modal.delete') }}" title="Remover registro">
                                                                        <i class="fa fa-trash-o fa-fw"></i>
                                                                        Remover
                                                                    </button>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-bold">Nenhuma transação encontrada !!!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="row bg-gray">
                                <div class="col-md-12">
                                    <p>Transferido: <span class="text-bold">R$ {{ number_format($vr_pago, 2, ',', '.') }}</span></p>
                                    <p>A transferir: <span class="text-bold">R$ {{ number_format($vr_apagar, 2, ',', '.') }}</span></p>
                                    <p>Total: <span class="text-bold">R$ {{ number_format($vr_total, 2, ',', '.') }}</span></p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        function trocaContas(form_id){
            var mes_transacao = $("#mes_transacao").children(":selected").val();

            $(form_id+" #mes_selecionado").val(mes_transacao);
            $(form_id).submit();
        }
    </script>
</section>
@endsection
