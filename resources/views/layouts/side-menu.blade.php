<div class="inner-wrapper">
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
                            <a href="mailbox-folder.html">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                <span>Transações</span>
                            </a>
                        </li>
                        <li>
                            <a href="mailbox-folder.html">
                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                <span>Relatórios</span>
                            </a>
                        </li>
                        <li>
                            <a href="mailbox-folder.html">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                <span>Contatos</span>
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
