<style>
    .mtb-3{ margin: 3% 0; }
    .mtb-5{ margin: 5% 0; }
</style>
<div class="modal-body text-center">
    <h4>Você deseja remover este registro?</h4>

    <div class="row mtb-3">
        <div class="col-md-12">
            <a type="button" href="{{ route('contatos.remove.registro', ['id'=>$id]) }}" class="btn btn-danger btn-sm">
                <i class="fa fa-trash-o fa-fw"></i>
                Deletar
            </a>
            <button type="button" class="btn btn-default btn-sm modal-close">
                <i class="fa fa-times fa-fw"></i>
                Fechar
            </button>
        </div>
    </div>
</div>
