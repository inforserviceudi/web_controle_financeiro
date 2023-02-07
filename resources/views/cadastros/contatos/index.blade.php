@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <style>
        table.table{
            background-color: #FFF;
        }
    </style>
    <header class="page-header">
        <h2>Empresas</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="{{ route('dashboard') }}" title="Voltar para a tela inicial">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Contatos</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="row">
        <div class="col-md-9">
            <h3>Lista de contatos</h3>
        </div>
        <div class="col-md-3 text-right">
            <button id="btn_novo_registro" class="btn btn-default btn-sm mt-xl modal-call" data-id="0" data-width="modal-md" data-url="{{ route("contatos.modal.create-edit") }}" title="Novo registro">
                <i class="fa fa-plus fa-fw"></i>
                Novo registro
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <section class="panel">
                <div class="panel-body">
                    <table id="dataTableIndex" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Contato</th>
                                <th>Tipo</th>
                                <th width="12%" class="text-center">Ações</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </section>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(function(){
                var colunas = Array('Contato', 'Tipo', 'Ações');
                var empresa_id = @json($empresa_id);

                $("#dataTableIndex").DataTable({
                    language: {
                        // "url": "https://raw.githubusercontent.com/DataTables/Plugins/master/i18n/pt_br.json",
                        "decimal": ",",
                        "thousands": "."
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('contatos.dataTable') }}",
                        data: { empresa_id:empresa_id }
                    },
                    initComplete: function( settings, json ) {
                        $('#dataTableIndex_filter label input[type=search]').attr('placeholder', 'Filtrar ...');
                    },
                    pageLength: 10,
                    order: [
                        [0, "desc" ]
                    ],
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, 'Tudo']
                    ],
                    // dom: 'Bflrtp',
                    autoWidth: !1,
                    createdRow: function( row, data, rowIndex ) {
                        $.each($('td', row), function (colIndex) {
                            $(this).attr('data-title', colunas[colIndex])
                        });
                    },
                    "columnDefs": [
                        {
                            className: "dt[-head|-body]-center",
                            targets: [ 0 ]
                        }
                    ],
                    columns: [
                        {
                            data: 'rz_social',
                            name: 'rz_social',
                        },
                        {
                            data: 'tp_contato',
                            name: 'tp_contato',
                        },
                        {
                            data: 'acoes',
                            name: 'acoes',
                        },
                    ]
                });
            });
        });
    </script>
</section>
@endsection
