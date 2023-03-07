@php
    $empresa_id = getIdEmpresa();
@endphp
<div class="inner-wrapper no-print">
    <!-- start: sidebar -->
    <aside id="sidebar-left" class="sidebar-left">

        <div class="sidebar-header">
            <div class="sidebar-title">
                Navigation
            </div>
            <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
                <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
            </div>
        </div>

        <div class="nano">
            <div class="nano-content">
                <nav id="menu" class="nav-main" role="navigation">
                    <ul class="nav nav-main">
                        <li class="nav-active">
                            <a href="{{ route('dashboard') }}">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('transacoes.index') }}">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                <span>Transações</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('relatorios.index') }}">
                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                <span>Relatórios</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contatos.index') }}">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                <span>Contatos</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contas.index') }}">
                                <i class="fa fa-list" aria-hidden="true"></i>
                                <span>Contas bancárias</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tipos-contas.index') }}">
                                <i class="fa fa-list" aria-hidden="true"></i>
                                <span>Tipos de contas</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('subcategorias.index') }}">
                                <i class="fa fa-list" aria-hidden="true"></i>
                                <span>Categorias</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('empresas.index') }}">
                                <i class="fa fa-list" aria-hidden="true"></i>
                                <span>Empresas</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('usuarios.index', ['empresa_id' => $empresa_id]) }}">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                                <span>Permissões</span>
                            </a>
                        </li>
                        <li class="nav-parent">
                            <a>
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                <span>Cadastros</span>
                            </a>

                            <ul class="nav nav-children">
                                <li>
                                    <a href="{{ route('cidades.index') }}">
                                        <span>Cidades</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </aside>
</div>
