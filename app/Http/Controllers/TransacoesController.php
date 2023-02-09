<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Conta;
use App\Models\Contato;
use App\Models\Log;
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
        $transacoes = Transacao::where('empresa_id', $empresa_id)
        ->where('categoria_id', 1)
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

        if( $transacao_id == 0 ){
            $modal_title = "Nova transação";
        }else{
            $modal_title = "Editar transação " . $transacao_id;
        }

        return view('transacoes.modal-create-edit',
            compact('modal_title', 'empresa_id', 'categoria_id', 'conta_id', 'transacao', 'categoria', 'conta',
                'transacao_id', 'contatos', 'subcategorias')
        );
    }

    public function ajaxParcelamento(Request $request)
    {
        dd($request->all());
    }
}
