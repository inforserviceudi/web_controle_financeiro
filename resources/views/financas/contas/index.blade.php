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
                <li><span>Contas</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="row">
        <div class="col-md-10">
            <h3>Contas</h3>
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
                                <div class="col-md-4">Conta</div>
                                <div class="col-md-2">Saldo incial</div>
                                <div class="col-md-2">Tipo de conta</div>
                                <div class="col-md-2"></div>
                                <div class="col-md-2 text-center">Ações</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbody_novo_registro">
                        @forelse ($contas as $item)
                            <tr>
                                <td>
                                    <form id="form-atualiza-conta-{{ $item->id }}" action="{{ route('contas.atualiza.registro', ['id' => $item->id]) }}" method="post" class="form">
                                        <div class="col-md-4">
                                            <input type="text" name="ds_conta" class="form-control" value="{{ $item->ds_conta }}" maxlength="150" required>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group mb-md">
                                                <div class="input-group-btn">
                                                    <select name="tp_saldo_inicial" class="form-control" style="width: 40px;">
                                                        <option value="P" {{ $item->tp_saldo_inicial == "P" ? "selected" : "" }} style="font-size: 16px; font-weight: bold;"> + </option>
                                                        <option value="N" {{ $item->tp_saldo_inicial == "N" ? "selected" : "" }} style="font-size: 16px; font-weight: bold;"> - </option>
                                                    </select>
                                                </div>
                                                <input type="text" name="vr_saldo_inicial" class="form-control mask-valor" value="{{ number_format($item->vr_saldo_inicial, 2, ',', '.') }}" maxlength="12">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="tipo_conta_id" class="form-control js-single" required>
                                                @foreach ($tipos_contas as $tpconta)
                                                    <option value="{{ $tpconta->id }}" {{ $tpconta->id === $item->tipo_conta_id ? 'selected' : '' }}>
                                                        {{ $tpconta->tp_conta }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="ds_conta_principal" id="ds_conta_principal{{ $item->id }}" {{ $item->ds_conta_principal === "S" ? "checked" : "" }}>Conta principal
                                                </label>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="col-md-2 text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm btn-spin-pencil" title="Atualizar registro" onclick="submitForm('form-atualiza-conta-{{ $item->id }}')">
                                                <i class="fa fa-pencil fa-fw"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btn-spin-trash-o" title="Remover registro" onclick="submitForm('form-remove-conta-{{ $item->id }}')">
                                                <i class="fa fa-trash-o fa-fw"></i>
                                            </button>
                                            <form id="form-remove-conta-{{ $item->id }}" action="{{ route('contas.remove.registro', ['id' => $item->id]) }}" method="post" class="form"></form>
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
            const tipos_contas = @json($tipos_contas);
            $("#tr_sem_registro").remove();

            if( !$("#tbody_novo_registro tr").hasClass('inserido') ){
                var row = '';
                var contador = (parseInt(nr_registros) + 1);
                var submitForm = "form-insere-conta-"+contador;

                row += '<tr id="tr-'+contador+'" class="inserido">';
                row += '    <td>';
                row += '        <form id="'+submitForm+'" action="{{ route("contas.insere.registro") }}" method="post" class="form">';
                row += '            <div class="col-md-4">';
                row += '                <input type="text" name="ds_conta" class="form-control" maxlength="150" required>';
                row += '            </div>';
                row += '            <div class="col-md-2">';
                row += '                <div class="input-group mb-md">';
                row += '                    <div class="input-group-btn" style="width: 40px;">';
                row += '                        <select name="tp_saldo_inicial" class="form-control" style="font-size: 16px; font-weight: bold;">';
                row += '                            <option value="P" style="font-size: 16px; font-weight: bold;"> + </option>';
                row += '                            <option value="N" style="font-size: 16px; font-weight: bold;"> - </option>';
                row += '                        </select>';
                row += '                    </div>';
                row += '                    <input type="text" name="vr_saldo_inicial" class="form-control mask-valor" maxlength="12">';
                row += '                </div>';
                row += '            </div>';
                row += '            <div class="col-md-2">';
                row += '                <select name="tipo_conta_id" class="form-control js-single" required>';
                row += '                    <option value="0" selected disabled>Selecione ...</option>';
                    tipos_contas.map((tpconta)=>{
                row += '                    <option value="'+tpconta.id+'">'+tpconta.tp_conta+'</option>';
                    });
                row += '                </select>';
                row += '            </div>';
                row += '            <div class="col-md-2">';
                row += '                <div class="radio">';
                row += '                    <label>';
                row += '                        <input type="radio" name="ds_conta_principal" id="ds_conta_principal'+contador+'">Conta principal';
                row += '                    </label>';
                row += '                </div>';
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
