@extends('layouts.app')

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Dashboard</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                {{-- <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li> --}}
                <li><span>Dashboard</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <div class="row">
        <div class="col-md-12">
            <h3>
                @if ( \Carbon\Carbon::now()->format('H') >= '5' && \Carbon\Carbon::now()->format('H') < '12' )
                    Bom dia
                @elseif ( \Carbon\Carbon::now()->format('H') >= '12' && \Carbon\Carbon::now()->format('H') < '18' )
                    Boa tarde
                @elseif ( \Carbon\Carbon::now()->format('H') >= '18' && \Carbon\Carbon::now()->format('H') < '5' )
                    Boa noite
                @endif
                <strong>{{ Auth::user()->name }}</strong>
            </h3>
        </div>
    </div>

    <hr class="divider">

    <div class="row">
        <div class="col-md-9">

            <div class="row">
                <div class="col-md-3">
                    <section class="panel panel-step">
                        <div class="panel-body">
                            <fa class="fa fa-file-text-o"></fa>
                            <p>Informe suas <strong>despesas</strong> para melhorar o controle do seu fluxo de caixa</p>
                            <a href="" class="btn-panel-step">Adicionar despesas</a>
                        </div>
                    </section>
                </div>
                <div class="col-md-3">
                    <section class="panel panel-step">
                        <div class="panel-body">
                            <fa class="fa fa-money"></fa>
                            <p>Informe suas <strong>receitas</strong> para melhorar o controle do seu fluxo de caixa</p>
                            <a href="" class="btn-panel-step">Adicionar receitas</a>
                        </div>
                    </section>
                </div>
            </div>

        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <h4>Contas</h4>
                    <hr class="divider">
                </div>
                <div class="col-md-12">
                    <div id="contasbox" class="userbox contasbox-header">
                        <div class="row">
                            <div class="col-md-12">
                                <select id="conta_id" class="form-control js-single">
                                    @foreach ( $contas as $conta )
                                    <option value="{{ $conta->id }}">
                                        <span>{{ $conta->ds_conta }}</span> <br>
                                        <span>R$ {{ number_format($conta->vr_saldo_inicial, 2, ',', '.') }}</span>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 text-center">
                                <a role="menuitem" tabindex="-1" href="{{ route('contas.index') }}">
                                    Gerenciar contas bancárias
                                </a>
                                <hr class="divider">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="mt-xl">Agenda do dia</h4>
                    <hr class="divider">
                </div>
                <div class="col-md-12">
                    <div class="calendario-dashboard">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="calendar"></div>
                            </div>
                            <div class="col-md-12" style="display: flex; justify-content: space-around">
                                <p>
                                    <span class="fa fa-square text-success"></span>
                                    entrada
                                </p>
                                <p>
                                    <span class="fa fa-square text-danger"></span>
                                    saída
                                </p>
                            </div>
                        </div>
                    </div>
                    <hr class="divider">
                </div>
                <div class="col-md-12">
                    <!-- start: page -->
					<div class="timeline">
						<ul>
                            <li class="timeline-title">Vencimentos do dia <span class="timeline-date">23</span></li>
                            <li>Você não tem lançamentos para este dia.</li>
                        </ul>
					</div>
					<!-- end: page -->
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function(){
        var initCalendar = function() {
            var $calendar = $('#calendar');
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            $calendar.fullCalendar({
                locale: 'pt-br',
                header: {
                    left: 'title',
                    right: ''
                },
                timeFormat: 'h:mm',
                titleFormat: {
                    month: 'MMMM YYYY',      // September 2009
                    week: "MMM d YYYY",      // Sep 13 2009
                    day: 'dddd, MMM d, YYYY' // Tuesday, Sep 8, 2009
                },
                themeButtonIcons: {
                    prev: 'fa fa-caret-left',
                    next: 'fa fa-caret-right',
                },
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                draggable: false,
                events: [
                    {
                        start: '2023-01-23',
                        end: '2023-01-23',
                    },
                    {
                        start: '2023-01-27',
                        end: '2023-01-27',
                    }
                ],
                eventClick: function(info) {
                    alert(info.start._i);
                }
            });

            $calendar.find('.fc-event')
                .css("background-color", "green")
                .css("border-color", "green");
        };

        initCalendar();
    });
</script>
@endsection
