@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Empresas</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Empresas</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('empresas.criar.registro') }}" type="button" class="btn btn-default btn-sm mt-xl">
                <i class="fa fa-plus fa-fw"></i>
                Cadastrar nova empresa
            </a>
        </div>
        <div class="col-md-8">
    </div>
    </div>

</section>
@endsection
