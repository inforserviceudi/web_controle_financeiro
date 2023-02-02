<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\TipoConta;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class TiposContasController extends Controller
{
    private $empresa_id;

    function __construct()
    {
        $this->empresa_id = getIdEmpresa();
    }

    public function index()
    {
        $tipos_contas = TipoConta::select('id', 'tp_conta')->orderBy('id', "ASC")->get();
        $nr_registros = $tipos_contas->count();

        return view('financas.tipos-contas.index',
            compact('tipos_contas', 'nr_registros')
        );
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'tp_conta'  => 'required|string|max:100',
            ], [
                'tp_conta.required' => "Informe o tipo de conta",

                "tp_conta.string"    => "A descrição deve conter letras e números",
                "tp_conta.max"       => "Informe no máximo :max caracteres",
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => $validator->errors()->all(),
                    'erro'      => 'erro'
                ]);
            } else {
                TipoConta::create([
                    "tp_conta"      => strtoupper(tirarAcentos($request['tp_conta'])),
                ]);

                $tp_contas = TipoConta::orderBy("id", "ASC")->get();
                $tabela = '';
                $tbody = $request['tbody'];

                foreach($tp_contas as $item){
                    $edit_route = route('tipos-contas.atualiza.registro', ['id' => $item->id]);
                    $delete_route = route('tipos-contas.remove.registro', ['id' => $item->id]);
                    $submit_edit = "form-atualiza-tipo-conta-".$item->id;
                    $submit_delete = "form-remove-tipo-conta-".$item->id;

                    $tabela .= '<tr>';
                    $tabela .= '    <td>';
                    $tabela .= '        <form id="form-atualiza-tipo-conta-'.$item->id.'" action="'.$edit_route.'" method="post" class="form">';
                    $tabela .= '            <input type="text" name="tp_conta" class="form-control" value="'.$item->tp_conta.'" maxlength="100">';
                    $tabela .= '        </form>';
                    $tabela .= '    </td>';
                    $tabela .= '    <td width="8%" class="text-center">';
                    $tabela .= '        <div class="btn-group">';
                    $tabela .= '            <button type="button" class="btn btn-info btn-sm btn-spin-pencil" title="Atualizar registro" onclick="submitForm(\''.$submit_edit.'\')">';
                    $tabela .= '                <i class="fa fa-pencil fa-fw"></i>';
                    $tabela .= '            </button>';
                    $tabela .= '            <button type="button" class="btn btn-danger btn-sm btn-spin-trash-o" title="Remover registro" onclick="submitForm(\''.$submit_delete.'\')">';
                    $tabela .= '                <i class="fa fa-trash-o fa-fw"></i>';
                    $tabela .= '            </button>';
                    $tabela .= '            <form id="form-remove-tipo-conta-'.$item->id.'" action="'.$delete_route.'" method="post" class="form">';
                    $tabela .= '                <input type="hidden" name="tbody" value="#tbody_novo_registro">';
                    $tabela .= '            </form>';
                    $tabela .= '        </div>';
                    $tabela .= '    </td>';
                    $tabela .= '</tr>';
                }

                // regista a ação de login do usuário na tabela de logs
                /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
                Log::create([
                    'empresa_id' => $this->empresa_id,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "C",
                    'ds_mensagem' => "Tipo de conta: ". ucwords($request['tp_conta'])
                ]);

                DB::commit();

                return Response::json([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Tipo de conta criada",
                    'tabela'    => $tabela,
                    'tbody'     => $tbody
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
        $tipo_conta = TipoConta::find($id);

        if( !$tipo_conta ){
            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => "Registro não encontrado no banco de dados"
            ]);
        }

        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'tp_conta'  => 'required|string|max:100',
            ], [
                'tp_conta.required' => "Informe o tipo de conta",

                "tp_conta.string"    => "A descrição deve conter letras e números",
                "tp_conta.max"       => "Informe no máximo :max caracteres",
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => $validator->errors()->all(),
                    'erro'      => 'erro'
                ]);
            } else {
                $tipo_conta->update([
                    "tp_conta"      => strtoupper(tirarAcentos($request['tp_conta'])),
                ]);

                // regista a ação de login do usuário na tabela de logs
                /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
                Log::create([
                    'empresa_id' => $this->empresa_id,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "A",
                    'ds_mensagem' => "Tipo de conta: ". ucwords($request['tp_conta'])
                ]);

                DB::commit();

                return Response::json([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Tipo de conta atualizado",
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

    public function delete(Request $request, $id)
    {
        $tipo_conta = TipoConta::find($id);

        if( !$tipo_conta ){
            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => "Registro não encontrado no banco de dados"
            ]);
        }

        $tipo_conta->delete();

        $tp_contas = TipoConta::orderBy("id", "ASC")->get();
        $tabela = '';
        $tbody = $request['tbody'];

        if( $tp_contas->count() > 0 ){
            foreach($tp_contas as $item){
                $edit_route = route('tipos-contas.atualiza.registro', ['id' => $item->id]);
                $delete_route = route('tipos-contas.remove.registro', ['id' => $item->id]);
                $submit_edit = "form-atualiza-tipo-conta-".$item->id;
                $submit_delete = "form-remove-tipo-conta-".$item->id;

                $tabela .= '<tr>';
                $tabela .= '    <td>';
                $tabela .= '        <form id="form-atualiza-tipo-conta-'.$item->id.'" action="'.$edit_route.'" method="post" class="form">';
                $tabela .= '            <input type="text" name="tp_conta" class="form-control" value="'.$item->tp_conta.'" maxlength="100">';
                $tabela .= '        </form>';
                $tabela .= '    </td>';
                $tabela .= '    <td width="8%" class="text-center">';
                $tabela .= '        <div class="btn-group">';
                $tabela .= '            <button type="button" class="btn btn-info btn-sm btn-spin-pencil" title="Atualizar registro" onclick="submitForm(\''.$submit_edit.'\')">';
                $tabela .= '                <i class="fa fa-pencil fa-fw"></i>';
                $tabela .= '            </button>';
                $tabela .= '            <button type="button" class="btn btn-danger btn-sm btn-spin-trash-o" title="Remover registro" onclick="submitForm(\''.$submit_delete.'\')">';
                $tabela .= '                <i class="fa fa-trash-o fa-fw"></i>';
                $tabela .= '            </button>';
                $tabela .= '            <form id="form-remove-tipo-conta-'.$item->id.'" action="'.$delete_route.'" method="post" class="form">';
                $tabela .= '                <input type="hidden" name="tbody" value="#tbody_novo_registro">';
                $tabela .= '            </form>';
                $tabela .= '        </div>';
                $tabela .= '    </td>';
                $tabela .= '</tr>';
            }
        }else{
            $tabela .= '<tr id="tr_sem_registro">';
            $tabela .= '    <td colspan="2" class="text-center text-bold">Nenhum registro encontrado !!!</td>';
            $tabela .= '</tr>';
        }

        // regista a ação de login do usuário na tabela de logs
        /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
        Log::create([
            'empresa_id' => $this->empresa_id,
            'usuario_id'    => Auth::user()->id,
            'ds_acao'   => "E",
            'ds_mensagem' => "Tipo de conta: ". ucwords($tipo_conta->tp_conta)
        ]);

        return Response::json([
            'titulo'    => "Sucesso!!!",
            'tipo'      => "success",
            'message'   => "Tipo de conta removido",
            'tabela'    => $tabela,
            'tbody'     => $tbody
        ]);
    }
}
