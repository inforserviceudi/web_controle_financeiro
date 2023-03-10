@php
    $header_empresas = \App\Models\Empresa::where("empresa_selecionada", "N")->get();
    $emp_principal = \App\Models\Empresa::where("empresa_selecionada", "S")->first();
@endphp
<header class="header no-print">
    <div class="logo-container">
        <a href="{{ route('dashboard') }}" class="logo">
            <img src="{{ asset('images/logo.png') }}" height="35" alt="Logo Inforservice" />
        </a>
        <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <!-- start: search & user box -->
    <div class="header-right">
        <span class="separator"></span>

        <div class="userbox menu-empresas">
            <a href="javascript:void();" data-toggle="dropdown">
                <div class="profile-info">
                    <figure class="profile-picture">
                        @if ( !empty($emp_principal->ds_logomarca) )
                        <img src="{{ asset('images/empresas/'.$emp_principal->ds_logomarca) }}" alt="{{ $emp_principal->nm_empresa }}" data-lock-picture="{{ asset('images/!logged-user.jpg') }} " />
                        @else
                        <img src="{{ asset('images/perfil-sem-foto.jpg') }}" alt="{{ $emp_principal->nm_empresa }}" data-lock-picture="{{ asset('images/!logged-user.jpg') }} " />
                        @endif
                    </figure>
                    <div class="profile-info" data-lock-name="{{ $emp_principal->nm_empresa }}" data-lock-email="{{ Auth::user()->email }}">
                        <span class="name">{{ $emp_principal->nm_empresa }}</span>
                    </div>
                </div>

                <i class="fa custom-caret"></i>
            </a>

            <div class="dropdown-menu">
                @foreach ($header_empresas as $emp_sel)
                <form action="{{ route('empresas.sistema.geral') }}" method="post">@csrf
                    <input type="hidden" name="empresa_id" value="{{ $emp_sel->id }}">
                    <button type="submit" class="empresa-link border-bottom btn-block">
                        <div class="empresa-info">
                            <p>{{ $emp_sel->nm_empresa }}</p>
                            <p>Saldo R$ <strong>{{ number_format($emp_sel->vr_saldo_inicial, 2, ',', '.') }}</strong> </p>
                        </div>
                        <div class="empresa-img">
                            @if ( !empty($emp_sel->ds_logomarca) )
                            <img src="{{ asset('images/empresas/'.$emp_sel->ds_logomarca) }}" alt="{{ $emp_sel->nm_empresa }}" data-lock-picture="{{ asset('images/!logged-user.jpg') }} " />
                            @else
                            <img src="{{ asset('images/perfil-sem-foto.jpg') }}" alt="{{ $emp_sel->nm_empresa }}" data-lock-picture="{{ asset('images/!logged-user.jpg') }} " />
                            @endif
                        </div>
                    </button>
                </form>
                @endforeach

                <a href="{{ route('empresas.index') }}" class="empresa-link" tabindex="-1" style="font-weight: bold; padding-bottom: 10px;">
                    <span class="text-center">Gerenciar empresas</span>
                </a>
            </div>
        </div>

        <span class="separator"></span>

        <ul class="notifications">
            <li>
                {{-- <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                    <i class="fa fa-search"></i>
                </a>
                <div class="dropdown-menu notification-menu large">
                    <div class="notification-title">
                        Buscar movimenta????es
                    </div>

                    <div class="content">
                        <div class="row form-group">
                            <div class="col-md-12">
                                <form action="" method="get">@csrf
                                    <div class="input-group mb-md">
                                        <input type="search" name="buscar_movimentacoes" id="buscar_movimentacoes" class="form-control" placeholder="O que voc?? procura?">
                                        <span class="input-group-btn">
                                            <button type="search" class="btn btn-default">
                                                <i class="fa fa-search fa-fw"></i>
                                            </button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p>Voc?? pode utilizar este campo para pesquisar movimenta????es por:</p>
                                <p class="text-bold">Nome ou peda??o do nome, Valor, Categoria, Recebido de / pago a</p>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <a href="#" type="button" class="notification-icon" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-search"></i>
                </a>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Buscar movimenta????es</h5>
                            </div>
                            <div class="modal-body">
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <form action="{{ route('pesquisa.movimentacaoes') }}" method="get">
                                            <div class="input-group mb-md">
                                                <input type="search" name="q" id="buscar_movimentacoes" class="form-control" placeholder="O que voc?? procura?">
                                                <span class="input-group-btn">
                                                    <button type="search" class="btn btn-default">
                                                        <i class="fa fa-search fa-fw"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Voc?? pode utilizar este campo para pesquisar movimenta????es por:</p>
                                        <p class="text-bold">Nome ou peda??o do nome, Valor, Categoria, Recebido de / pago a</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <span class="separator"></span>

        <div id="userbox" class="userbox">
            <a href="#" data-toggle="dropdown">
                <figure class="profile-picture">
                    <img src="{{ asset('images/perfil-sem-foto.jpg') }} " alt="{{ Auth::user()->name }}" data-lock-picture="{{ asset('images/!logged-user.jpg') }} " />
                </figure>
                <div class="profile-info" data-lock-name="{{ Auth::user()->name }}" data-lock-email="{{ Auth::user()->email }}">
                    <span class="name">{{ Auth::user()->name }}</span>
                </div>

                <i class="fa custom-caret"></i>
            </a>

            <div class="dropdown-menu">
                <ul class="list-unstyled">
                    <li>
                        <a role="menuitem" tabindex="-1" href="pages-user-profile.html">
                            <i class="fa fa-user fa-fw"></i>
                            Dados pessoais
                        </a>
                    </li>
                    <li>
                        <a role="menuitem" tabindex="-1" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-power-off fa-fw"></i>
                            Sair do sistema
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- end: search & user box -->
</header>
