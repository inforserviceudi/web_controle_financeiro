@extends('layouts.app')

@section('content')
<section id="section_permissoes" role="main" class="content-body">
    <header class="page-header">
        <h2>Empresas</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li>
                    <a href="{{ route('empresas.index') }}">
                        <span>Empresas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('usuarios.index', ['empresa_id' => 3]) }}">
                        <span>Usuários</span>
                    </a>
                </li>
                <li><span>Permissões</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="row">
        <div class="col-md-9">
            <h3>Permissões de {{ $user->name }}</h3>
        </div>
        <div class="col-md-3 text-right">
            <a href="{{ route('usuarios.index', ['empresa_id' => $empresa_id]) }}" type="button" class="btn btn-default btn-sm mt-xl" title="Voltar para a tela de empresas">
                <i class="fa fa-angle-double-left fa-fw"></i>
                <span>Sair</span>
            </a>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label mt-xl text-bold" for="usuario_id">Selecionar outro usuário</label>
        <div class="col-md-4">
            <select name="usuario_id" id="usuario_id" class="form-control mt-xl">
                @foreach ($usuarios as $item)
                <option value="{{ $item->id }}" {{ $item->id === $user->id ? "selected" : "" }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <section class="panel panel-featured">
                <div class="panel-body">
                    <form id="form-permissao" action="{{ route('usuarios.atualiza.permissoes', ['usuario_id'=>$user->id]) }}" method="post" class="form">

                        <div class="row form-group">
                            <div class="col-md-4">
                                <section class="panel">
                                    <header class="panel-heading">
                                        <h4 class="panel-title">Recebimentos</h4>
                                    </header>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="ver_aba_recebimento" {{ $permissao && $permissao->ver_aba_recebimento === "S" ? "checked" : "" }}>
                                                Visualizar esta aba
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="incluir_movimentacao_recebimento" {{ $permissao && $permissao->incluir_movimentacao_recebimento === "S" ? "checked" : "" }}>
                                                Incluir movimentação
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="excluir_movimentacao_recebimento" {{ $permissao && $permissao->excluir_movimentacao_recebimento === "S" ? "checked" : "" }}>
                                                Excluir movimentação
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="alterar_movimentacao_recebimento" {{ $permissao && $permissao->alterar_movimentacao_recebimento === "S" ? "checked" : "" }}>
                                                Alterar movimentação
                                            </label>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-md-4">
                                <section class="panel">
                                    <header class="panel-heading">
                                        <h4 class="panel-title">Despesas fixas</h4>
                                    </header>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="ver_aba_despfixa" {{ $permissao && $permissao->ver_aba_despfixa === "S" ? "checked" : "" }}>
                                                Visualizar esta aba
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="incluir_movimentacao_despfixa" {{ $permissao && $permissao->incluir_movimentacao_despfixa === "S" ? "checked" : "" }}>
                                                Incluir movimentação
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="excluir_movimentacao_despfixa" {{ $permissao && $permissao->excluir_movimentacao_despfixa === "S" ? "checked" : "" }}>
                                                Excluir movimentação
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="alterar_movimentacao_despfixa" {{ $permissao && $permissao->alterar_movimentacao_despfixa === "S" ? "checked" : "" }}>
                                                Alterar movimentação
                                            </label>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-md-4">
                                <section class="panel">
                                    <header class="panel-heading">
                                        <h4 class="panel-title">Despesas variáveis</h4>
                                    </header>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="ver_aba_despvariavel" {{ $permissao && $permissao->ver_aba_despvariavel === "S" ? "checked" : "" }}>
                                                Visualizar esta aba
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="incluir_movimentacao_despvariavel" {{ $permissao && $permissao->incluir_movimentacao_despvariavel === "S" ? "checked" : "" }}>
                                                Incluir movimentação
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="excluir_movimentacao_despvariavel" {{ $permissao && $permissao->excluir_movimentacao_despvariavel === "S" ? "checked" : "" }}>
                                                Excluir movimentação
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="alterar_movimentacao_despvariavel" {{ $permissao && $permissao->alterar_movimentacao_despvariavel === "S" ? "checked" : "" }}>
                                                Alterar movimentação
                                            </label>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <section class="panel">
                                    <header class="panel-heading">
                                        <h4 class="panel-title">Pessoas</h4>
                                    </header>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="ver_aba_pessoas" {{ $permissao && $permissao->ver_aba_pessoas === "S" ? "checked" : "" }}>
                                                Visualizar esta aba
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="incluir_movimentacao_pessoas" {{ $permissao && $permissao->incluir_movimentacao_pessoas === "S" ? "checked" : "" }}>
                                                Incluir movimentação
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="excluir_movimentacao_pessoas" {{ $permissao && $permissao->excluir_movimentacao_pessoas === "S" ? "checked" : "" }}>
                                                Excluir movimentação
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="alterar_movimentacao_pessoas" {{ $permissao && $permissao->alterar_movimentacao_pessoas === "S" ? "checked" : "" }}>
                                                Alterar movimentação
                                            </label>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-md-4">
                                <section class="panel">
                                    <header class="panel-heading">
                                        <h4 class="panel-title">Impostos</h4>
                                    </header>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="ver_aba_impostos" {{ $permissao && $permissao->ver_aba_impostos === "S" ? "checked" : "" }}>
                                                Visualizar esta aba
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="incluir_movimentacao_impostos" {{ $permissao && $permissao->incluir_movimentacao_impostos === "S" ? "checked" : "" }}>
                                                Incluir movimentação
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="excluir_movimentacao_impostos" {{ $permissao && $permissao->excluir_movimentacao_impostos === "S" ? "checked" : "" }}>
                                                Excluir movimentação
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="alterar_movimentacao_impostos" {{ $permissao && $permissao->alterar_movimentacao_impostos === "S" ? "checked" : "" }}>
                                                Alterar movimentação
                                            </label>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-md-4">
                                <section class="panel">
                                    <header class="panel-heading">
                                        <h4 class="panel-title">Transferências</h4>
                                    </header>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="ver_aba_transferencia" {{ $permissao && $permissao->ver_aba_transferencia === "S" ? "checked" : "" }}>
                                                Visualizar esta aba
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="incluir_movimentacao_transferencia" {{ $permissao && $permissao->incluir_movimentacao_transferencia === "S" ? "checked" : "" }}>
                                                Incluir movimentação
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="excluir_movimentacao_transferencia" {{ $permissao && $permissao->excluir_movimentacao_transferencia === "S" ? "checked" : "" }}>
                                                Excluir movimentação
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="alterar_movimentacao_transferencia" {{ $permissao && $permissao->alterar_movimentacao_transferencia === "S" ? "checked" : "" }}>
                                                Alterar movimentação
                                            </label>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>

                        <hr class="divider">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="permissao_usuarios" {{ $permissao && $permissao->permissao_usuarios === "S" ? "checked" : "" }}>
                                        Dar permissão a outros usuários
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="importar_arquivos" {{ $permissao && $permissao->importar_arquivos === "S" ? "checked" : "" }}>
                                        Importar arquivos (OFX e planilhas)
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="fazer_backup" {{ $permissao && $permissao->fazer_backup === "S" ? "checked" : "" }}>
                                        Fazer backup
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="alterar_dados_empresa" {{ $permissao && $permissao->alterar_dados_empresa === "S" ? "checked" : "" }}>
                                        Alterar dados da empresa
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="upload_arquivos" {{ $permissao && $permissao->upload_arquivos === "S" ? "checked" : "" }}>
                                        Uploads de arquivos
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="gerenciar_clientes_fornecedores" {{ $permissao && $permissao->gerenciar_clientes_fornecedores === "S" ? "checked" : "" }}>
                                        Gerenciar clientes e fornecedores
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="ver_relatorios" {{ $permissao && $permissao->ver_relatorios === "S" ? "checked" : "" }}>
                                        Visualizar relatórios
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="ver_tela_resumo" {{ $permissao && $permissao->ver_tela_resumo === "S" ? "checked" : "" }}>
                                        Visualizar tela de resumo
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="ver_arquivos" {{ $permissao && $permissao->ver_arquivos === "S" ? "checked" : "" }}>
                                        Visualizar / baixar arquivos
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="gerenciar_categorias" {{ $permissao && $permissao->gerenciar_categorias === "S" ? "checked" : "" }}>
                                        Gerenciar categorias
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="gerencias_contas" {{ $permissao && $permissao->gerencias_contas === "S" ? "checked" : "" }}>
                                        Gerencias contas
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="ver_saldo" {{ $permissao && $permissao->ver_saldo === "S" ? "checked" : "" }}>
                                        Visualizar saldo
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="excluir_arquivos" {{ $permissao && $permissao->excluir_arquivos === "S" ? "checked" : "" }}>
                                        Excluir arquivos
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-success btn-sm btn-spin" title="Salvar registro" onclick="submitForm('form-permissao')">
                                    <i class="fa fa-check fa-fw"></i>
                                    Salvar
                                </button>
                                <a href="{{ route('usuarios.index', ['empresa_id' => $empresa_id]) }}" type="button" class="btn btn-default btn-sm">
                                    <i class="fa fa-angle-double-left fa-fw"></i>
                                    <span>Sair</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <form id="form-trocar-usuario" method="get"></form>

    <script>
        $(document).ready(function(){
            $("#section_permissoes #usuario_id").on("change", function(){
                var usuario_id = $(this).children(':selected').val();
                var base_url = window.location.origin;
                var route = base_url + "/usuarios/"+usuario_id+"/permissoes";

                if( usuario_id > 0){
                    $("#section_permissoes #form-trocar-usuario").attr('action', route).submit();
                }
            });
        });
    </script>
</section>
@endsection
