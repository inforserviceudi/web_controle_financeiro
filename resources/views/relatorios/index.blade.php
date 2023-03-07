@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <style>
        @media print {
            @page { size: portrait;  margin: 1mm; }
            .print{ display: block; }
            .no-print{ display: none; }
            .border-top{ border-top: 2px solid #CCC; }
            #tabela_relatorio{
                min-width: 900px;
                width: 100%
                border-collapse: collapse;
                font-size: 12pt;
                margin-top: 20px;
            }
            #tabela_relatorio th {
                background-color: #ccc;
                font-weight: bold;
                text-align: left;
            }
            #tabela_relatorio td {
                border: 1px solid #000;
                padding: 5px;
            }
        }

    </style>
    <header class="page-header no-print">
        <h2>Relatórios</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Relatórios</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="panel mt-lg">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6 no-print">
                    <form id="form-relatorio" action="{{ route('relatorios.filtrar') }}" method="post" class="form">@csrf
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label for="dt_inicial">Data inicial</label>
                                <input type="date" name="dt_inicial" id="dt_inicial" class="form-control" value="{{ $dt_inicial }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="dt_final">Data final</label>
                                <input type="date" name="dt_final" id="dt_final" class="form-control" value="{{ $dt_final }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="ds_pago">Incluir lançamentos</label>
                                <select name="ds_pago" id="ds_pago" class="form-control">
                                    <option value="A"> Ambos </option>
                                    <option value="S"> Pagos </option>
                                    <option value="N"> Não Pagos </option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label for="conta_id">Conta</label>
                                <select name="conta_id" id="conta_id" class="form-control" required>
                                    <option value="0" selected disabled> SELECIONE ... </option>
                                    @foreach( $contas as $conta )
                                    <option value="{{ $conta->id }}"> {{ $conta->ds_conta }} </option>
                                    @endforeach
                                    <option value="T"> Todas </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="mostrar_data">Mostrar por data de:</label>
                                <select name="mostrar_data" id="mostrar_data" class="form-control">
                                    <option value="dt_pagamento"> Pagamento </option>
                                    <option value="dt_competencia"> Competência </option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label for="tp_relatorio">Tipo de relatório</label>
                                <select name="tp_relatorio" id="tp_relatorio" class="form-control">
                                    <option value="D"> Despesas </option>
                                    <option value="R"> Recebimentos </option>
                                    <option value="F"> Fluxo de caixa </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="tp_filtro">Filtrar por:</label>
                                <select name="tp_filtro" id="tp_filtro" class="form-control">
                                    <option value="0" selected disabled> SELECIONE ... </option>
                                    @foreach ($despesas as $item)
                                    <option value="{{ $item }}"> {{ $item }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="row print">
                        <div class="col-md-12">
                            <p>Empresa: xxxxxxxxxxx</p>
                            <p>Conta: xxxxxxxxxxx</p>
                            <p>Período: xxxxxxxxxxx</p>
                        </div>
                    </div>
                    <div class="table-responsive print">
                        <table class="table table-hover table-bordered" id="tabela_relatorio"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#form-relatorio #tp_relatorio").on("change", function(){
                var tp_relatorio = $(this).children(":selected").val();
                var arr_despesas = @json($despesas);
                var arr_recebimentos = @json($recebimentos);
                var arr_fluxo_caixa = @json($fluxo_caixa);

                $("#form-relatorio #tp_filtro").empty(); //limpa o selectbox
                $("#form-relatorio #tp_filtro").append('<option value="0" selected disabled> SELECIONE ... </option>');

                if( tp_relatorio === "D" ){
                    $.each(arr_despesas, function (key, value) {
                        $("#form-relatori  #tp_filtro").append('<option value="'+value+'"> ' + value + ' </option>');
                    });
                }else if( tp_relatorio === "R" ){
                    $.each(arr_recebimentos, function (key, value) {
                        $("#form-relatorio #tp_filtro").append('<option value="'+value+'"> ' + value + ' </option>');
                    });
                }else if( tp_relatorio === "F" ){
                    $.each(arr_fluxo_caixa, function (key, value) {
                        $("#form-relatorio #tp_filtro").append('<option value="'+value+'"> ' + value + ' </option>');
                    });
                }

            });

            $("#form-relatorio #tp_filtro").on("change", function(){
                submitForm("form-relatorio");
            });

            $(document).delegate("#btn_relatorio", "click", function(){
                window.print();
            });
        });
    </script>
</section>
@endsection
