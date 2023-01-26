@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Finanças</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Tipos de Conta</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="row">
        <div class="col-md-10">
            <h3>Tipos de Conta</h3>
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
                            <th>Tipo de conta</th>
                            <th width="8%" class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_novo_registro">
                        @forelse ($tipos_contas as $item)
                            <tr>
                                <td>
                                    <form id="form-atualiza-tipo-conta-{{ $item->id }}" action="{{ route('tipos-contas.atualiza.registro', ['id' => $item->id]) }}" method="post" class="form">
                                        <input type="text" name="tp_conta" class="form-control" value="{{ $item->tp_conta }}" maxlength="100">
                                    </form>
                                </td>
                                <td width="8%" class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm btn-spin-pencil" title="Atualizar registro" onclick="submitForm('form-atualiza-tipo-conta-{{ $item->id }}')">
                                            <i class="fa fa-pencil fa-fw"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm btn-spin-trash-o" title="Remover registro" onclick="submitForm('form-remove-tipo-conta-{{ $item->id }}')">
                                            <i class="fa fa-trash-o fa-fw"></i>
                                        </button>
                                        <form id="form-remove-tipo-conta-{{ $item->id }}" action="{{ route('tipos-contas.remove.registro', ['id' => $item->id]) }}" method="post" class="form">
                                            <input type="hidden" name="tbody" value="#tbody_novo_registro">
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="tr_sem_registro">
                                <td colspan="2" class="text-center text-bold">Nenhum registro encontrado !!!</td>
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
                var submitForm = "form-insere-tipo-conta-"+contador;

                row += '<tr id="tr-'+contador+'" class="inserido">';
                row += '    <td>';
                row += '        <form id="form-insere-tipo-conta-'+contador+'" action="{{ route("tipos-contas.insere.registro") }}" method="post" class="form">';
                row += '            <input type="hidden" name="tbody" value="#tbody_novo_registro">';
                row += '            <input type="text" name="tp_conta" class="form-control" maxlength="100">';
                row += '        </form>';
                row += '    </td>';
                row += '    <td width="8%" class="text-center">';
                row += '        <button type="button" class="btn btn-success btn-sm btn-spin-check" title="Salvar registro" onclick="submitForm(\''+submitForm+'\')">';
                row += '            <i class="fa fa-check fa-fw"></i>';
                row += '        </button>';
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
