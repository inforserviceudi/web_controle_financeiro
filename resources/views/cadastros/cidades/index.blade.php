@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Cadastros</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Cidade</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="row">
        <div class="col-md-10">
            <h3>Cidade</h3>
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
                                <div class="col-md-7">Nome da cidade</div>
                                <div class="col-md-3">Estado</div>
                                <div class="col-md-2 text-center">Ações</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbody_novo_registro">
                        @forelse ($cidades as $item)
                            <tr>
                                <td>
                                    <form id="form-atualiza-cidade-{{ $item->id }}" action="{{ route('cidades.atualiza.registro', ['id' => $item->id]) }}" method="post" class="form">
                                        <div class="col-md-7">
                                            <input type="text" name="nm_cidade" class="form-control" value="{{ $item->nm_cidade }}" maxlength="150" required>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="estado_id" class="form-control js-single" required>
                                                @foreach ($estados as $estado)
                                                    <option value="{{ $estado->id }}" {{ $estado->id === $item->estado_id ? 'selected' : '' }}>
                                                        {{ $estado->ds_sigla }} - {{ $estado->nm_estado }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>

                                    <div class="col-md-2 text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm btn-spin-pencil" title="Atualizar registro" onclick="submitForm('form-atualiza-cidade-{{ $item->id }}')">
                                                <i class="fa fa-pencil fa-fw"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btn-spin-trash-o" title="Remover registro" onclick="submitForm('form-remove-cidade-{{ $item->id }}')">
                                                <i class="fa fa-trash-o fa-fw"></i>
                                            </button>
                                            <form id="form-remove-cidade-{{ $item->id }}" action="{{ route('cidades.remove.registro', ['id' => $item->id]) }}" method="post" class="form">
                                                <input type="hidden" name="tbody" value="#tbody_novo_registro">
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="tr_sem_registro">
                                <td class="text-center text-bold">Nenhum registro encontrado !!!</td>
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
            const estados = @json($estados);
            $("#tr_sem_registro").remove();

            if( !$("#tbody_novo_registro tr").hasClass('inserido') ){
                var row = '';
                var contador = (parseInt(nr_registros) + 1);
                var submitForm = "form-insere-cidade-"+contador;

                row += '<tr id="tr-'+contador+'" class="inserido">';
                row += '    <td>';
                row += '        <form id="'+submitForm+'" action="{{ route("cidades.insere.registro") }}" method="post" class="form">';
                row += '            <div class="col-md-7">';
                row += '                <input type="hidden" name="tbody" value="#tbody_novo_registro">';
                row += '                <input type="text" name="nm_cidade" class="form-control" maxlength="150" required>';
                row += '            </div>';
                row += '            <div class="col-md-3">';
                row += '                <select name="estado_id" class="form-control js-single" required>';
                row += '                    <option value="0" selected disabled>Selecione ...</option>';
                        estados.map((estado)=>{
                row += '                    <option value="'+estado.id+'">'+estado.ds_sigla+' - '+estado.nm_estado+'</option>';
                        });
                row += '                </select>';
                row += '            </div>';
                row += '            <div class="col-md-2 text-center">';
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
