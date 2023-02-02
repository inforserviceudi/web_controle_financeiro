<style>
    .mtb-3{ margin: 3% 0; }
    .mtb-5{ margin: 5% 0; }
</style>
<div class="modal-header">
    <h4>Relatório de logs</h4>
    <h5>Ações realizadas pelo usuário: <strong>{{ $nm_usuario }}</strong> </h5>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <table id="dataTableIndex" class="table table-hover">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Ação</th>
                        <th>Mensagem</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-md btn-default btn-sm modal-close">
        <i class="fa fa-times fa-fw"></i>
        Fechar
    </button>
</div>
<script>
    $(document).ready(function(){
        $(function(){
            var colunas = Array('Data', 'Ação', 'Mensagem');
            var empresa_id = @json($empresa_id);
            var usuario_id = @json($usuario_id);

            $("#dataTableIndex").DataTable({
                language: {
                    // "url": "https://raw.githubusercontent.com/DataTables/Plugins/master/i18n/pt_br.json",
                    "decimal": ",",
                    "thousands": "."
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('usuarios.dataTable', ['empresa_id'=> "+empresa_id+"]) }}",
                    data: { usuario_id:usuario_id, empresa_id:empresa_id }
                },
                initComplete: function( settings, json ) {
                    $('input.input-sm').attr('placeholder', 'Filtrar ...');
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
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'ds_acao',
                        name: 'ds_acao',
                    },
                    {
                        data: 'ds_mensagem',
                        name: 'ds_mensagem',
                    },
                ]
            });
        });
    });
</script>
