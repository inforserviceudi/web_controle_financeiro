<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="fixed sidebar-left-collapsed">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="keywords" content="sistema controle financeiro inforservice" />
        <meta name="description" content="Sistema de controle financeiro da Inforservice Sistemas Ltda">
        <meta name="author" content="inforservice.com.br">

        <title>{{ config('app.name', 'Controle Financeiro') }}</title>

        <!-- Fonts -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

        <!-- CSS -->
        <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.css') }}"/>
        {{-- <link rel="stylesheet" href="https://cdn.datatables.net/v/bs-3.3.7/dt-1.10.22/datatables.min.css" type="text/css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css" type="text/css"> --}}
        <link rel="stylesheet" href="{{ asset('vendor/jquery-datatables-bs3/assets/css/datatables.css') }}"/>
        <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.css') }}"/>
        <link rel="stylesheet" href="{{ asset('vendor/magnific-popup/magnific-popup.css') }}"/>
        <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker/css/datepicker3.css') }}"/>
        <link rel="stylesheet" href="{{ asset('vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css') }}"/>
        <link rel="stylesheet" href="{{ asset('vendor/bootstrap-multiselect/bootstrap-multiselect.css') }}"/>
        <link rel="stylesheet" href="{{ asset('vendor/bootstrap-fileupload/bootstrap-fileupload.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('vendor/select2/select2-bootstrap.css') }}"/>
        <link rel="stylesheet" href="{{ asset('vendor/toast/demos/css/jquery.toast.css', Request::secure()) }}">
        <link rel="stylesheet" href="{{ asset('vendor/fullcalendar/fullcalendar.css') }}" />
		<link rel="stylesheet" href="{{ asset('vendor/fullcalendar/fullcalendar.print.css') }}" media="print" />
        <link rel="stylesheet" href="{{ asset('vendor/morris/morris.css') }}"/>
        <link rel="stylesheet" href="{{ asset('stylesheets/theme.css') }}"/>
        <link rel="stylesheet" href="{{ asset('stylesheets/skins/default.css') }}"/>
        <link rel="stylesheet" href="{{ asset('stylesheets/theme-custom.css') }}">

        {{-- SCRIPTS --}}
        <script src="{{ asset('vendor/modernizr/modernizr.js') }}"></script>
        <script src="{{ asset('vendor/jquery/jquery.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.js') }}"></script>
        <script src="{{ asset('vendor/jquery-mask/dist/jquery.mask.min.js', Request::secure()) }}"></script>
        <script src="{{ asset('vendor/jquery-datatables/media/js/jquery.dataTables.js', Request::secure()) }}"></script>
        <script src="{{ asset('vendor/jquery-datatables-bs3/assets/js/datatables.js', Request::secure()) }}"></script>
        <script src="{{ asset('vendor/toast/dist/jquery.toast.min.js', Request::secure()) }}"></script>
        <script src="{{ asset('javascripts/mootools-core-1.4.5.js', Request::secure()) }}"></script>
        <script src="{{ asset('javascripts/default.js', Request::secure()) }}"></script>
        <script src="{{ asset('javascripts/loader.js', Request::secure()) }}"></script>
        <script src="{{ asset('javascripts/ajax.js', Request::secure()) }}"></script>
        <script src="{{ asset('javascripts/modal.js', Request::secure()) }}"></script>

        <script>
            function getMessage(tipo, titulo, msg){
                $.toast({
                    icon: tipo,
                    heading: titulo,
                    text: msg,
                    showHideTransition: 'fade',
                    position: 'top-right',
                    stack: true,
                    hideAfter: 2000
                });
            }
        </script>
    </head>
    <body>
        @auth
            @include('layouts.header')
            @include('layouts.side-menu')
        @endauth

        @yield('content')

        @include('layouts.scripts')
    </body>
</html>
