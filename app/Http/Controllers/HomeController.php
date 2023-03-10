<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Transacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $id_empresa = getIdEmpresa();
        $contas = Conta::where("empresa_id", $id_empresa)->get();
        $dia_atual = Carbon::today()->format('d');
        $transacoes = Transacao::select('parcelas_transacoes.dt_vencimento')
        ->leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $id_empresa)
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->groupBy('parcelas_transacoes.dt_vencimento')
        ->get();

        $vencimentos = Transacao::select('transacoes.id', 'transacoes.descricao', 'parcelas_transacoes.vr_parcela',
            'transacoes.recebido_de', 'transacoes.pago_a', 'parcelas_transacoes.ds_pago', 'parcelas_transacoes.id as parc_transacao_id')
        ->leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $id_empresa)
        ->where('parcelas_transacoes.dt_vencimento', Carbon::today()->format('Y-m-d'))
        ->get();

        return view('layouts.dashboard',
            compact('contas', 'dia_atual', 'transacoes', 'vencimentos')
        );
    }

    public function filtraAgendaMes(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $id_empresa = getIdEmpresa();
            $data_selecionada = $request['data_selecionada'];
            $dia_atual = Carbon::parse($data_selecionada)->format('d');
            $timeline = "";

            $vencimentos = Transacao::select('transacoes.id', 'transacoes.descricao', 'parcelas_transacoes.vr_parcela',
                'transacoes.recebido_de', 'transacoes.pago_a', 'parcelas_transacoes.ds_pago', 'parcelas_transacoes.id as parc_transacao_id')
            ->leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $id_empresa)
            ->where('parcelas_transacoes.dt_vencimento', $data_selecionada)
            ->get();

            $timeline .= '<ul class="timeline-ul">';
            $timeline .= '  <li class="timeline-title">Vencimentos do dia <span class="timeline-date">'. $dia_atual .'</span></li>';
            foreach($vencimentos as $venc){
            $vr_parcela = number_format($venc->vr_parcela, 2, ',', '.');
            $checked = ($venc->ds_pago === 'S') ? 'checked' : '';
            $route = "'".route('transacoes.informar.pagamento')."'";

            $timeline .= '  <li class="timeline-li">';
            $timeline .= '      <p class="text-bold text-uppercase">R$ '. $vr_parcela .'</p>';
            $timeline .= '      <p>'. $venc->descricao .'</p>';
            $timeline .= '      <p>';
            if ( !empty($venc->recebido_de) && empty($venc->pago_a) ){
                $timeline .= '      <span class="text-bold">Recebido de:</span> '.$venc->recebido->rz_social;
            }elseif ( empty($venc->recebido_de) && !empty($venc->pago_a) ){
                $timeline .= '      <span class="text-bold">Pago a:</span> '.$venc->pago->rz_social;
            }
            $timeline .= '      </p>';
            $timeline .= '      <p>';
            $timeline .= '          <label class="switch">';
            $timeline .= '              <input type="checkbox" name="ds_pago" data-plugin-ios-switch '.$checked;
            $timeline .= '                  onclick="informarPagamento('.$route.', null, '.$venc->parc_transacao_id.', false)"/>';
            $timeline .= '              <span class="slider round"></span>';
            $timeline .= '          </label>';
            $timeline .= '          <span class="text-bold" style="position: relative; top: 6px; left: 5px;">Marcar como pago</span>';
            $timeline .= '      </p>';
            $timeline .= '  </li>';
            }
            $timeline .= '</ul>';

            DB::commit();

            return Response::json([
                'dia_atual' => $dia_atual,
                'timeline'  => $timeline,
            ]);
        } catch (QueryException $e) {
            DB::rollback();

            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => $e->getMessage()
            ]);
        }
    }
}
