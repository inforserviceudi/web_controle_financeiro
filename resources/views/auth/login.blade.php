@extends('layouts.app')

@section('content')
<section class="body-sign">
    <div class="center-sign">
        <a href="{{ route('login') }}" class="logo pull-left">
            <img src="{{ asset('images/logo.png') }}" height="54" alt="Logo Inforservice" />
        </a>

        <div class="panel panel-sign">
            <div class="panel-title-sign mt-xl text-right">
                <h2 class="title text-uppercase text-bold m-none">
                    <i class="fa fa-sign-in mr-xs"></i>
                    Login
                </h2>
            </div>
            <div class="panel-body">
                <form action="{{ route('login') }}" method="post">@csrf
                    <div class="form-group mb-lg">
                        <label for="email">Email</label>
                        <div class="input-group input-group-icon">
                            <input name="email" type="email" class="form-control input-lg" />
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-envelope"></i>
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group mb-lg">
                        <div class="clearfix">
                            <label for="password" class="pull-left">Senha</label>
                            <a href="pages-recover-password.html" class="pull-right">Esqueceu a senha?</a>
                        </div>
                        <div class="input-group input-group-icon">
                            <input name="password" type="password" class="form-control input-lg" />
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-4 text-right">
                            <button type="submit" class="btn btn-primary hidden-xs title text-uppercase text-bold">
                                <i class="fa fa-sign-in mr-xs"></i>
                                Entrar
                            </button>
                            <button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg title text-uppercase text-bold">
                                <i class="fa fa-sign-in mr-xs"></i>
                                Entrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
