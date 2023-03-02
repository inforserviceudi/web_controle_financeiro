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
</style>
<div class="modal-header">
    <div class="row">
        <div class="col-md-11">
            <h4 class="modal-title">Transferência</h4>
        </div>
        <div class="col-md-1">
            <a href="javascript:void(0);" type="button" class="modal-close">
                <i class="fa fa-times fa-fw text-danger"></i>
            </a>
        </div>
    </div>
</div>

<form id="form-transacao" action="{{ route('transacoes.gera.transferencia') }}" method="post" class="form-horizontal" novalidate="novalidate">@csrf
    <div class="modal-body">
        <input type="hidden" name="empresa_id" value="{{ $empresa_id }}">
        <div class="row form-group">
            <div class="col-md-3">
                <label for="dt_transacao">Data</label>
                <input type="date" name="dt_transacao" id="dt_transacao" class="form-control" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
                <label for="descricao">Descrição</label>
                <input type="text" name="descricao" id="descricao" class="form-control" maxlength="150" required>
            </div>
            <div class="col-md-3">
                <label for="vr_total">Valor</label>
                <input type="text" name="vr_total" id="vr_total" class="form-control mask-valor" maxlength="10" required>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-4">
                <label for="conta_origem_id">Conta de origem</label>
                <select name="conta_origem_id" id="conta_origem_id" class="form-control" required>
                    <option value="0" selected disabled>SELECIONE ...</option>
                    @foreach ($contas as $conta)
                    <option value="{{ $conta->id }}">{{ $conta->ds_conta }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="conta_destino_id">Conta de destino</label>
                <select name="conta_destino_id" id="conta_destino_id" class="form-control" required>
                    <option value="0" selected disabled>SELECIONE ...</option>
                    @foreach ($contas as $conta)
                    <option value="{{ $conta->id }}">{{ $conta->ds_conta }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm text-bold btn-success btn-spin"
            onclick="submitForm('form-transacao');">
            <i class="fa fa-check fa-fw"></i>
            Salvar
        </button>
        <button type="button" class="btn btn-md btn-default btn-sm modal-close">
            <i class="fa fa-times fa-fw"></i>
            Fechar
        </button>
    </div>
</form>
