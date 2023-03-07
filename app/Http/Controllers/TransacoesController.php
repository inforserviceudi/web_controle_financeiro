<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Conta;
use App\Models\Contato;
use App\Models\Log;
use App\Models\Parametro;
use App\Models\ParcelaTransacao;
use App\Models\SubCategoria;
use App\Models\Transacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class TransacoesController extends Controller
{
    public function index()
    {
        $empresa_id = getIdEmpresa();
        $contas = Conta::select('id', 'ds_conta', 'ds_conta_principal', 'vr_saldo_inicial')
        ->where('empresa_id', $empresa_id)
        ->where('ds_conta_principal', 'N')
        ->orderBy('id', "ASC")
        ->get();
        $contaP = Conta::select('id', 'ds_conta', 'ds_conta_principal', 'vr_saldo_inicial')
        ->where('empresa_id', $empresa_id)
        ->where('ds_conta_principal', 'S')
        ->first();
        $categorias = Categoria::select('id', 'nome')->get();
        $meses = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];
        $ano_atual = Carbon::today()->format('Y');
        $mes_atual = Carbon::today()->format('m');
        $transacoes = Transacao::select('transacoes.id', 'transacoes.descricao', 'transacoes.recebido_de', 'transacoes.pago_a',
            'transacoes.tipo_pagamento', 'transacoes.categoria_id', 'transacoes.subcategoria_id', 'transacoes.tipo_pagamento',
            'parcelas_transacoes.vr_parcela', 'parcelas_transacoes.ds_pago', 'parcelas_transacoes.dt_vencimento',
            'parcelas_transacoes.nr_parcela')
        ->leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->get();
        $recebimentos = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->where('parcelas_transacoes.tipo_transacao', 'R')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->sum('parcelas_transacoes.vr_parcela');
        $despesas = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->where('parcelas_transacoes.tipo_transacao', 'D')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->sum('parcelas_transacoes.vr_parcela');

        $previsto_mes = ( $recebimentos - $despesas );

        $recebimento_pago = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->where('parcelas_transacoes.tipo_transacao', 'R')
        ->where('parcelas_transacoes.ds_pago', 'S')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->sum('parcelas_transacoes.vr_parcela');
        $despesa_pago = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->where('parcelas_transacoes.tipo_transacao', 'D')
        ->where('parcelas_transacoes.ds_pago', 'S')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->sum('parcelas_transacoes.vr_parcela');

        $desp_fixo = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->where('transacoes.categoria_id', 2)
        ->where('parcelas_transacoes.tipo_transacao', 'D')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->sum('parcelas_transacoes.vr_parcela');
        $desp_fixo_pago = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->where('transacoes.categoria_id', 2)
        ->where('parcelas_transacoes.tipo_transacao', 'D')
        ->where('parcelas_transacoes.ds_pago', 'S')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->sum('parcelas_transacoes.vr_parcela');

        $desp_variavel = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->where('transacoes.categoria_id', 3)
        ->where('parcelas_transacoes.tipo_transacao', 'D')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->sum('parcelas_transacoes.vr_parcela');
        $desp_variavel_pago = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->where('transacoes.categoria_id', 3)
        ->where('parcelas_transacoes.tipo_transacao', 'D')
        ->where('parcelas_transacoes.ds_pago', 'S')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->sum('parcelas_transacoes.vr_parcela');

        $desp_pessoas = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->where('transacoes.categoria_id', 4)
        ->where('parcelas_transacoes.tipo_transacao', 'D')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->sum('parcelas_transacoes.vr_parcela');
        $desp_pessoas_pago = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->where('transacoes.categoria_id', 4)
        ->where('parcelas_transacoes.tipo_transacao', 'D')
        ->where('parcelas_transacoes.ds_pago', 'S')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->sum('parcelas_transacoes.vr_parcela');

        $desp_impostos = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->where('transacoes.categoria_id', 5)
        ->where('parcelas_transacoes.tipo_transacao', 'D')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->sum('parcelas_transacoes.vr_parcela');
        $desp_impostos_pago = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.conta_id', $contaP->id)
        ->where('transacoes.categoria_id', 5)
        ->where('parcelas_transacoes.tipo_transacao', 'D')
        ->where('parcelas_transacoes.ds_pago', 'S')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->sum('parcelas_transacoes.vr_parcela');

        $transferencias = Transacao::select('transacoes.id', 'transacoes.descricao','parcelas_transacoes.vr_parcela',
            'parcelas_transacoes.ds_pago', 'transacoes.dt_transacao', 'transacoes.conta_origem_id', 'transacoes.conta_destino_id')
        ->leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('parcelas_transacoes.tipo_transacao', 'T')
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->get();

        $param = Parametro::where('usuario_id', Auth::user()->id)->first();

        return view('transacoes.index',
            compact('empresa_id', 'contas', 'contaP', 'meses', 'ano_atual', 'mes_atual', 'categorias', 'transacoes', 'recebimentos',
                'despesas', 'previsto_mes', 'recebimento_pago', 'despesa_pago', 'desp_fixo', 'desp_fixo_pago', 'desp_variavel',
                'desp_variavel_pago', 'desp_pessoas', 'desp_pessoas_pago', 'desp_impostos', 'desp_impostos_pago', 'transferencias',
                'param')
        );
    }

    public function selecionaConta(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $empresa_id = $request['empresa_id'];
            $conta_id = $request['conta_id'];
            $mes_transacao = $request['mes_transacao'];
            $categorias = Categoria::select('id', 'nome')->get();
            $meses = [
                'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ];
            $ano_atual = Carbon::today()->format('Y');
            $mes_atual = ( $mes_transacao < 10 ) ? '0'.$mes_transacao : $mes_transacao;
            $recebimentos = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('transacoes.conta_id', $conta_id)
            ->where('parcelas_transacoes.tipo_transacao', 'R')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->sum('parcelas_transacoes.vr_parcela');
            $despesas = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('transacoes.conta_id', $conta_id)
            ->where('parcelas_transacoes.tipo_transacao', 'D')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->sum('parcelas_transacoes.vr_parcela');

            $previsto_mes = ( $recebimentos - $despesas );

            $recebimento_pago = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('transacoes.conta_id', $conta_id)
            ->where('parcelas_transacoes.tipo_transacao', 'R')
            ->where('parcelas_transacoes.ds_pago', 'S')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->sum('parcelas_transacoes.vr_parcela');
            $despesa_pago = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('transacoes.conta_id', $conta_id)
            ->where('parcelas_transacoes.tipo_transacao', 'D')
            ->where('parcelas_transacoes.ds_pago', 'S')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->sum('parcelas_transacoes.vr_parcela');

            $desp_fixo = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('transacoes.conta_id', $conta_id)
            ->where('transacoes.categoria_id', 2)
            ->where('parcelas_transacoes.tipo_transacao', 'D')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->sum('parcelas_transacoes.vr_parcela');
            $desp_fixo_pago = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('transacoes.conta_id', $conta_id)
            ->where('transacoes.categoria_id', 2)
            ->where('parcelas_transacoes.tipo_transacao', 'D')
            ->where('parcelas_transacoes.ds_pago', 'S')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->sum('parcelas_transacoes.vr_parcela');

            $desp_variavel = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('transacoes.conta_id', $conta_id)
            ->where('transacoes.categoria_id', 3)
            ->where('parcelas_transacoes.tipo_transacao', 'D')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->sum('parcelas_transacoes.vr_parcela');
            $desp_variavel_pago = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('transacoes.conta_id', $conta_id)
            ->where('transacoes.categoria_id', 3)
            ->where('parcelas_transacoes.tipo_transacao', 'D')
            ->where('parcelas_transacoes.ds_pago', 'S')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->sum('parcelas_transacoes.vr_parcela');

            $desp_pessoas = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('transacoes.conta_id', $conta_id)
            ->where('transacoes.categoria_id', 4)
            ->where('parcelas_transacoes.tipo_transacao', 'D')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->sum('parcelas_transacoes.vr_parcela');
            $desp_pessoas_pago = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('transacoes.conta_id', $conta_id)
            ->where('transacoes.categoria_id', 4)
            ->where('parcelas_transacoes.tipo_transacao', 'D')
            ->where('parcelas_transacoes.ds_pago', 'S')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->sum('parcelas_transacoes.vr_parcela');

            $desp_impostos = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('transacoes.conta_id', $conta_id)
            ->where('transacoes.categoria_id', 5)
            ->where('parcelas_transacoes.tipo_transacao', 'D')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->sum('parcelas_transacoes.vr_parcela');
            $desp_impostos_pago = Transacao::leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('transacoes.conta_id', $conta_id)
            ->where('transacoes.categoria_id', 5)
            ->where('parcelas_transacoes.tipo_transacao', 'D')
            ->where('parcelas_transacoes.ds_pago', 'S')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->sum('parcelas_transacoes.vr_parcela');

            if( $conta_id > 0 ){
                $contas = Conta::select('id', 'ds_conta', 'ds_conta_principal', 'vr_saldo_inicial')
                ->where('empresa_id', $empresa_id)
                ->where('id', '<>', $conta_id)
                ->orderBy('id', "ASC")
                ->get();
                $contaP = Conta::find($conta_id);
                $saldo_total_conta = 0;

                $transacoes = Transacao::select('transacoes.id', 'transacoes.descricao', 'transacoes.recebido_de', 'transacoes.pago_a',
                    'transacoes.tipo_pagamento', 'transacoes.categoria_id', 'transacoes.subcategoria_id', 'transacoes.tipo_pagamento',
                    'parcelas_transacoes.vr_parcela', 'parcelas_transacoes.ds_pago', 'parcelas_transacoes.dt_vencimento',
                    'parcelas_transacoes.nr_parcela')
                ->leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
                ->where('transacoes.empresa_id', $empresa_id)
                ->where('transacoes.conta_id', $conta_id)
                ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
                ->get();
            }else{
                $contas = Conta::select('id', 'ds_conta', 'ds_conta_principal', 'vr_saldo_inicial')
                ->where('empresa_id', $empresa_id)
                ->orderBy('id', "ASC")
                ->get();
                $contaP = false;
                $saldo_total_conta = Conta::where('empresa_id', $empresa_id)
                ->sum('vr_saldo_inicial');

                $transacoes = Transacao::select('transacoes.id', 'transacoes.descricao', 'transacoes.recebido_de', 'transacoes.pago_a',
                    'transacoes.tipo_pagamento', 'transacoes.categoria_id', 'transacoes.subcategoria_id', 'transacoes.tipo_pagamento',
                    'parcelas_transacoes.vr_parcela', 'parcelas_transacoes.ds_pago', 'parcelas_transacoes.dt_vencimento',
                    'parcelas_transacoes.nr_parcela', 'transacoes.conta_origem_id', 'transacoes.conta_destino_id')
                ->leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
                ->where('transacoes.empresa_id', $empresa_id)
                ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
                ->get();
            }

            $transferencias = Transacao::select('transacoes.id', 'transacoes.descricao','parcelas_transacoes.vr_parcela',
                'parcelas_transacoes.ds_pago', 'transacoes.dt_transacao', 'transacoes.conta_origem_id', 'transacoes.conta_destino_id')
            ->leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
            ->where('transacoes.empresa_id', $empresa_id)
            ->where('parcelas_transacoes.tipo_transacao', 'T')
            ->whereBetween('parcelas_transacoes.dt_vencimento', [ $ano_atual.$mes_atual.'01', $ano_atual.$mes_atual.'31' ])
            ->get();
            // dd($transferencias);
            $param = Parametro::where('usuario_id', Auth::user()->id)->first();

            // registra a ação do usuário na tabela de logs
            /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
            Log::create([
                'empresa_id' => $empresa_id,
                'usuario_id'    => Auth::user()->id,
                'ds_acao'   => "A",
                'ds_mensagem' => "Conta: ". ucwords(tirarAcentos($request['nm_cidade']))
            ]);

            DB::commit();

            return view('transacoes.index',
                compact('empresa_id', 'contas', 'contaP', 'saldo_total_conta', 'meses', 'ano_atual', 'mes_atual', 'categorias',
                    'transacoes', 'previsto_mes', 'recebimentos', 'despesas', 'recebimento_pago', 'despesa_pago', 'desp_fixo',
                    'desp_fixo_pago', 'desp_variavel', 'desp_variavel_pago', 'desp_pessoas', 'desp_pessoas_pago', 'desp_impostos',
                    'desp_impostos_pago', 'transferencias', 'param')
            );
        } catch (QueryException $e) {
            DB::rollback();

            return redirect()->back()->with([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function modalCreateEdit(Request $request)
    {
        // dd($request->all());
        /// PARAMETROS DA REQUEST 1 - EMPRESA_ID / 2 - CATEGORIA_ID / 3 - CONTA_ID / 4 - TRANSACAO_ID
        $explode = explode("#", $request['id']);
        $empresa_id = $explode[0];
        $categoria_id = $explode[1];
        $conta_id = $explode[2];
        $transacao_id = $explode[3];
        // $transacao = Transacao::find($transacao_id);
        $transacao = Transacao::select('transacoes.id', 'transacoes.descricao', 'transacoes.recebido_de', 'transacoes.pago_a',
            'transacoes.tipo_pagamento', 'transacoes.categoria_id', 'transacoes.subcategoria_id', 'transacoes.forma_pagamento',
            'parcelas_transacoes.vr_parcela', 'parcelas_transacoes.ds_pago', 'parcelas_transacoes.dt_vencimento',
            'parcelas_transacoes.nr_parcela')
        ->leftJoin('parcelas_transacoes', 'parcelas_transacoes.transacao_id', '=', 'transacoes.id')
        ->where('transacoes.empresa_id', $empresa_id)
        ->where('transacoes.id', $transacao_id)
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->first();
        $categoria = Categoria::find($categoria_id);
        $conta = Conta::find($conta_id);
        $contatos = Contato::select('id', 'rz_social')->where('empresa_id', $empresa_id)->get();
        $subcategorias = SubCategoria::select('id', 'nome')->where('empresa_id', $empresa_id)->where('categoria_id', $categoria_id)->get();

        if( $transacao_id == 0 ){
            $modal_title = "Nova transação";
            $parcelas = [];
        }else{
            $modal_title = "Editar transação " . $transacao_id;
            $parcelas = ParcelaTransacao::where('transacao_id', $transacao->id)->orderBy('nr_parcela', 'ASC')->get();
        }

        return view('transacoes.modal-create-edit',
            compact('modal_title', 'empresa_id', 'categoria_id', 'conta_id', 'transacao', 'categoria', 'conta',
                'transacao_id', 'contatos', 'subcategorias', 'parcelas')
        );
    }

    public function modalParcelas(Request $request)
    {
        // dd($request->all());
        /// PARAMETROS DA REQUEST 1 - EMPRESA_ID / 2 - CATEGORIA_ID / 3 - CONTA_ID / 4 - TRANSACAO_ID
        $explode = explode("#", $request['id']);
        $empresa_id = $explode[0];
        $categoria_id = $explode[1];
        $conta_id = $explode[2];
        $transacao_id = $explode[3];
        $transacao = Transacao::find($transacao_id);
        $tt_parcelas = ParcelaTransacao::where('transacao_id', $transacao->id)->count('transacao_id');
        $parcelas = ParcelaTransacao::where('transacao_id', $transacao->id)->orderBy('nr_parcela', 'ASC')->get();
        $vr_total = $transacao->vr_total;

        return view('transacoes.modal-parcelas',
            compact('empresa_id', 'categoria_id', 'conta_id', 'transacao_id', 'tt_parcelas', 'parcelas', 'vr_total')
        );
    }

    public function modalTransferencias(Request $request)
    {
        // dd($request->all());
        /// PARAMETROS DA REQUEST 1 - EMPRESA_ID
        $explode = explode("#", $request['id']);
        $empresa_id = $explode[0];
        $contas = Conta::select('id', 'ds_conta')->where('empresa_id', $empresa_id)->get();

        return view('transacoes.modal-transferencias',
            compact('empresa_id', 'contas')
        );
    }

    public function ajaxParcelamento(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $transacao_id = $request['transacao_id'];
            $frequencia = $request['frequencia'];
            $nr_parcelas = $request['nr_parcelas'];
            $nm_modal = $request['nm_modal'];
            $valor_total = formatValue($request['valor']);
            $tabela = "";
            $soma_parcelas = 0;

            if( !$valor_total ){
                return Response::json([
                    'titulo'    => 'Falhou!!!',
                    'tipo'      => "error",
                    'message'   => "Informe o valor total",
                    'erro'      => 'erro'
                ]);
            }

            if( $nm_modal === 'create-edit' ){
                $vr_parcela = ($valor_total / $nr_parcelas);

                for( $i = 0; $i < $nr_parcelas; $i++ ){
                    $tabela .=   '<tr>';
                    $tabela .=   '  <td>';
                    /// calcula a data de vencimento de acordo com a frequencia selecionada
                    switch ($frequencia) {
                        case 'semana':
                            $freq = (7 * ($i));
                            $dt_vencimento = Carbon::today()->addDays($freq)->format('Y-m-d');
                            break;
                        case 'quinzena':
                            $freq = (15 * ($i));
                            $dt_vencimento = Carbon::today()->addDays($freq)->format('Y-m-d');
                            break;
                        case 'mes':
                            $freq = (30 * ($i));
                            $dt_vencimento = Carbon::today()->addDays($freq)->format('Y-m-d');
                            break;
                        case 'bimestre':
                            $freq = (2 * ($i));
                            $dt_vencimento = Carbon::today()->addMonths($freq)->format('Y-m-d');
                            break;
                        case 'trimestre':
                            $freq = (3 * ($i));
                            $dt_vencimento = Carbon::today()->addMonths($freq)->format('Y-m-d');
                            break;
                        case 'semestre':
                            $freq = (6 * ($i));
                            $dt_vencimento = Carbon::today()->addMonths($freq)->format('Y-m-d');
                            break;
                        case 'anual':
                            $freq = (1 * ($i));
                            $dt_vencimento = Carbon::today()->addYears($freq)->format('Y-m-d');
                            break;
                    }
                    $tabela .=   '      <input type="date" name="dt_vencimento[]" class="form-control" value="'. $dt_vencimento .'" required>';
                    $tabela .=   '  </td>';
                    $tabela .=   '  <td> <input type="text" id="parcela'.($i+1).'" name="vr_parcela[]" class="form-control mask-valor vr_parcela" value="'. number_format($vr_parcela, 2, ',', '.') .'" required></td>';
                    $tabela .=   '  <td>';
                    $tabela .=   '      <label class="switch">';
                    $tabela .=   '          <input type="checkbox" name="ds_pago[]" data-plugin-ios-switch/>';
                    $tabela .=   '          <span class="slider round"></span>';
                    $tabela .=   '      </label>';
                    $tabela .=   '  </td>';
                    $tabela .=   '  <td></td>';
                    $tabela .=   '</tr>';
                }
            }elseif( $nm_modal === 'parcelas' ){
                $count_parcelas = ParcelaTransacao::where('transacao_id', $transacao_id)->count('transacao_id');
                $count_ds_pago = ParcelaTransacao::where('transacao_id', $transacao_id)->where('ds_pago', 'S')->count('ds_pago');
                $parcelas_pagas = ParcelaTransacao::where('transacao_id', $transacao_id)->where('ds_pago', 'S')->sum('vr_parcela');
                $parcelamento = ParcelaTransacao::where('transacao_id', $transacao_id)->where('ds_pago', 'S')->get();
                $ultima_parcela = ParcelaTransacao::select('nr_parcela')
                ->where('transacao_id', $transacao_id)
                ->where('ds_pago', 'S')
                ->orderBy('nr_parcela', 'DESC')
                ->first();
                $ult_dtvencimento = Carbon::today()->subMonth()->format('Y-m-d');

                if( !$ultima_parcela ){
                    $ultima_parcela = ParcelaTransacao::select('nr_parcela')
                    ->where('transacao_id', $transacao_id)
                    ->orderBy('nr_parcela', 'DESC')
                    ->first();
                }

                if( $nr_parcelas > $count_ds_pago ){
                    $nr_parcelas = ($nr_parcelas - $count_ds_pago);
                }else{
                    return Response::json([
                        'titulo'    => 'Falhou!!!',
                        'tipo'      => "error",
                        'message'   => "Não é possível realizar esta ação",
                        'erro'      => 'erro'
                    ]);
                }

                foreach( $parcelamento as $parc ){
                    $dt_vencimento = Carbon::parse($parc->dt_vencimento)->format('Y-m-d');
                    $checked = ( $parc->ds_pago === 'S' ) ? 'checked' : '';
                    $disabled = ( $parc->ds_pago === 'S' ) ? 'disabled' : '';
                    $route_delete = route('transacoes.excluir.parcelas');
                    $onclick2 = "'$parc->id', '$route_delete', 'tbody_parcelamento'";

                    $tabela .=   '<tr>';
                    $tabela .=   '  <td>';
                    $tabela .=   '      <input type="date" name="dt_vencimento[]" class="form-control" value="'. $dt_vencimento .'" required '. $disabled .'>';
                    $tabela .=   '  </td>';
                    $tabela .=   '  <td> <input type="text" id="parcela'. $parc->nr_parcela .'" name="vr_parcela[]" class="form-control mask-valor vr_parcela" value="'. number_format($parc->vr_parcela, 2, ',', '.') .'" required '. $disabled .'></td>';
                    $tabela .=   '  <td>';
                    $tabela .=   '      <label class="switch">';
                    $tabela .=   '          <input type="checkbox" name="ds_pago[]" data-plugin-ios-switch '. $checked .'/>';
                    $tabela .=   '          <span class="slider round"></span>';
                    $tabela .=   '      </label>';
                    $tabela .=   '  </td>';
                    $tabela .=   '  <td>';
                    if( $parc->ds_pago === 'N' ){
                    $tabela .=   '      <button class="btn btn-link btn-sm text-default" onclick="removeParcela('. $onclick2 .')"><i class="fa fa-trash-o fa-fw"></i></button>';
                    }
                    $tabela .=   '  </td>';
                    $tabela .=   '</tr>';

                    $ult_dtvencimento = $dt_vencimento;
                }

                // ParcelaTransacao::where('transacao_id', $transacao_id)->where('ds_pago', 'N')->delete();
                $valor_a_parcelar = ($valor_total - $parcelas_pagas);
                $ult_nrparcela = ($ultima_parcela->nr_parcela + 1);
                $vr_parcela = ($valor_a_parcelar / $nr_parcelas);

                for( $i = 1; $i <= $nr_parcelas; $i++ ){
                    $tabela .=   '<tr>';
                    $tabela .=   '  <td>';
                    /// calcula a data de vencimento de acordo com a frequencia selecionada
                    switch ($frequencia) {
                        case 'semana':
                            $freq = (7 * ($i));
                            $dt_vencimento = Carbon::parse($ult_dtvencimento)->addDays($freq)->format('Y-m-d');
                            break;
                        case 'quinzena':
                            $freq = (15 * ($i));
                            $dt_vencimento = Carbon::parse($ult_dtvencimento)->addDays($freq)->format('Y-m-d');
                            break;
                        case 'mes':
                            $freq = (30 * ($i));
                            $dt_vencimento = Carbon::parse($ult_dtvencimento)->addDays($freq)->format('Y-m-d');
                            break;
                        case 'bimestre':
                            $freq = (2 * ($i));
                            $dt_vencimento = Carbon::parse($ult_dtvencimento)->addMonths($freq)->format('Y-m-d');
                            break;
                        case 'trimestre':
                            $freq = (3 * ($i));
                            $dt_vencimento = Carbon::parse($ult_dtvencimento)->addMonths($freq)->format('Y-m-d');
                            break;
                        case 'semestre':
                            $freq = (6 * ($i));
                            $dt_vencimento = Carbon::parse($ult_dtvencimento)->addMonths($freq)->format('Y-m-d');
                            break;
                        case 'anual':
                            $freq = (1 * ($i));
                            $dt_vencimento = Carbon::parse($ult_dtvencimento)->addYears($freq)->format('Y-m-d');
                            break;
                    }
                    $tabela .=   '      <input type="date" name="dt_vencimento[]" class="form-control" value="'. $dt_vencimento .'" required>';
                    $tabela .=   '  </td>';
                    $tabela .=   '  <td> <input type="text" id="parcela'. $ult_nrparcela .'" name="vr_parcela[]" class="form-control mask-valor vr_parcela" value="'. number_format($vr_parcela, 2, ',', '.') .'" required></td>';
                    $tabela .=   '  <td>';
                    // $tabela .=   '      <label class="switch">';
                    // $tabela .=   '          <input type="checkbox" name="ds_pago[]" data-plugin-ios-switch/>';
                    // $tabela .=   '          <span class="slider round"></span>';
                    // $tabela .=   '      </label>';
                    $tabela .=   '  </td>';
                    $tabela .=   '  <td>';
                    // $tabela .=   '      <button class="btn btn-link btn-sm text-default" onclick="removeParcela();"><i class="fa fa-trash-o fa-fw"></i></button>';
                    $tabela .=   '  </td>';
                    $tabela .=   '</tr>';

                    $ult_nrparcela++;
                }
            }

            DB::commit();

            return Response::json([
                'tabela'    => $tabela,
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

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            if( ($request['categoria_id'] == 1) && !isset($request['recebido_de']) ){
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => "Informe o campo 'Recebido de'",
                    'erro'      => 'erro'
                ]);
            }

            if( ($request['categoria_id'] != 1) && !isset($request['pago_a']) ){
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => "Informe o campo 'Pagar a'",
                    'erro'      => 'erro'
                ]);
            }

            if( isset($request['recebido_de']) ){
                $validator = Validator::make($request->all(), [
                    'descricao'  => 'required|string|max:150',
                    'dt_transacao'  => 'required',
                    'subcategoria_id'  => 'required',
                    'vr_total'  => 'required',
                    'recebido_de'  => 'required',
                ], [
                    'descricao.required' => "Informe a descrição da transação",
                    'dt_transacao.required' => "Informe a data da transação",
                    'subcategoria_id.required' => "Informe a categoria da transação",
                    'vr_total.required' => "Informe o valor da transação",
                    'recebido_de.required' => "Informe o contato",

                    "string"    => "A descrição deve conter letras e números",
                    "max"       => "Informe no máximo :max caracteres",
                ]);
            }elseif( isset($request['pago_a']) ){
                $validator = Validator::make($request->all(), [
                    'descricao'  => 'required|string|max:150',
                    'dt_transacao'  => 'required',
                    'subcategoria_id'  => 'required',
                    'vr_total'  => 'required',
                    'pago_a'  => 'required',
                ], [
                    'descricao.required' => "Informe a descrição da transação",
                    'dt_transacao.required' => "Informe a data da transação",
                    'subcategoria_id.required' => "Informe a categoria da transação",
                    'vr_total.required' => "Informe o valor da transação",
                    'pago_a.required' => "Informe o contato",

                    "string"    => "A descrição deve conter letras e números",
                    "max"       => "Informe no máximo :max caracteres",
                ]);
            }

            if ($validator->fails()) {
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => $validator->errors()->all(),
                    'erro'      => 'erro'
                ]);
            } else {
                $empresa_id = $request['empresa_id'];
                $categoria_id = $request['categoria_id'];
                $conta_id = $request['conta_id'];
                $vr_total = formatValue($request['vr_total']);

                $transacao = Transacao::create([
                    'empresa_id'    => $empresa_id,
                    'conta_id'  => $conta_id,
                    'categoria_id'  => $categoria_id,
                    'subcategoria_id'   => $request['subcategoria_id'],
                    'dt_transacao'  => verifyDateFormat($request['dt_transacao']),
                    'dt_competencia'    => verifyDateFormat($request['dt_competencia']),
                    'descricao' => $request['descricao'],
                    'vr_total'   => $vr_total,
                    'recebido_de'   => $request['recebido_de'],
                    'pago_a'    => $request['pago_a'],
                    'tipo_pagamento'    => $request['tipo_pagamento'],
                    'forma_pagamento'   => $request['forma_pagamento'],
                    'ds_pago'   => "N",
                    'nr_documento'  => $request['nr_documento'],
                    'comentarios'   => $request['comentarios'],
                    'repetir_transacao' => $request['repetir_transacao'],
                ]);

                if( $request['tipo_pagamento'] === "V" ){
                    ParcelaTransacao::create([
                        'transacao_id'  => $transacao->id,
                        'tipo_transacao'  => ( $categoria_id === 1 ) ? "R" : "D",
                        'nr_parcela'    => 1,
                        'vr_parcela'    => $vr_total,
                        'dt_vencimento' => verifyDateFormat($request['dt_transacao']),
                        'dt_pagamento'  => null,
                        'ds_pago'       => "N",
                    ]);
                }elseif( $request['tipo_pagamento'] === "P" ){
                    if( isset($request['vr_parcela']) ){
                        for( $i = 0; $i < count($request['vr_parcela']); $i++ ){
                            ParcelaTransacao::create([
                                'transacao_id'  => $transacao->id,
                                'tipo_transacao'  => ( $categoria_id === 1 ) ? "R" : "D",
                                'nr_parcela'    => ($i+1),
                                'vr_parcela'    => formatValue($request['vr_parcela'][$i]),
                                'dt_vencimento' => verifyDateFormat($request['dt_vencimento'][$i]),
                                'dt_pagamento'  => null,
                                'ds_pago'       => "N",
                            ]);
                        }
                    }else{
                        return Response::json([
                            'titulo'    => "Falhou!!!",
                            'tipo'      => "error",
                            'message'   => "Não tem parcela gerada para esta transação",
                        ]);
                    }
                }

                // regista a ação de login do usuário na tabela de logs
                /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
                Log::create([
                    'empresa_id' => $empresa_id,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "C",
                    'ds_mensagem' => "Transação cód: ".$transacao->id
                ]);

                DB::commit();
                $href = route('transacoes.index');

                return Response::json([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Transação criada",
                    'href'      => $href
                ]);
            }
        } catch (QueryException $e) {
            DB::rollback();

            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $transacao = Transacao::find($id);

        if( !$transacao ){
            return Response::json([
                'titulo'    => "Atenção!!!",
                'tipo'      => "error",
                'message'   => "Transação não existe no banco de dados",
                'erro'      => 'erro'
            ]);
        }

        // dd($request->all());
        DB::beginTransaction();
        try{
            if( isset($request['recebido_de']) ){
                $validator = Validator::make($request->all(), [
                    'descricao'  => 'required|string|max:150',
                    'dt_transacao'  => 'required',
                    'subcategoria_id'  => 'required',
                    // 'vr_total'  => 'required',
                    'recebido_de'  => 'required',
                ], [
                    'descricao.required' => "Informe a descrição da transação",
                    'dt_transacao.required' => "Informe a data da transação",
                    'subcategoria_id.required' => "Informe a categoria da transação",
                    // 'vr_total.required' => "Informe o valor da transação",
                    'recebido_de.required' => "Informe o contato",

                    "string"    => "A descrição deve conter letras e números",
                    "max"       => "Informe no máximo :max caracteres",
                ]);
            }elseif( isset($request['pago_a']) ){
                $validator = Validator::make($request->all(), [
                    'descricao'  => 'required|string|max:150',
                    'dt_transacao'  => 'required',
                    'subcategoria_id'  => 'required',
                    // 'vr_total'  => 'required',
                    'pago_a'  => 'required',
                ], [
                    'descricao.required' => "Informe a descrição da transação",
                    'dt_transacao.required' => "Informe a data da transação",
                    'subcategoria_id.required' => "Informe a categoria da transação",
                    // 'vr_total.required' => "Informe o valor da transação",
                    'pago_a.required' => "Informe o contato",

                    "string"    => "A descrição deve conter letras e números",
                    "max"       => "Informe no máximo :max caracteres",
                ]);
            }

            if ($validator->fails()) {
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => $validator->errors()->all(),
                    'erro'      => 'erro'
                ]);
            } else {
                $empresa_id = $request['empresa_id'];

                $transacao->update([
                    'subcategoria_id'   => $request['subcategoria_id'],
                    'dt_transacao'  => verifyDateFormat($request['dt_transacao']),
                    'dt_competencia'    => verifyDateFormat($request['dt_competencia']),
                    'descricao' => $request['descricao'],
                    'recebido_de'   => $request['recebido_de'],
                    'pago_a'    => $request['pago_a'],
                    // 'tipo_pagamento'    => $request['tipo_pagamento'],
                    'forma_pagamento'   => $request['forma_pagamento'],
                    'nr_documento'  => $request['nr_documento'],
                    'comentarios'   => $request['comentarios'],
                    'repetir_transacao' => $request['repetir_transacao'],
                ]);

                // regista a ação de login do usuário na tabela de logs
                /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
                Log::create([
                    'empresa_id' => $empresa_id,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "A",
                    'ds_mensagem' => "Transação cód: ".$transacao->id
                ]);

                DB::commit();
                $href = route('transacoes.index');

                return Response::json([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Transação atualizada",
                    'href'      => $href
                ]);
            }
        } catch (QueryException $e) {
            DB::rollback();

            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function deleteParcelas(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $parcela_id = $request['parcela_id'];
            $valor_total = formatValue($request['valor_total']);
            $parcela = ParcelaTransacao::find($parcela_id);
            $tabela = "";

            if( !$parcela ){
                return Response::json([
                    'titulo'    => 'Falhou!!!',
                    'tipo'      => "error",
                    'message'   => "Parcela não existe no banco de dados",
                    'erro'      => 'erro'
                ]);
            }

            if( $parcela->ds_pago === "N" ){
                $parcela->delete();
                $count_parcelas = ParcelaTransacao::where('transacao_id', $parcela->transacao_id)->count('transacao_id');
                $count_ds_pago = ParcelaTransacao::where('transacao_id', $parcela->transacao_id)->where('ds_pago', 'S')->count('ds_pago');
                $parcelamento = ParcelaTransacao::where('transacao_id', $parcela->transacao_id)->get();
                $nr = 1;

                foreach( $parcelamento as $p ){
                    ParcelaTransacao::find($p->id)->update([
                        'nr_parcela' => $nr
                    ]);

                    $nr++;
                }

                $parcelas_pagas = ParcelaTransacao::where('transacao_id', $parcela->transacao_id)
                    ->where('ds_pago', 'S')
                    ->sum('vr_parcela');
                $valor_a_parcelar = ($valor_total - $parcelas_pagas);

                if(( $count_parcelas - $count_ds_pago ) > 0){
                    for($i = 1; $i <= $count_parcelas; $i++){
                        $nova_parcela = ($valor_a_parcelar / ( $count_parcelas - $count_ds_pago ));

                        ParcelaTransacao::where('transacao_id', $parcela->transacao_id)->where('ds_pago', 'N')
                        ->update([
                            'vr_parcela' => $nova_parcela,
                        ]);
                    }
                }else{
                    return Response::json([
                        'titulo'    => 'Atenção!!!',
                        'tipo'      => "error",
                        'message'   => "Você não pode excluir esta parcela",
                        'erro'      => 'erro'
                    ]);
                }

                $parcelamento = ParcelaTransacao::where('transacao_id', $parcela->transacao_id)->get();

                foreach( $parcelamento as $parc ){
                    $dt_vencimento = Carbon::parse($parc->dt_vencimento)->format('Y-m-d');
                    $checked = ( $parc->ds_pago === 'S' ) ? 'checked' : '';
                    $disabled = ( $parc->ds_pago === 'S' ) ? 'disabled' : '';
                    $route1 = route('transacoes.informar.pagamento');
                    $route_delete = route('transacoes.excluir.parcelas');
                    $onclick2 = "'$parc->id', '$route_delete', 'tbody_parcelamento'";

                    $tabela .=   '<tr>';
                    $tabela .=   '  <td>';
                    $tabela .=   '      <input type="date" name="dt_vencimento[]" class="form-control" value="'. $dt_vencimento .'" required '. $disabled .'>';
                    $tabela .=   '  </td>';
                    $tabela .=   '  <td> <input type="text" id="parcela'. $parc->nr_parcela .'" name="vr_parcela[]" class="form-control mask-valor vr_parcela" value="'. number_format($parc->vr_parcela, 2, ',', '.') .'" required '. $disabled .'></td>';
                    $tabela .=   '  <td>';
                    $tabela .=   '      <label class="switch">';
                    $tabela .=   '          <input type="checkbox" name="ds_pago[]" data-plugin-ios-switch '. $checked .' onclick="informarPagamento('. $route1 .', "tbody_parcelamento", '. $parc->id .');"/>';
                    $tabela .=   '          <span class="slider round"></span>';
                    $tabela .=   '      </label>';
                    $tabela .=   '  </td>';
                    $tabela .=   '  <td>';
                    // if( $parc->ds_pago === 'N' ){
                    // $tabela .=   '      <button class="btn btn-link btn-sm text-default" onclick="removeParcela('. $onclick2 .')"><i class="fa fa-trash-o fa-fw"></i></button>';
                    // }
                    $tabela .=   '  </td>';
                    $tabela .=   '</tr>';
                }
            }else{
                return Response::json([
                    'titulo'    => 'Falhou!!!',
                    'tipo'      => "error",
                    'message'   => "Esta parcela foi paga, não é possível excluir",
                    'erro'      => 'erro'
                ]);
            }

            $empresa_id = getIdEmpresa();

            // regista a ação de login do usuário na tabela de logs
            /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
            Log::create([
                'empresa_id' => $empresa_id,
                'usuario_id'    => Auth::user()->id,
                'ds_acao'   => "E",
                'ds_mensagem' => "Removeu parcelas"
            ]);

            DB::commit();

            return Response::json([
                'tabela'    => $tabela,
            ]);
        } catch (QueryException $e) {
            DB::rollback();

            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => $e->getMessage(),
                'erro'      => 'erro'
            ]);
        }
    }

    public function informarPagamento(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $parcela_id = $request['parcela_id'];
            $parcela = ParcelaTransacao::find($parcela_id);
            $tabela = "";

            if( !$parcela ){
                return Response::json([
                    'titulo'    => 'Falhou!!!',
                    'tipo'      => "error",
                    'message'   => "Parcela não existe no banco de dados",
                    'erro'      => 'erro'
                ]);
            }

            if( $parcela->ds_pago === "S" ){
                $parcela->update([
                    'dt_pagamento'  => null,
                    'ds_pago'   => "N",
                ]);
            }else{
                $parcela->update([
                    'dt_pagamento'  => Carbon::today()->format('Y-m-d'),
                    'ds_pago'   => "S",
                ]);
            }

            $parcelamento = ParcelaTransacao::where('transacao_id', $parcela->transacao_id)->get();
            $count_parcelas = ParcelaTransacao::where('transacao_id', $parcela->transacao_id)->count('transacao_id');

            foreach( $parcelamento as $parc ){
                $dt_vencimento = Carbon::parse($parc->dt_vencimento)->format('Y-m-d');
                $checked = ( $parc->ds_pago === 'S' ) ? 'checked' : '';
                $disabled = ( $parc->ds_pago === 'S' ) ? 'disabled' : '';
                $route1 = route('transacoes.informar.pagamento');
                $onclick =  "'$route1', 'tbody_parcelamento', '$parc->id'";
                $route_delete = route('transacoes.excluir.parcelas');
                $onclick2 = "'$parc->id', '$route_delete', 'tbody_parcelamento'";

                $tabela .=   '<tr>';
                $tabela .=   '  <td>';
                $tabela .=   '      <input type="date" name="dt_vencimento[]" class="form-control" value="'. $dt_vencimento .'" required '. $disabled .'>';
                $tabela .=   '  </td>';
                $tabela .=   '  <td> <input type="text" id="parcela'. $parc->nr_parcela .'" name="vr_parcela[]" class="form-control mask-valor vr_parcela" value="'. number_format($parc->vr_parcela, 2, ',', '.') .'" required '. $disabled .'></td>';
                $tabela .=   '  <td>';
                $tabela .=   '      <label class="switch">';
                $tabela .=   '          <input type="checkbox" name="ds_pago[]" data-plugin-ios-switch '. $checked .' onclick="informarPagamento('. $onclick .')"/>';
                $tabela .=   '          <span class="slider round"></span>';
                $tabela .=   '      </label>';
                $tabela .=   '  </td>';
                $tabela .=   '  <td>';
                if( $parc->ds_pago === 'N' ){
                $tabela .=   '      <button class="btn btn-link btn-sm text-default" onclick="removeParcela('. $onclick2 .')"><i class="fa fa-trash-o fa-fw"></i></button>';
                }
                $tabela .=   '  </td>';
                $tabela .=   '</tr>';
            }

            DB::commit();

            return Response::json([
                'tabela'    => $tabela,
            ]);
        } catch (QueryException $e) {
            DB::rollback();

            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => $e->getMessage(),
                'erro'      => 'erro'
            ]);
        }
    }

    public function atualizaParcelas(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $empresa_id = $request['empresa_id'];
            $categoria_id = $request['categoria_id'];
            $conta_id = $request['conta_id'];
            $transacao_id = $request['transacao_id'];
            $parcelas = $request['parcelas'];
            $frequencia = $request['frequencia'];
            $arr_dt_vencimento = $request['dt_vencimento'];
            $arr_vr_parcela = $request['vr_parcela'];
            $nr_parcela = 1;

            ParcelaTransacao::where('transacao_id', $transacao_id)
            ->where('ds_pago', 'N')
            ->delete();

            $ultima_parcela = ParcelaTransacao::select('nr_parcela')
            ->where('transacao_id', $transacao_id)
            ->where('ds_pago', 'S')
            ->orderBy('nr_parcela', 'DESC')
            ->first();

            if( $ultima_parcela ){
                $nr_parcela = ( $ultima_parcela->nr_parcela + 1 );
            }

            for( $i = 0; $i < count($arr_vr_parcela); $i++ ){
                ParcelaTransacao::create([
                    'transacao_id'  => $transacao_id,
                    'tipo_transacao'  => ( $categoria_id === 1 ) ? "R" : "D",
                    'nr_parcela'    => $nr_parcela,
                    'vr_parcela'    => formatValue($arr_vr_parcela[$i]),
                    'dt_vencimento' => verifyDateFormat($arr_dt_vencimento[$i]),
                    'dt_pagamento'  => null,
                    'ds_pago'   => 'N',
                ]);

                $nr_parcela++;
            }

            // regista a ação de login do usuário na tabela de logs
            /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
            Log::create([
                'empresa_id' => $empresa_id,
                'usuario_id'    => Auth::user()->id,
                'ds_acao'   => "A",
                'ds_mensagem' => "Recalculou parcelas"
            ]);

            DB::commit();
            $href = route('transacoes.index');

            return Response::json([
                'titulo'    => 'Sucesso !!!',
                'tipo'      => "success",
                'message'   => "As parcelas foram alteradas",
                'href'      => $href
            ]);
        } catch (QueryException $e) {
            DB::rollback();

            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => $e->getMessage(),
                'erro'      => 'erro'
            ]);
        }
    }

    public function geraTransferencia(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'descricao'  => 'required|string|max:150',
                'dt_transacao'  => 'required',
                'vr_total'  => 'required',
                'conta_origem_id'  => 'required',
                'conta_destino_id'  => 'required',
            ], [
                'descricao.required' => "Informe a descrição da transferência",
                'dt_transacao.required' => "Informe a data da transferência",
                'vr_total.required' => "Informe o valor da transferência",
                'conta_origem_id.required' => "Informe a conta de origem",
                'conta_origem_id.required' => "Informe a conta de destino",

                "string"    => "A descrição deve conter letras e números",
                "max"       => "Informe no máximo :max caracteres",
            ]);

            if( $request['conta_origem_id'] === $request['conta_destino_id'] ){
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => "As contas de origem e destino devem ser diferentes",
                    'erro'      => 'erro'
                ]);
            }

            if ($validator->fails()) {
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => $validator->errors()->all(),
                    'erro'      => 'erro'
                ]);
            } else {
                $empresa_id = $request['empresa_id'];
                $vr_total = formatValue($request['vr_total']);

                $transacao = Transacao::create([
                    'empresa_id'    => $empresa_id,
                    'conta_id'  => 0,
                    'conta_origem_id'   => $request['conta_origem_id'],
                    'conta_destino_id'   => $request['conta_destino_id'],
                    'categoria_id'  => 0,
                    'subcategoria_id'   => 0,
                    'dt_transacao'  => verifyDateFormat($request['dt_transacao']),
                    'dt_competencia'    => verifyDateFormat($request['dt_transacao']),
                    'descricao' => $request['descricao'],
                    'vr_total'   => $vr_total,
                    'recebido_de'   => null,
                    'pago_a'    => null,
                    'tipo_pagamento'    => 0,
                    'forma_pagamento'   => 0,
                    'ds_pago'   => "N",
                    'nr_documento'  => 0,
                    'comentarios'   => null,
                    'repetir_transacao' => 'N',
                ]);

                ParcelaTransacao::create([
                    'transacao_id'  => $transacao->id,
                    'tipo_transacao'  => "T",
                    'nr_parcela'    => 1,
                    'vr_parcela'    => $vr_total,
                    'dt_vencimento' => verifyDateFormat($request['dt_transacao']),
                    'dt_pagamento'  => verifyDateFormat($request['dt_transacao']),
                    'ds_pago'       => "N",
                ]);

                // regista a ação de login do usuário na tabela de logs
                /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
                Log::create([
                    'empresa_id' => $empresa_id,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "C",
                    'ds_mensagem' => "Transferência cód: ".$transacao->id
                ]);

                DB::commit();
                $href = route('transacoes.index');

                return Response::json([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Transferência efetuada",
                    'href'      => $href
                ]);
            }
        } catch (QueryException $e) {
            DB::rollback();

            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function duplicaTransferencia(Request $request, $id)
    {
        // dd($request->all(), $id);
        DB::beginTransaction();
        try{
            $transferencia = Transacao::find($id);

            if( !$transferencia ){
                return Response::json([
                    'titulo'    => 'Falhou!!!',
                    'tipo'      => "error",
                    'message'   => "Transação não existe no banco de dados",
                    'erro'      => 'erro'
                ]);
            }

            $transacao = Transacao::create([
                'empresa_id'    => $transferencia->empresa_id,
                'conta_id'  => 0,
                'conta_origem_id'   => $transferencia->conta_origem_id,
                'conta_destino_id'   => $transferencia->conta_destino_id,
                'categoria_id'  => 0,
                'subcategoria_id'   => 0,
                'dt_transacao'  => $transferencia->dt_transacao,
                'dt_competencia'    => $transferencia->dt_transacao,
                'descricao' => $transferencia->descricao,
                'vr_total'   => $transferencia->vr_total,
                'recebido_de'   => null,
                'pago_a'    => null,
                'tipo_pagamento'    => 0,
                'forma_pagamento'   => 0,
                'ds_pago'   => "N",
                'nr_documento'  => 0,
                'comentarios'   => null,
                'repetir_transacao' => 'N',
            ]);

            ParcelaTransacao::create([
                'transacao_id'  => $transacao->id,
                'tipo_transacao'  => "T",
                'nr_parcela'    => 1,
                'vr_parcela'    => $transferencia->vr_total,
                'dt_vencimento' => $transferencia->dt_transacao,
                'dt_pagamento'  => $transferencia->dt_transacao,
                'ds_pago'       => "N",
            ]);

            // regista a ação de login do usuário na tabela de logs
            /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
            Log::create([
                'empresa_id' => $transferencia->empresa_id,
                'usuario_id'    => Auth::user()->id,
                'ds_acao'   => "C",
                'ds_mensagem' => "Transferência cód: ".$transacao->id
            ]);

            DB::commit();
            $href = route('transacoes.index');

            return Response::json([
                'titulo'    => "Sucesso!!!",
                'tipo'      => "success",
                'message'   => "Transferência efetuada",
                'href'      => $href
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
