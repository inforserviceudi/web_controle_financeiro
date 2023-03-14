@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <header class="page-header no-print">
        <h2>Resultado da busca</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span class="text-bold">{{ ucfirst($item_pesquisado) }}</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="panel mt-lg">
        <div class="row container">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-2 text-right">
                        <h4 class="badge bg-success" style="font-size: 18pt;">{{ $resultado_total }}</h4>
                    </div>
                    <div class="col-md-10 text-left">
                        <h4>resultados foram encontrados para sua pesquisa!</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <ul>
                    @if (count($transacoes) > 0)
                        <li>
                            <p>
                                <span class="badge badge-primary">{{ count($transacoes) }}</span>
                                em movimentações
                            </p>
                        </li>
                    @endif

                    @if (count($contatos) > 0)
                        <li>
                            <p>
                                <span class="badge badge-primary">{{ count($contatos) }}</span>
                                em contatos
                            </p>
                        </li>
                    @endif

                    @if (count($subcategorias) > 0)
                        <li>
                            <p>
                                <span class="badge badge-primary">{{ count($subcategorias) }}</span>
                                em categorias
                            </p>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="panel-body">
            @if (count($transacoes) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <caption>
                            <span class="badge badge-primary">{{ count($transacoes) }}</span>
                            em movimentações
                        </caption>
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th>Descrição</th>
                                <th>Recebido de / Pago a</th>
                                <th>Categoria</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transacoes as $trans)
                            <tr style="cursor: pointer;">
                                <td>{{ \Carbon\Carbon::parse($trans->dt_transacao)->format('d/m/Y') }}</td>
                                <td>{{ $trans->categoria->nome }}</td>
                                <td>{{ $trans->descricao }}</td>
                                <td>
                                    @if( !empty($trans->recebido_de) && empty($trans->pago_a) )
                                        {{ $trans->recebido->rz_social }}
                                    @endif

                                    @if( !empty($trans->pago_a) && empty($trans->recebido_de) )
                                        {{ $trans->pago->rz_social }}
                                    @endif
                                </td>
                                <td>{{ $trans->subcategoria->nome }}</td>
                                <td>R${{ number_format($trans->vr_parcela, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (count($contatos) > 0)
                <hr class="divider">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <caption>
                            <span class="badge badge-primary">{{ count($contatos) }}</span>
                            em contatos
                        </caption>
                        <thead>
                            <tr>
                                <th>Cliente / Fornecedor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contatos as $cont)
                            <tr>
                                <td style="cursor: pointer;" class="modal-call" data-id="{{ $cont->id }}" data-width="modal-md" data-url="{{ route("contatos.modal.create-edit") }}" title="Clique no registro para ver mais informações">
                                    {{ $cont->rz_social }} / {{ $cont->nm_fantasia }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (count($subcategorias) > 0)
                <hr class="divider">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <caption>
                            <span class="badge badge-primary">{{ count($subcategorias) }}</span>
                            em categorias
                        </caption>
                        <thead>
                            <tr>
                                <th>Categoria</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subcategorias as $subcat)
                            <tr>
                                <td>
                                    <a href="{{ route('subcategorias.index', ['categoria_id'=>$subcat->categoria_id]) }}" type="button" style="color: #777; text-decoration: none; cursor:pointer;">
                                        {{ $subcat->nome }} em <em class="text-primary">{{ $subcat->categoria->nome }}</em>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
