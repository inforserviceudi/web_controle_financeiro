<style>
    .mtb-3{ margin: 3% 0; }
    .mtb-5{ margin: 5% 0; }
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
</div>

@if( $id == 0 )
<form id="form-contato" action="{{ route('contatos.insere.registro') }}" method="post" class="form-horizontal" novalidate="novalidate">@csrf
@else
<form id="form-contato" action="{{ route('contatos.atualiza.registro', ['id' => $id]) }}" method="post" class="form-horizontal" novalidate="novalidate">@csrf
@endif
    <div class="modal-body">
        <div class="row">
            <div class="form-group">
                <label class="col-md-3 control-label text-bold">Tipo: </label>
                <div class="col-md-9">
                    <label class="checkbox-inline">
                        <input type="radio" id="pessoa_fisica" name="tp_pessoa" value="F" {{ $id > 0 && $contato->tp_pessoa === "F" ? "checked" : "" }}>
                        Pessoa Física
                    </label>
                    <label class="checkbox-inline">
                        <input type="radio" id="pessoa_jurifica" name="tp_pessoa" value="J" {{ $id > 0 && $contato->tp_pessoa === "J" ? "checked" : "" }}>
                        Pessoa Jurídica
                    </label>
                </div>
            </div>
        </div>
        <div class="row mt-sm">
            <div class="form-group">
                <label class="col-md-3 control-label text-bold">Categoria: </label>
                <div class="col-md-9">
                    <label class="checkbox-inline">
                        <input type="radio" id="tp_contato1" name="tp_contato" value="1" {{ $id > 0 && $contato->tp_contato == "1" ? "checked" : "" }}>
                        Cliente
                    </label>
                    <label class="checkbox-inline">
                        <input type="radio" id="tp_contato2" name="tp_contato" value="2" {{ $id > 0 && $contato->tp_contato == "2" ? "checked" : "" }}>
                        Fornecedor
                    </label>
                    <label class="checkbox-inline">
                        <input type="radio" id="tp_contato3" name="tp_contato" value="3" {{ $id > 0 && $contato->tp_contato == "3" ? "checked" : "" }}>
                        Funcionário
                    </label>
                </div>
            </div>
        </div>

        <div class="{{ $id == 0 ? 'body-hide' : '' }}">
            <div class="row mt-sm">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">Nome fantasia: </label>
                        <div class="col-md-9">
                            <input type="text" name="nm_fantasia" id="nm_fantasia" class="form-control" value="{{ $id > 0 ? $contato->nm_fantasia : "" }}" maxlength="150" placeholder="Nome da empresa">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-sm">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">Razão Social: </label>
                        <div class="col-md-9">
                            <input type="text" name="rz_social" id="rz_social" class="form-control" value="{{ $id > 0 ? $contato->rz_social : "" }}" maxlength="150" placeholder="Razão social" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-sm esconder_juridico">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">CNPJ: </label>
                        <div class="col-md-9">
                            <input type="text" name="cpf_cnpj" id="ds_cnpj" class="form-control" value="{{ $id > 0 ? $contato->cpf_cnpj : "" }}" maxlength="18" placeholder="Informe o CNPJ">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-sm esconder_fisico">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">CPF: </label>
                        <div class="col-md-9">
                            <input type="text" name="cpf_cnpj" id="ds_cpf" class="form-control" value="{{ $id > 0 ? $contato->cpf_cnpj : "" }}" maxlength="14" placeholder="Informe o CPF">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-sm esconder_juridico">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">Inscrição estadual: </label>
                        <div class="col-md-9">
                            <input type="text" name="ds_insc_estadual" id="ds_insc_estadual" class="form-control" value="{{ $id > 0 ? $contato->ds_insc_estadual : "" }}" maxlength="50" placeholder="Informe a inscrição estadual">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-sm">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">E-mail: </label>
                        <div class="col-md-9">
                            <input type="email" name="ds_email" id="ds_email" class="form-control" value="{{ $id > 0 ? $contato->ds_email : "" }}" maxlength="100" placeholder="Informe o e-mail">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-sm">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">Telefones: </label>
                        <div class="col-md-4">
                            <input type="text" name="ds_telefone" id="ds_telefone" class="form-control mask-ddd_telefone" value="{{ $id > 0 ? $contato->ds_telefone : "" }}" maxlength="14" placeholder="Comercial">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="ds_celular" id="ds_celular" class="form-control mask-ddd_celular" value="{{ $id > 0 ? $contato->ds_celular : "" }}" maxlength="15" placeholder="Celular">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-sm">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">Contato: </label>
                        <div class="col-md-9">
                            <input type="text" name="nm_contato_emp" id="nm_contato_emp" class="form-control" value="{{ $id > 0 ? $contato->nm_contato_emp : "" }}" maxlength="150" placeholder="Nome do contato na empresa">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-sm">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">Endereço: </label>
                        <div class="col-md-7">
                            <input type="text" name="ds_endereco" id="ds_endereco" class="form-control" value="{{ $id > 0 ? $contato->ds_endereco : "" }}" maxlength="150" placeholder="Rua  / Avenida / Quadra">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="nr_endereco" id="nr_endereco" class="form-control" value="{{ $id > 0 ? $contato->nr_endereco : "" }}" maxlength="8" placeholder="Número">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-sm">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">Complemento: </label>
                        <div class="col-md-9">
                            <input type="text" name="ds_complemento" id="ds_complemento" class="form-control" value="{{ $id > 0 ? $contato->ds_complemento : "" }}" maxlength="50" placeholder="Complemento do endereço">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-sm">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">Bairro: </label>
                        <div class="col-md-6">
                            <input type="text" name="ds_bairro" id="ds_bairro" class="form-control" value="{{ $id > 0 ? $contato->ds_bairro : "" }}" maxlength="50" placeholder="Bairro">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="ds_cep" id="ds_cep" class="form-control mask-cep" value="{{ $id > 0 ? $contato->ds_cep : "" }}" maxlength="9" placeholder="CEP">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-sm">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">Estado: </label>
                        <div class="col-md-4">
                            <select name="estado_id" id="estado_id" class="form-control js-single">
                                <option value="0" selected disabled>Estado</option>
                                @foreach ($estados as $estado)
                                <option value="{{ $estado->id }}" {{ $id > 0 && $estado->id === $contato->estado_id ? "selected" : "" }}>
                                    {{ $estado->ds_sigla }} - {{ $estado->nm_estado }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <select name="cidade_id" id="cidade_id" class="form-control js-single">
                                <option value="0" selected disabled>Cidade</option>
                                @foreach ($cidades as $cidade)
                                <option value="{{ $cidade->id }}" {{ $id > 0 && $cidade->id === $contato->cidade_id ? "selected" : "" }}>
                                    {{ $cidade->nm_cidade }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-sm">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-bold">Descrição: </label>
                        <div class="col-md-9">
                            <textarea type="text" name="ds_descricao" id="ds_descricao" class="form-control" rows="2" maxlength="500">
                                {{ $id > 0 ? $contato->ds_descricao : "" }}
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm text-bold btn-success btn-spin" onclick="submitForm('form-contato');">
            <i class="fa fa-check fa-fw"></i>
            Salvar
        </button>
        <button type="button" class="btn btn-md btn-default btn-sm modal-close">
            <i class="fa fa-times fa-fw"></i>
            Fechar
        </button>
    </div>
</form>
<script>
    $(document).ready(function(){
        $(".body-hide").hide();

        @if( $id > 0 )
            var pessoa = @json($contato->tp_pessoa);

            if( pessoa === "F" ){
                $(".esconder_juridico").hide();
                $(".esconder_fisico").show();
            }else if( pessoa === "J" ){
                $(".esconder_juridico").show();
                $(".esconder_fisico").hide();
            }
        @endif

        $("input[name=tp_pessoa]").on("click", function(){
            var tp_pessoa = $(this).val();

            if( tp_pessoa === "F" ){
                $(".esconder_juridico").hide();
                $(".esconder_fisico").show();
            }else if( tp_pessoa === "J" ){
                $(".esconder_juridico").show();
                $(".esconder_fisico").hide();
            }

            $(".body-hide").show();
        });
    });
</script>
