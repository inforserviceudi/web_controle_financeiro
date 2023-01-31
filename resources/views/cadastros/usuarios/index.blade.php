@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Empresas</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="{{ route('dashboard') }}" title="Voltar para a tela inicial">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li>
                    <a href="{{ route('empresas.index') }}" title="Voltar para a tela de empresas">
                       <span>Empresas</span>
                    </a>
                </li>
                <li><span>Usuários</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="row">
        <div class="col-md-10">
            <h3>Usuários</h3>
        </div>
        <div class="col-md-2">
            <button id="btn_novo_registro" class="btn btn-default btn-sm btn-block mt-xl">
                <i class="fa fa-plus"></i>
                Novo registro
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <div class="col-md-4">Pessoa</div>
                                <div class="col-md-4">E-mail</div>
                                <div class="col-md-2">Permissões</div>
                                <div class="col-md-1 text-center">Log</div>
                                <div class="col-md-1 text-center">Ações</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="col-md-4">
                                    {{ $admin->name }}
                                </div>
                                <div class="col-md-4">
                                    {{ $admin->email }}
                                </div>
                                <div class="col-md-2">
                                    <strong>{{ $admin->permissao }}</strong>
                                </div>
                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-link btn-block modal-call" data-id="{{ $admin->id }}" data-width="modal-lg" data-url="{{ route('usuarios.modal.logs') }}">
                                        Ver log
                                    </button>
                                </div>
                                <div class="col-md-1 text-center"> --- </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody id="tbody_novo_registro">
                        @forelse ($usuarios as $item)
                            <tr>
                                <td>
                                    <div class="col-md-4">{{ $item->name }}</div>
                                    <div class="col-md-4">{{ $item->email }}</div>
                                    <div class="col-md-2">
                                        <strong>{{ $item->permissao }}</strong> <br>
                                        <a href="{{ route('usuarios.permissoes') }}" class="btn btn-link"> Gerenciar </a>
                                    </div>
                                    <div class="col-md-1 text-center">

                                    </div>
                                    <div class="col-md-1 text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger btn-sm btn-spin-trash-o" title="Remover registro" onclick="submitForm('form-remove-usuario-{{ $item->id }}')">
                                                <i class="fa fa-trash-o fa-fw"></i>
                                            </button>
                                            <form id="form-remove-usuario-{{ $item->id }}" action="{{ route('usuarios.remove.registro', ['id' => $item->id]) }}" method="post" class="form"></form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="tr_sem_registro">
                                <td class="text-center text-bold">Nenhuma outra pessoa cadastrada até o momento para ter acesso aos dados da sua empresa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function(){
        $("#btn_novo_registro").on("click", function(){
            var nr_registros = "{{ $nr_registros }}";
            $("#tr_sem_registro").remove();

            if( !$("#tbody_novo_registro tr").hasClass('inserido') ){
                var row = '';
                var contador = (parseInt(nr_registros) + 1);
                var submitForm = "form-insere-usuario-"+contador;

                row += '<tr id="tr-'+contador+'" class="inserido">';
                row += '    <td>';
                row += '        <form id="'+submitForm+'" action="{{ route("usuarios.insere.registro") }}" method="post" class="form">';
                row += '            <div class="col-md-4">';
                row += '                <input type="text" name="name" class="form-control" maxlength="150" required>';
                row += '            </div>';
                row += '            <div class="col-md-4">';
                row += '                <input type="email" name="email" class="form-control" maxlength="150" required>';
                row += '            </div>';
                row += '            <div class="col-md-2">';
                row += '            </div>';
                row += '            <div class="col-md-1 text-center">';
                row += '            </div>';
                row += '            <div class="col-md-1 text-center">';
                row += '                <button type="button" class="btn btn-success btn-sm btn-spin-check" title="Salvar registro" onclick="submitForm(\''+submitForm+'\')">';
                row += '                    <i class="fa fa-check fa-fw"></i>';
                row += '                </button>';
                row += '            </div>';
                row += '        </form>';
                row += '    </td>';
                row += '</tr>';

                $("#tbody_novo_registro").append(row);
            }else{
                $("#tbody_novo_registro tr.inserido").remove();
            }
        });
    });
</script>
@endsection