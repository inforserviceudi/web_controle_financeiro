@php
    $contato_id = $categoria_id == 1 ? "recebido_de" : "pago_a";
    $nm_label = $categoria_id == 1 ? "Recebido de" : "Pago a";
    $tt_parcelas = \App\Models\ParcelaTransacao::where('transacao_id', $transacao_id)->count('transacao_id');
@endphp
<style>
    .mtb-3{ margin: 3% 0; }
    .mtb-5{ margin: 5% 0; }
    form label{ font-weight: bold; }
    .text-default{ color: #CCC; }
    .text-default:hover{ color: #d2322d; }
</style>
<div class="modal-header">
    <div class="row">
        <div class="col-md-11">
            <h4 class="modal-title">{{ $modal_title }}</h4>
        </div>
        <div class="col-md-1">
            <a href="javascript:void(0);" type="button" class="modal-close">
                <i class="fa fa-times fa-fw text-danger"></i>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <span>Categoria: {{ $categoria->nome }}</span>  <br>
            <span>Conta: {{ $conta->ds_conta }}</span>
        </div>
    </div>
</div>

@if( $transacao_id == 0 )
<form id="form-transacao" action="{{ route('transacoes.insere.registro') }}" method="post" class="form-horizontal" novalidate="novalidate">@csrf
@else
<form id="form-transacao" action="{{ route('transacoes.atualiza.registro', ['id' => $transacao_id]) }}" method="post" class="form-horizontal" novalidate="novalidate">@csrf
@endif
    <div class="modal-body">
        <input type="hidden" name="empresa_id" value="{{ $empresa_id }}">
        <input type="hidden" name="categoria_id" value="{{ $categoria_id }}">
        <input type="hidden" name="conta_id" value="{{ $conta_id }}">

        <div class="row form-group">
            <div class="col-md-3">
                <label for="dt_transacao">Data</label>
                <input type="date" name="dt_transacao" id="dt_transacao" class="form-control" value="{{ $transacao_id > 0 ? \Carbon\Carbon::parse($transacao->dt_transacao)->format('Y-m-d') : \Carbon\Carbon::today()->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-5">
                <label for="descricao">Descrição</label>
                <input type="text" name="descricao" id="descricao" class="form-control" value="{{ $transacao_id > 0 ? $transacao->descricao : '' }}" maxlength="150" required>
            </div>
            <div class="col-md-4">
                <label for="{{ $contato_id }}">{{ $nm_label }}</label>
                <select name="{{ $contato_id }}" id="{{ $contato_id }}" class="form-control">
                    <option value="0" selected disabled>Selecione ...</option>
                    @foreach ($contatos as $item)
                        <option value="{{ $item->id }}" {{ $transacao_id > 0 && $transacao->$contato_id === $item->id ? "selected" : "" }}>
                            {{ $item->rz_social }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-2">
                <label for="vr_total">Valor</label>
                <input type="text" name="vr_total" id="vr_total" class="form-control mask-valor" value="{{ $transacao_id > 0 ? number_format($transacao->vr_parcela, 2, ',', '.') : '' }}" maxlength="10" placeholder="000,00" {{ $transacao_id > 0 ? "disabled" : "required" }}>
            </div>
            <div class="col-md-4">
                <label for="subcategoria_id">Categoria</label>
                <select name="subcategoria_id" id="subcategoria_id" class="form-control" required>
                    <option value="0" selected disabled>Selecione ...</option>
                    @foreach ($subcategorias as $sub)
                        <option value="{{ $sub->id }}" {{ $transacao_id > 0 && $sub->id === $transacao->subcategoria_id ? 'selected' : '' }}>
                            {{ $sub->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="tipo_pagamento">Pagamento</label>
                <select name="tipo_pagamento" id="tipo_pagamento" class="form-control" {{ $transacao_id > 0 ? "disabled" : "" }}>
                    <option value="V" {{ $transacao_id > 0 && $transacao->tipo_pagamento === 'V' ? 'selected' : '' }}>À vista</option>
                    <option value="P" {{ $transacao_id > 0 && $transacao->tipo_pagamento === 'P' ? 'selected' : '' }}>Criar parcelas</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="dt_competencia">Competência</label>
                <input type="date" name="dt_competencia" id="dt_competencia" class="form-control" value="{{ $transacao_id > 0 ? \Carbon\Carbon::parse($transacao->dt_competencia)->format('Y-m-d') : '' }}">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-3">
                <label for="nr_documento">Nrº do documento</label>
                <input type="text" name="nr_documento" id="nr_documento" class="form-control" value="{{ $transacao_id > 0 ? $transacao->nr_documento : '' }}" maxlength="30">
            </div>
            <div class="col-md-4">
                <label for="forma_pagamento">Modo de pagamento</label>
                <select name="forma_pagamento" id="forma_pagamento" class="form-control">
                    <option value="0" selected disabled>Selecione ...</option>
                    <option value="A" {{ $transacao_id > 0 && $transacao->forma_pagamento === 'A' ? 'selected' : '' }}>Cartão de crédito</option>
                    <option value="B" {{ $transacao_id > 0 && $transacao->forma_pagamento === 'B' ? 'selected' : '' }}>Boleto</option>
                    <option value="C" {{ $transacao_id > 0 && $transacao->forma_pagamento === 'C' ? 'selected' : '' }}>Cheque</option>
                    <option value="D" {{ $transacao_id > 0 && $transacao->forma_pagamento === 'D' ? 'selected' : '' }}>Dinheiro</option>
                    <option value="E" {{ $transacao_id > 0 && $transacao->forma_pagamento === 'E' ? 'selected' : '' }}>Cartão de débito</option>
                    <option value="F" {{ $transacao_id > 0 && $transacao->forma_pagamento === 'F' ? 'selected' : '' }}>Débito automático</option>
                    <option value="P" {{ $transacao_id > 0 && $transacao->forma_pagamento === 'P' ? 'selected' : '' }}>Promissória</option>
                    <option value="T" {{ $transacao_id > 0 && $transacao->forma_pagamento === 'T' ? 'selected' : '' }}>Transferência</option>
                </select>
            </div>
            <div class="col-md-5">
                <label for="comentarios">Comentários</label>
                <textarea name="comentarios" id="comentarios" class="form-control" rows="2" maxlength="200">{{ $transacao_id > 0 ? $transacao->comentarios : '' }}</textarea>
            </div>
        </div>

        <div class="row">
            <hr class="divider">
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Repetir transação</h4>
                    </div>
                    <div class="col-md-6">
                        <ul>
                            <li>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="repetir_transacao" value="N" checked>Nunca repetir
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="repetir_transacao" value="A">Semanal
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="repetir_transacao" value="B">Quinzenal
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="repetir_transacao" value="C">Mensal
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul>
                            <li>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="repetir_transacao" value="D">Bimestral
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="repetir_transacao" value="E">Trimestral
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="repetir_transacao" value="F">Semestral
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="repetir_transacao" value="G">Anual
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            @if ($transacao_id == 0)
            <div class="col-md-7">
                <div class="row div_parcelamento">
                    <div class="col-md-12">
                        <h4>Parcelas</h4>
                    </div>
                    <div class="col-md-6">
                        <label for="parcelas">Número de parcelas</label>
                        <select name="parcelas" id="parcelas" class="form-control">
                            @for ($i = 2; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $transacao_id > 0 && $tt_parcelas === $i ? 'selected' : '' }}>{{ $i }}x</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="frequencia">Frequência</label>
                        <select name="frequencia" id="frequencia" class="form-control">
                            <option value="semana">Semanal</option>
                            <option value="quinzena">Quinzenal</option>
                            <option value="mes" selected>Mensal</option>
                            <option value="bimestre">Bimestral</option>
                            <option value="trimestre">Trimestral</option>
                            <option value="semestre">Semestral</option>
                            <option value="anual">Anual</option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-sm">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="15%"></th>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tbody_parcelamento">
                                {{-- @if ($transacao_id > 0)
                                    @foreach ($parcelas as $parcela)
                                        <tr>
                                            <td width="15%">{{ $parcela->nr_parcela }} / {{ $tt_parcelas }}</td>
                                            <td>
                                                <input type="date" name="dt_vencimento[]" class="form-control" value="{{ \Carbon\Carbon::parse($parcela->dt_vencimento)->format('Y-m-d') }}" required>
                                            </td>
                                            <td> <input type="text" id="parcela{{ $parcela->nr_parcela }}" name="vr_parcela[]" class="form-control mask-valor vr_parcela" value="{{ number_format($parcela->vr_parcela, 2, ',', '.') }}" required></td>
                                            <td>
                                                <button type="button" class="btn btn-link btn-sm text-default" onclick="removeParcela('{{ $parcela->id }}', '{{ route('transacoes.excluir.parcelas') }}', 'tbody_parcelamento');">
                                                    <i class="fa fa-trash-o fa-fw"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>
    <div class="modal-footer">
        @if ($transacao_id == 0)
            <button type="button" class="btn btn-sm text-bold btn-success btn-spin" onclick="confereParcelamento('form-transacao');">
                <i class="fa fa-check fa-fw"></i>
                Salvar
            </button>
        @else
            @if ($transacao->ds_pago === "N")
                <button type="button" class="btn btn-sm text-bold btn-success btn-spin" onclick="confereParcelamento('form-transacao');">
                    <i class="fa fa-check fa-fw"></i>
                    Salvar
                </button>
            @else
                <button type="button" class="btn btn-sm text-bold btn-success" disabled>
                    <i class="fa fa-check fa-fw"></i>
                    Salvar
                </button>
            @endif
        @endif

        <button type="button" class="btn btn-md btn-default btn-sm modal-close">
            <i class="fa fa-times fa-fw"></i>
            Fechar
        </button>
    </div>
</form>
<script>
    $(document).ready(function(){
        @if ($transacao_id == 0)
            $("#form-transacao .div_parcelamento").hide();
        @else
            @if ($transacao->ds_pago === "N")
                $("#form-transacao .div_parcelamento").show();
            @else
                $("#form-transacao .div_parcelamento").hide();
            @endif
        @endif

        $("#form-transacao #tipo_pagamento").on('change', function(){
            var tp_pagamento = $(this).children(':selected').val();
            var vr_total = $("#form-transacao #vr_total").val();

            if(tp_pagamento === 'V'){
                $("#form-transacao .div_parcelamento #tbody_parcelamento").html("");
                $("#form-transacao .div_parcelamento").hide();
            }else if(tp_pagamento === 'P'){
                if( vr_total === "" ){
                    getMessage('error', "Atenção !!!", 'Informe o valor da transação');
                    $(this).val('V');
                }else{
                    $("#form-transacao .div_parcelamento").show();
                }
            }
        });

        $("#form-transacao .div_parcelamento #parcelas, #form-transacao .div_parcelamento #frequencia").on('change', function(){
            var parcela = $("#form-transacao .div_parcelamento #parcelas").children(':selected').val();
            var frequencia = $("#form-transacao .div_parcelamento #frequencia").children(':selected').val();
            var vr_total = $("#form-transacao #vr_total").val();
            var route = "{{ route('transacoes.ajax.parcelamento') }}";
            var tbody_id = "tbody_parcelamento";

            ajaxTransacao(route, parcela, frequencia, vr_total, tbody_id, 'create-edit');
        });
    });

    function confereParcelamento(form_id){
        var valor_total = $("#form-transacao #vr_total").val();
        var parcelamento = $("#form-transacao .div_parcelamento #parcelas").children(':selected').val();
        var tp_pagamento = $("#form-transacao #tipo_pagamento").children(':selected').val();
        var vr_parcela = 0;
        var total_parcelas = 0;
        var diferenca = 0;
        var inputs = $("#tbody_parcelamento .vr_parcela");
        var transacao_id = @json($transacao_id);

        inputs.each(function( index ) {
            vr_parcela = $(this).val();

            if( vr_parcela === '' ){
                getMessage('error', "Atenção !!!", 'Informe o valor da parcela');
            }else{
                vr_parcela = vr_parcela.replace(',', '.');
                total_parcelas = (total_parcelas + parseFloat(vr_parcela));
            }
        });

        $("#tbody_parcelamento .soma_parcelas").text(total_parcelas.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

        if( valor_total === "" ){
            getMessage('error', "Atenção !!!", 'Informe o valor da transação');
        }

        valor_total = valor_total.replace(',', '.');
        valor_total = parseFloat(valor_total);

        if( tp_pagamento === "V" ){
            if( valor_total === "" ){
                getMessage('error', "Atenção !!!", 'Informe o valor da transação');
            }else{
                submitForm(form_id);
            }
        }else if( tp_pagamento === "P" ){
            if( transacao_id > 0 ){
                submitForm(form_id);
            }else{
                if( total_parcelas < valor_total ){
                    diferenca = (valor_total - total_parcelas);

                    inputs.each(function( index ) {
                        if( (index+1) === parseInt(parcelamento) ){
                            vr_parcela = ( parseFloat(vr_parcela) + diferenca );
                            $("#tbody_parcelamento #parcela"+parcelamento).val(vr_parcela.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                        }
                    });

                    if( total_parcelas === valor_total ){
                        submitForm(form_id);
                    }
                }else if( total_parcelas > valor_total ){
                    diferenca = (total_parcelas - valor_total);

                    inputs.each(function( index ) {
                        if( (index+1) === parseInt(parcelamento) ){
                            vr_parcela = ( parseFloat(vr_parcela) - diferenca );
                            $("#tbody_parcelamento #parcela"+parcelamento).val(vr_parcela.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                        }
                    });

                    if( total_parcelas === valor_total ){
                        submitForm(form_id);
                    }
                }else if( total_parcelas === valor_total ){
                    submitForm(form_id);
                }
            }
        }
    }
</script>
