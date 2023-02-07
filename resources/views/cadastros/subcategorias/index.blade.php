@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <style>
        .categoria-ativa{
            background-color: #CCC !important;
        }
    </style>
    <header class="page-header">
        <h2>Cadastros</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Categorias</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <section class="panel mt-lg">
        <header class="panel-heading">
            <h2 class="panel-title">Categorias</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <ul class="ul-categoria">
                        @foreach ($categorias as $categoria)
                        <li class="li-categoria" onclick="selecionaCategoria({{ $categoria->id }});">
                            <section class="panel panel-featured-left">
                                <div class="panel-body {{ $categoria->id == $categ_id ? 'categoria-ativa' : '' }}">
                                    <div class="widget-summary widget-summary-sm">
                                        <div class="widget-summary-col">
                                            <div class="summary">
                                                <div class="info">
                                                    <strong class="amount"> {{ $categoria->nome }} </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </li>
                        @endforeach
                    </ul>

                    <form id="form-categoria" action="{{ route('subcategorias.index') }}" method="get">
                        <input type="hidden" id="categoria_id" name="categoria_id">
                    </form>
                </div>
                <div class="col-md-8">
                    <section class="panel">
                        <div class="panel-heading">
                            <form id="form-criar-categoria" action="{{ route('subcategorias.insere.registro') }}" method="post" class="form">@csrf
                                <input type="hidden" name="categoria_id" value="{{ $categ_id }}">

                                <div class="row form-group">
                                    <div class="col-md-10">
                                        <input type="text" name="nm_subcategoria" id="nm_subcategoria" class="form-control" placeholder="Digite a nova categoria ..." maxlength="150" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-success btn-sm btn-block">
                                            <i class="fa fa-check fa-fw"></i>
                                            Salvar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="panel-body">
                            @forelse ( $subcategorias as $sub )
                                <div class="row mb-md">
                                    <div class="col-md-10">
                                        <form id="form-categoria-{{ $sub->id }}" action="{{ route('subcategorias.atualiza.registro', ['id' => $sub->id]) }}" method="post" class="form">@csrf
                                            <input type="text" name="nome_subcategoria" class="form-control" value="{{ $sub->nome }}" maxlength="150" required>
                                        </form>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm" title="Atualizar registro" onclick="formUpdate('#form-categoria-{{ $sub->id }}');">
                                                <i class="fa fa-pencil fa-fw"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm modal-call" data-id="{{ $sub->id }}" data-width="modal-md" data-url="{{ route('subcategorias.modal.delete') }}" title="Remover registro">
                                                <i class="fa fa-trash-o fa-fw"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <p class="text-bold">Nenhuma categoria cadastrada !!!</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <script>
                            function formUpdate(form){
                                $(form).submit();
                            }
                        </script>
                    </section>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
