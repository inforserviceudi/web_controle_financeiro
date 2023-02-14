<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Conta;
use App\Models\Contato;
use App\Models\Log;
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
        ->whereBetween('parcelas_transacoes.dt_vencimento', [ Carbon::today()->format('Y-m-').'01', Carbon::today()->format('Y-m-').'31' ])
        ->get();

        return view('transacoes.index',
            compact('empresa_id', 'contas', 'contaP', 'meses', 'ano_atual', 'mes_atual', 'categorias', 'transacoes')
        );
    }

    public function selecionaConta(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $empresa_id = $request['empresa_id'];
            $conta_id = $request['conta_id'];

            if( $conta_id > 0 ){
                $contas = Conta::select('id', 'ds_conta', 'ds_conta_principal', 'vr_saldo_inicial')
                ->where('empresa_id', $empresa_id)
                ->where('id', '<>', $conta_id)
                ->orderBy('id', "ASC")
                ->get();
                $contaP = Conta::find($conta_id);
                $saldo_total_conta = 0;
            }else{
                $contas = Conta::select('id', 'ds_conta', 'ds_conta_principal', 'vr_saldo_inicial')
                ->where('empresa_id', $empresa_id)
                ->orderBy('id', "ASC")
                ->get();
                $contaP = false;
                $saldo_total_conta = Conta::where('empresa_id', $empresa_id)
                ->sum('vr_saldo_inicial');
            }

            $categorias = Categoria::select('id', 'nome')->get();
            $meses = [
                'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ];
            $ano_atual = Carbon::today()->format('Y');
            $mes_atual = Carbon::today()->format('m');
            $transacoes = Transacao::where('empresa_id', $empresa_id)->get();

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
                    'transacoes')
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
        $transacao = Transacao::find($transacao_id);
        $categoria = Categoria::find($categoria_id);
        $conta = Conta::find($conta_id);
        $contatos = Contato::select('id', 'rz_social')->where('empresa_id', $empresa_id)->get();
        $subcategorias = SubCategoria::select('id', 'nome')->where('empresa_id', $empresa_id)->where('categoria_id', $categoria_id)->get();
        $parcelas = ParcelaTransacao::where('transacao_id', $transacao->id)->orderBy('nr_parcela', 'ASC')->get();

        if( $transacao_id == 0 ){
            $modal_title = "Nova transação";
            $valor_parcela = 0;
        }else{
            $modal_title = "Editar transação " . $transacao_id;
            $valor_parcela = ParcelaTransacao::where('transacao_id', $transacao->id)->sum('vr_parcela');
        }

        return view('transacoes.modal-create-edit',
            compact('modal_title', 'empresa_id', 'categoria_id', 'conta_id', 'transacao', 'categoria', 'conta',
                'transacao_id', 'contatos', 'subcategorias', 'valor_parcela', 'parcelas')
        );
    }

    public function ajaxParcelamento(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $frequencia = $request['frequencia'];
            $parcela = $request['nr_parcelas'];
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

            $vr_parcela = ($valor_total / $parcela);

            for( $i = 0; $i < $parcela; $i++ ){
                $tabela .=   '<tr>';
                $tabela .=   '  <td width="15%">'. ($i+1) .' / '. $parcela .'</td>';
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
                $tabela .=   '</tr>';
            }

            $tabela .=   '<tr>';
            $tabela .=   '  <td colspan="2" class="text-right">Total: </td>';
            $tabela .=   '  <td colspan="1" class="text-bold soma_parcelas">'. number_format($soma_parcelas, 2, ',', '.') .'</td>';
            $tabela .=   '</tr>';

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
            if( isset($request['recebido_de']) ){
                $validator = Validator::make($request->all(), [
                    'descricao'  => 'required|string|max:150',
                    'dt_transacao'  => 'required',
                    'subcategoria_id'  => 'required',
                    'valor_parcela'  => 'required',
                    'recebido_de'  => 'required',
                ], [
                    'descricao.required' => "Informe a descrição da transação",
                    'dt_transacao.required' => "Informe a data da transação",
                    'subcategoria_id.required' => "Informe a categoria da transação",
                    'valor_parcela.required' => "Informe o valor da transação",
                    'recebido_de.required' => "Informe o contato",

                    "string"    => "A descrição deve conter letras e números",
                    "max"       => "Informe no máximo :max caracteres",
                ]);
            }elseif( isset($request['pago_a']) ){
                $validator = Validator::make($request->all(), [
                    'descricao'  => 'required|string|max:150',
                    'dt_transacao'  => 'required',
                    'subcategoria_id'  => 'required',
                    'valor_parcela'  => 'required',
                    'pago_a'  => 'required',
                ], [
                    'descricao.required' => "Informe a descrição da transação",
                    'dt_transacao.required' => "Informe a data da transação",
                    'subcategoria_id.required' => "Informe a categoria da transação",
                    'valor_parcela.required' => "Informe o valor da transação",
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
                $valor_parcela = formatValue($request['valor_parcela']);

                $transacao = Transacao::create([
                    'empresa_id'    => $empresa_id,
                    'conta_id'  => $conta_id,
                    'categoria_id'  => $categoria_id,
                    'subcategoria_id'   => $request['subcategoria_id'],
                    'dt_transacao'  => verifyDateFormat($request['dt_transacao']),
                    'dt_competencia'    => verifyDateFormat($request['dt_competencia']),
                    'descricao' => $request['descricao'],
                    'recebido_de'   => $request['recebido_de'],
                    'pago_a'    => $request['pago_a'],
                    'tipo_pagamento'    => $request['tipo_pagamento'],
                    'forma_pagamento'   => $request['forma_pagamento'],
                    'ds_pago'   => "N",
                    'nr_documento'  => $request['nr_documento'],
                    'comentarios'   => $request['comentarios'],
                    'repetir_transacao' => $request['repetir_transacao'],
                ]);

                if( $request['tipo_pagamento'] === "A" ){
                    ParcelaTransacao::create([
                        'transacao_id'  => $transacao->id,
                        'nr_parcela'    => 1,
                        'vr_parcela'    => $valor_parcela,
                        'dt_vencimento' => verifyDateFormat($request['dt_transacao']),
                        'dt_pagamento'  => null,
                        'ds_pago'       => "N",
                    ]);
                }elseif( $request['tipo_pagamento'] === "P" ){
                    if( isset($request['vr_parcela']) ){
                        for( $i = 0; $i < count($request['vr_parcela']); $i++ ){
                            ParcelaTransacao::create([
                                'transacao_id'  => $transacao->id,
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
}
