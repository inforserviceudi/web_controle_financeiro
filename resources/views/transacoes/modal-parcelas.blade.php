<style>
    .mtb-3 {
        margin: 3% 0;
    }
    .mtb-5 {
        margin: 5% 0;
    }
    form label {
        font-weight: bold;
    }
    .text-default {
        color: #CCC;
    }
    .text-default:hover {
        color: #d2322d;
    }
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 21px;
        top: 5px;
    }
    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 13px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked+.slider {
        background-color: #47A447;
    }
    input:focus+.slider {
        box-shadow: 0 0 1px #47A447;
    }
    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }
    .slider.round:before {
        border-radius: 50%;
    }
</style>
<div class="modal-header">
    <div class="row">
        <div class="col-md-11">
            <h4 class="modal-title">Parcelas</h4>
        </div>
        <div class="col-md-1">
            <a href="javascript:void(0);" type="button" class="modal-close">
                <i class="fa fa-times fa-fw text-danger"></i>
            </a>
        </div>
    </div>
</div>

<form id="form-transacao" action="{{ route('transacoes.atualiza.parcelas') }}" method="post" class="form-horizontal"
    novalidate="novalidate">@csrf
    <div class="modal-body">
        <input type="hidden" name="empresa_id" value="{{ $empresa_id }}">
        <input type="hidden" name="categoria_id" value="{{ $categoria_id }}">
        <input type="hidden" name="conta_id" value="{{ $conta_id }}">
        <input type="hidden" id="transacao_id" name="transacao_id" value="{{ $transacao_id }}">

        <div class="row">
            <div class="col-md-3">
                <label for="vr_total">Valor total</label>
                <input type="text" id="vr_total" class="form-control mask-valor"
                    value="{{ number_format($vr_total, 2, ',', '.') }}" maxlength="10" placeholder="000,00" disabled>
            </div>
            <div class="col-md-4">
                <label for="parcelas">Número de parcelas</label>
                <select name="parcelas" id="parcelas" class="form-control">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $tt_parcelas === $i ? 'selected' : '' }}>
                            {{ $i === 1 ? 'À vista' : $i . 'x' }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-5">
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
                            <th>Pago?</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tbody_parcelamento">
                        @foreach ($parcelas as $parcela)
                            <tr>
                                <td width="15%">{{ $parcela->nr_parcela }} / {{ $tt_parcelas }}</td>
                                <td>
                                    <input type="date" name="dt_vencimento[]" class="form-control"
                                        value="{{ \Carbon\Carbon::parse($parcela->dt_vencimento)->format('Y-m-d') }}"
                                        required {{ $parcela->ds_pago === 'S' ? 'readonly' : '' }}>
                                </td>
                                <td> <input type="text" id="parcela{{ $parcela->nr_parcela }}" name="vr_parcela[]"
                                        class="form-control mask-valor vr_parcela"
                                        value="{{ number_format($parcela->vr_parcela, 2, ',', '.') }}"
                                        required {{ $parcela->ds_pago === 'S' ? 'readonly' : '' }}>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="ds_pago" data-plugin-ios-switch
                                            {{ $parcela->ds_pago === 'S' ? 'checked' : '' }}
                                            onclick="informarPagamento('{{ route('transacoes.informar.pagamento') }}', 'tbody_parcelamento', '{{ $parcela->id }}');"/>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    @if ($parcela->ds_pago === 'N')
                                        <button type="button" class="btn btn-link btn-sm text-default"
                                            onclick="removeParcela('{{ $parcela->id }}', '{{ route('transacoes.excluir.parcelas') }}', 'tbody_parcelamento');">
                                            <i class="fa fa-trash-o fa-fw"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm text-bold btn-success btn-spin"
            onclick="confereParcelamento('form-transacao');">
            <i class="fa fa-check fa-fw"></i>
            Salvar
        </button>
        <button type="button" class="btn btn-md btn-default btn-sm modal-close">
            <i class="fa fa-times fa-fw"></i>
            Fechar
        </button>
    </div>
</form>
<script src="{{ asset('vendor/ios7-switch/ios7-switch.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#form-transacao #parcelas, #form-transacao #frequencia").on('change', function() {
            var parcela = $("#form-transacao #parcelas").children(':selected').val();
            var frequencia = $("#form-transacao #frequencia").children(':selected').val();
            var vr_total = $("#form-transacao #vr_total").val();
            var transacao_id = $("#form-transacao #transacao_id").val();
            var route = "{{ route('transacoes.ajax.parcelamento') }}";
            var tbody_id = "tbody_parcelamento";

            ajaxTransacao(route, parcela, frequencia, vr_total, tbody_id, 'parcelas', transacao_id);
        });
    });

    function confereParcelamento(form_id) {
        var valor_total = $("#form-transacao #vr_total").val();
        var parcelamento = $("#form-transacao #parcelas").children(':selected').val();
        var tp_pagamento = $("#form-transacao #tipo_pagamento").children(':selected').val();
        var vr_parcela = 0;
        var total_parcelas = 0;
        var diferenca = 0;
        var inputs = $("#tbody_parcelamento .vr_parcela");
        var transacao_id = @json($transacao_id);

        inputs.each(function(index) {
            vr_parcela = $(this).val();

            if (vr_parcela === '') {
                getMessage('error', "Atenção !!!", 'Informe o valor da parcela');
            } else {
                vr_parcela = vr_parcela.replace(',', '.');
                total_parcelas = (total_parcelas + parseFloat(vr_parcela));
            }
        });

        $("#tbody_parcelamento .soma_parcelas").text(total_parcelas.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));

        if (valor_total === "") {
            getMessage('error', "Atenção !!!", 'Informe o valor da transação');
        }

        valor_total = valor_total.replace(',', '.');
        valor_total = parseFloat(valor_total);

        if (tp_pagamento === "V") {
            if (valor_total === "") {
                getMessage('error', "Atenção !!!", 'Informe o valor da transação');
            } else {
                submitForm(form_id);
            }
        } else if (tp_pagamento === "P") {
            if (transacao_id > 0) {
                submitForm(form_id);
            } else {
                if (total_parcelas < valor_total) {
                    diferenca = (valor_total - total_parcelas);

                    inputs.each(function(index) {
                        if ((index + 1) === parseInt(parcelamento)) {
                            vr_parcela = (parseFloat(vr_parcela) + diferenca);
                            $("#tbody_parcelamento #parcela" + parcelamento).val(vr_parcela.toLocaleString(
                                'pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                        }
                    });

                    if (total_parcelas === valor_total) {
                        submitForm(form_id);
                    }
                } else if (total_parcelas > valor_total) {
                    diferenca = (total_parcelas - valor_total);

                    inputs.each(function(index) {
                        if ((index + 1) === parseInt(parcelamento)) {
                            vr_parcela = (parseFloat(vr_parcela) - diferenca);
                            $("#tbody_parcelamento #parcela" + parcelamento).val(vr_parcela.toLocaleString(
                                'pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                        }
                    });

                    if (total_parcelas === valor_total) {
                        submitForm(form_id);
                    }
                } else if (total_parcelas === valor_total) {
                    submitForm(form_id);
                }
            }
        }
    }
</script>
