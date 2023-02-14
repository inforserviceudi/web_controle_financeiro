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
                    <span style="font-weight:bold; font-size: 2em;">R$ 11.222,33</span>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-uppercase">recebimentos</h6>
                            <p>Recebido: <span class="text-success text-bold">R$ 11.222,33</span></p>
                            <p>Previsto: <span class="text-bold">R$ 11.222,33</span></p>
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
                                                    <li>R$ 11.222,33</li>
                                                </ul>
                                                <ul>
                                                    <li>Previsto:</li>
                                                    <li>R$ 11.222,33</li>
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
                                                    <li>R$ 11.222,33</li>
                                                </ul>
                                                <ul>
                                                    <li>Previsto:</li>
                                                    <li>R$ 11.222,33</li>
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
                                                    <li>R$ 11.222,33</li>
                                                </ul>
                                                <ul>
                                                    <li>Previsto:</li>
                                                    <li>R$ 11.222,33</li>
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
                                                    <li>R$ 11.222,33</li>
                                                </ul>
                                                <ul>
                                                    <li>Previsto:</li>
                                                    <li>R$ 11.222,33</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </h6>
                            <p style="margin-top: -4%;">Pago: <span class="text-danger text-bold">R$ 11.222,33</span></p>
                            <p>Previsto: <span class="text-bold">R$ 11.222,33</span></p>
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
                                <div class="row" onclick="$('#form-conta-{{ $conta->id }}').submit();" style="cursor: pointer; margin: 3px 5px;">
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
                                <div class="row" onclick="$('#form-conta-0').submit();" style="cursor: pointer; margin: 3px 5px;">
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
                    <select name="mes_transacao" id="mes_transacao" class="form-control">
                        @for ($i = 0; $i < 12; $i++)
                            <option value="{{ $i+1 }}" {{ ($i+1) === intval($mes_atual) ? 'selected' : '' }}>
                                {{ $meses[$i] .' '. $ano_atual }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="tabs">
                <ul class="nav nav-tabs">
                    @foreach ($categorias as $categoria)
                        <li class="{{ $categoria->id === 1 ? 'active' : '' }}">
                            <a href="#{{ strtolower(str_replace(' ', '_', tirarAcentos($categoria->nome))) }}" data-toggle="tab" aria-expanded="false">
                                {{ $categoria->nome }}
                            </a>
                        </li>
                    @endforeach
                    <li class="">
                        <a href="#transferencia" data-toggle="tab" aria-expanded="true">
                            Transferências
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    @foreach ($categorias as $categoria)
                        <div id="{{ strtolower(str_replace(' ', '_', tirarAcentos($categoria->nome))) }}" class="tab-pane {{ $categoria->id === 1 ? 'active' : '' }}">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button id="btn_novo_registro" class="btn btn-default btn-sm mt-sm mb-sm modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP->id }}#0" data-width="modal-lg" data-url="{{ route("transacoes.modal.create-edit") }}" title="Novo registro">
                                        <i class="fa fa-plus fa-fw"></i>
                                        Novo registro
                                    </button>
                                </div>
                            </div>
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
                                                <th>{{ \Carbon\Carbon::parse($transacao->dt_vencimento)->format('d/m/Y') }}</th>
                                                <th>{{ $transacao->descricao }}</th>
                                                <th>{{ $categoria->id === $transacao->categoria_id && $transacao->categoria_id === 1 ? $transacao->recebido->rz_social : $transacao->pago->rz_social }}</th>
                                                <th>R$ {{ number_format($transacao->vr_parcela, 2, ',', '.') }}</th>
                                                <th>{{ $transacao->subcategoria->nome }}</th>
                                                <th>
                                                    @switch($transacao->tipo_pagamento)
                                                        @case('V')
                                                            <span>À VISTA</span>
                                                            @break
                                                        @case('P')
                                                            <span>PARCELADO</span>
                                                            @break
                                                    @endswitch
                                                </th>
                                                <th>{{ $transacao->nr_parcela }} / {{ \App\Models\ParcelaTransacao::where('transacao_id', $transacao->id)->count('transacao_id') }}</th>
                                                <th>
                                                    <div class="switch switch-sm switch-success">
														<input type="checkbox" name="ds_pago" data-plugin-ios-switch {{ $transacao->ds_pago === "S" ? 'checked' : '' }} />
													</div>
                                                </th>
                                                <th>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm text-white modal-call" data-id="{{ $empresa_id }}#{{ $categoria->id }}#{{ $contaP->id }}#{{ $transacao->id }}" data-width="modal-lg" data-url="{{ route('transacoes.modal.create-edit') }}" title="Editar informações">
                                                            <i class="fa fa-pencil fa-fw"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm text-white modal-call" data-id="{{ $transacao->id }}" data-width="modal-md" data-url="{{ route('transacoes.modal.delete') }}" title="Remover registro">
                                                            <i class="fa fa-trash-o fa-fw"></i>
                                                        </button>
                                                    </div>
                                                </th>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-bold">Nenhuma transação encontrada !!!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                           </div>
                        </div>
                    @endforeach
                    <div id="transferencia" class="tab-pane">
                        <p>transferencia</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
