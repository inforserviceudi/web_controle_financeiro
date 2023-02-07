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
