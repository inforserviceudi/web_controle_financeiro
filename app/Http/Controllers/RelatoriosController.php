<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Empresa;
use App\Models\Log;
use App\Models\Transacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class RelatoriosController extends Controller
{
    public function index()
    {
        $empresa_id = getIdEmpresa();
        $dt_inicial = Carbon::today()->format('Y-m').'-01';
        $dt_final = Carbon::today()->format('Y-m').'-31';
        $contas = Conta::select('id', 'ds_conta')->where('empresa_id', $empresa_id)->get();
        $despesas = array('Descrição', 'Dia', 'Tipo', 'Categoria', 'Pago_a');
        $recebimentos = array('Descrição', 'Dia', 'Tipo', 'Categoria', 'Recebido_de');
        $fluxo_caixa = array('Extrato', 'DRE');

        return view('relatorios.index',
            compact('dt_inicial', 'dt_final', 'contas', 'despesas', 'recebimentos', 'fluxo_caixa')
        );
    }

    public function filtrar(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'dt_inicial'  => 'required',
                'dt_final'  => 'required',
                'conta_id'  => 'required',
            ], [
                'dt_inicial.required' => "Informe a data inicial",
                'dt_final.required' => "Informe a data final",
                'conta_id.required' => "Informe a conta",
            ]);

            $dt_inicial = verifyDateFormat($request['dt_inicial']);
            $dt_final = verifyDateFormat($request['dt_final']);

            if( $dt_final < $dt_inicial ){
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => "A data final deve ser maior que a data inicial",
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
                $empresa_id = getIdEmpresa();
                $tabela = $titulo_tabela = $tbody = $thead = "";
                $ds_pago = $request['ds_pago'];
                $mostrar_data = $request['mostrar_data'];
                $conta_id = $request['conta_id'];
                $tp_relatorio = $request['tp_relatorio'];
                $tp_filtro = $request['tp_filtro'];
                $soma_valor = 0;
                $empresa = Empresa::find($empresa_id);
                $nm_empresa = $empresa->nm_empresa;
                $conta = Conta::find($conta_id);
                $nm_conta = $conta->ds_conta;
                $saldo_inicial = number_format($conta->vr_saldo_inicial, 2, ',', '.');
                $periodo_relatorio = Carbon::parse($request['dt_inicial'])->format('d/m/Y') .' à '. Carbon::parse($request['dt_final'])->format('d/m/Y');

                if($tp_relatorio === "D" ){
                    $titulo_tabela = "Despesas por";
                }elseif($tp_relatorio === "R" ){
                    $titulo_tabela = "Recebimentos por";
                }elseif($tp_relatorio === "F" ){
                    $titulo_tabela = "Fluxo de caixa -";
                }

                switch ( $tp_filtro ) {
                    case 'Descrição':
                        $titulo_tabela = $titulo_tabela.' Descrição';
                        $transacoes = Transacao::qryRelatorio('transacoes.descricao', null, null, $empresa_id, $ds_pago, $mostrar_data, $conta_id, $dt_final, $dt_inicial, $tp_relatorio);

                        $thead .= '    <tr>';
                        $thead .= '        <th>Descrição</th>';
                        $thead .= '        <th width="15%" class="text-center">Valor</th>';
                        $thead .= '    </tr>';

                        foreach($transacoes as $trans){
                            $vr_parcela = number_format($trans->vr_parcela, 2, ',', '.');

                            $tbody .= '    <tr  class="border-top">';
                            $tbody .= '        <td>'. $trans->descricao .'</td>';
                            $tbody .= '        <td width="15%" class="text-center">R$ '. $vr_parcela .'</td>';
                            $tbody .= '    </tr>';

                            $soma_valor = ( $soma_valor + $trans->vr_parcela );
                        }

                        break;
                    case 'Dia':
                        $titulo_tabela = $titulo_tabela.' Dia';
                        $transacoes = Transacao::qryRelatorio('transacoes.dt_transacao', null, null, $empresa_id, $ds_pago, $mostrar_data, $conta_id, $dt_final, $dt_inicial, $tp_relatorio);

                        $thead .= '    <tr>';
                        $thead .= '        <th>Data</th>';
                        $thead .= '        <th width="15%" class="text-center">Valor</th>';
                        $thead .= '    </tr>';

                        foreach($transacoes as $trans){
                            $vr_parcela = number_format($trans->vr_parcela, 2, ',', '.');
                            $dt_transacao = Carbon::parse($trans->dt_transacao)->format('d/m/Y');

                            $tbody .= '    <tr class="border-top">';
                            $tbody .= '        <td>'. $dt_transacao .'</td>';
                            $tbody .= '        <td width="15%" class="text-center">R$ '. $vr_parcela .'</td>';
                            $tbody .= '    </tr>';

                            $soma_valor = ( $soma_valor + $trans->vr_parcela );
                        }

                        break;
                    case 'Tipo':  //// colocar o nome da categoria
                        $titulo_tabela = $titulo_tabela.' Tipo';
                        $transacoes = Transacao::qryRelatorio('transacoes.categoria_id', null, null, $empresa_id, $ds_pago, $mostrar_data, $conta_id, $dt_final, $dt_inicial, $tp_relatorio);

                        $thead .= '    <tr>';
                        $thead .= '        <th>Descrição</th>';
                        $thead .= '        <th width="15%" class="text-center">Valor</th>';
                        $thead .= '    </tr>';

                        foreach($transacoes as $trans){
                            $vr_parcela = number_format($trans->vr_parcela, 2, ',', '.');

                            $tbody .= '    <tr class="border-top">';
                            $tbody .= '        <td>'. $trans->categoria->nome .'</td>';
                            $tbody .= '        <td width="15%" class="text-center">R$ '. $vr_parcela .'</td>';
                            $tbody .= '    </tr>';

                            $soma_valor = ( $soma_valor + $trans->vr_parcela );
                        }

                        break;
                    case 'Categoria':   //// colocar o nome da sub categoria
                        $titulo_tabela = $titulo_tabela.' Categoria';
                        $transacoes = Transacao::qryRelatorio('transacoes.subcategoria_id', null, null, $empresa_id, $ds_pago, $mostrar_data, $conta_id, $dt_final, $dt_inicial, $tp_relatorio);

                        $thead .= '    <tr>';
                        $thead .= '        <th>Categoria</th>';
                        $thead .= '        <th width="15%" class="text-center">Valor</th>';
                        $thead .= '    </tr>';

                        foreach($transacoes as $trans){
                            $vr_parcela = number_format($trans->vr_parcela, 2, ',', '.');

                            $tbody .= '    <tr class="border-top">';
                            $tbody .= '        <td>'. $trans->subcategoria->nome .'</td>';
                            $tbody .= '        <td width="15%" class="text-center">R$ '. $vr_parcela .'</td>';
                            $tbody .= '    </tr>';

                            $soma_valor = ( $soma_valor + $trans->vr_parcela );
                        }

                        break;
                    case 'Pago_a':
                        $titulo_tabela = $titulo_tabela.' Pago a';
                        $transacoes = Transacao::qryRelatorio('transacoes.pago_a', null, null, $empresa_id, $ds_pago, $mostrar_data, $conta_id, $dt_final, $dt_inicial, $tp_relatorio);

                        $thead .= '    <tr>';
                        $thead .= '        <th>Favorecido / Credor</th>';
                        $thead .= '        <th width="15%" class="text-center">Valor</th>';
                        $thead .= '    </tr>';

                        foreach($transacoes as $trans){
                            $vr_parcela = number_format($trans->vr_parcela, 2, ',', '.');

                            $tbody .= '    <tr class="border-top">';
                            $tbody .= '        <td>'. $trans->pago->rz_social .'</td>';
                            $tbody .= '        <td width="15%" class="text-center">R$ '. $vr_parcela .'</td>';
                            $tbody .= '    </tr>';

                            $soma_valor = ( $soma_valor + $trans->vr_parcela );
                        }

                        break;
                    case 'Recebido_de':
                        $titulo_tabela = $titulo_tabela.' Recebido de';
                        $transacoes = Transacao::qryRelatorio('transacoes.recebido_de', null, null, $empresa_id, $ds_pago, $mostrar_data, $conta_id, $dt_final, $dt_inicial, $tp_relatorio);

                        $thead .= '    <tr>';
                        $thead .= '        <th>Favorecido</th>';
                        $thead .= '        <th width="15%" class="text-center">Valor</th>';
                        $thead .= '    </tr>';

                        foreach($transacoes as $trans){
                            $vr_parcela = number_format($trans->vr_parcela, 2, ',', '.');

                            $tbody .= '    <tr class="border-top">';
                            $tbody .= '        <td>'. $trans->recebido->rz_social .'</td>';
                            $tbody .= '        <td width="15%" class="text-center">R$ '. $vr_parcela .'</td>';
                            $tbody .= '    </tr>';

                            $soma_valor = ( $soma_valor + $trans->vr_parcela );
                        }

                        break;
                    case 'Extrato':
                        $titulo_tabela = $titulo_tabela.' Extrato';
                        $transacoes = Transacao::qryRelatorio('transacoes.dt_transacao', 'transacoes.descricao', 'transacoes.subcategoria_id', $empresa_id, $ds_pago, $mostrar_data, $conta_id, $dt_final, $dt_inicial, $tp_relatorio);

                        $thead .= '    <tr>';
                        $thead .= '        <th colspan="4" class="text-right">Saldo anterior</th>';
                        $thead .= '        <th class="text-center text-bold text-success">R$ '. $saldo_inicial .'</th>';
                        $thead .= '    </tr>';
                        $thead .= '    <tr>';
                        $thead .= '        <th>Data</th>';
                        $thead .= '        <th>Descrição</th>';
                        $thead .= '        <th>Categoria</th>';
                        $thead .= '        <th width="15%" class="text-center">Valor</th>';
                        $thead .= '        <th width="15%" class="text-center">Saldo</th>';
                        $thead .= '    </tr>';

                        $saldo1 = $conta->vr_saldo_inicial;

                        foreach($transacoes as $trans){
                            $tipo_transacao = $trans->tipo_transacao;
                            $dt_transacao = Carbon::parse($trans->dt_transacao)->format('d/m/Y');
                            $ext_parcela = $trans->vr_parcela;

                            if( $tipo_transacao === "R" ){
                                $saldo1 = ( $saldo1 + $ext_parcela );
                            }else{
                                $saldo1 = ( $saldo1 - $ext_parcela );
                            }

                            $vr_parcela = number_format($trans->vr_parcela, 2, ',', '.');
                            $saldo = number_format($saldo1, 2, ',', '.');
                            $cor_valor = ( $tipo_transacao === "R" ) ? "text-success" : "text-danger";
                            $sinal_valor = ( $tipo_transacao === "D" ) ? "- R$" : "R$";

                            $tbody .= '    <tr class="border-top">';
                            $tbody .= '        <td>'. $dt_transacao .'</td>';
                            $tbody .= '        <td>'. $trans->descricao .'</td>';
                            $tbody .= '        <td>'. $trans->subcategoria->nome .'</td>';
                            $tbody .= '        <td width="15%" class="text-center '. $cor_valor .'">'. $sinal_valor .' '. $vr_parcela .'</td>';
                            $tbody .= '        <td width="15%" class="text-center">R$ '. $saldo .'</td>';
                            $tbody .= '    </tr>';

                            $soma_valor = $saldo1;
                        }

                        break;
                    case 'DRE':
                        $titulo_tabela = $titulo_tabela.' DRE';
                        break;
                }

                if( $transacoes->count() > 0 ){
                    $colspan = ( $tp_filtro === 'Extrato' ) ? '4' : '1';
                    $vr_total = number_format($soma_valor, 2, ',', '.');

                    $tabela .= '<caption class="text-uppercase text-bold">'. $titulo_tabela .'</caption>';
                    $tabela .= '<thead>';
                    $tabela .=      $thead;
                    $tabela .= '</thead>';
                    $tabela .= '<tbody>';
                    $tabela .=      $tbody;
                    $tabela .= '    <tr class="border-top">';
                    $tabela .= '        <td colspan="'. $colspan .'">Total</td>';
                    $tabela .= '        <td width="15%" class="text-center text-bold">R$ '. $vr_total .'</td>';
                    $tabela .= '    </tr>';
                    $tabela .= '    <tr class="no-print">';
                    $tabela .= '        <td colspan="'. ($colspan + 1) .'" class="text-right">';
                    $tabela .= '            <button type="button" id="btn_relatorio" class="btn btn-primary btn-sm text-bold">Gerar pdf ou imprimir</button>';
                    $tabela .= '        </td>';
                    $tabela .= '    </tr>';
                    $tabela .= '</tbody>';

                }else{
                    $tabela .= '<caption class="text-uppercase text-bold">'. $titulo_tabela .'</caption>';
                    $tabela .= '<tbody>';
                    $tabela .= '    <tr>';
                    $tabela .= '        <td colspan="4" class="text-center text-bold">Nenhum registro encontrado !!!</td>';
                    $tabela .= '    </tr>';
                    $tabela .= '</tbody>';
                }

                // regista a ação de login do usuário na tabela de logs
                /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
                Log::create([
                    'empresa_id' => $empresa_id,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "C",
                    'ds_mensagem' => "Gerou relatório"
                ]);

                DB::commit();

                return Response::json([
                    'tabela'    => $tabela,
                    'nm_empresa'    => $nm_empresa,
                    'nm_conta'    => $nm_conta,
                    'periodo_relatorio'    => $periodo_relatorio,
                ]);
            }
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
}
