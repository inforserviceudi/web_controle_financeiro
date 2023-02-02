<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Empresa;
use App\Models\Estado;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class CidadesController extends Controller
{
    public function index()
    {
        $cidades = Cidade::select('id', 'nm_cidade', 'estado_id')->orderBy('id', "ASC")->get();
        $estados = Estado::select('id', 'nm_estado', 'ds_sigla')->orderBy('nm_estado', 'ASC')->get();
        $nr_registros = $cidades->count();

        return view('cadastros.cidades.index',
            compact('cidades', 'estados', 'nr_registros')
        );
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'nm_cidade'  => 'required|string|max:150',
                'estado_id'  => 'required',
            ], [
                'nm_cidade.required' => "Informe o nome de cidade",
                'estado_id.required' => "Informe o estado",

                "string"    => "A descrição deve conter letras e números",
                "max"       => "Informe no máximo :max caracteres",
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => $validator->errors()->all(),
                    'erro'      => 'erro'
                ]);
            } else {
                Cidade::create([
                    "nm_cidade"     => strtoupper(tirarAcentos($request['nm_cidade'])),
                    "estado_id"     => $request['estado_id']
                ]);

                $cidades = Cidade::orderBy("id", "ASC")->get();
                $estados = Estado::select('id', 'nm_estado', 'ds_sigla')->orderBy('nm_estado', 'ASC')->get();
                $tabela = '';
                $tbody = $request['tbody'];

                foreach($cidades as $item){
                    $edit_route = route('cidades.atualiza.registro', ['id' => $item->id]);
                    $delete_route = route('cidades.remove.registro', ['id' => $item->id]);
                    $submit_edit = "form-atualiza-cidade-".$item->id;
                    $submit_delete = "form-remove-tipo-conta-".$item->id;

                    $tabela .= '<tr>';
                    $tabela .= '    <td>';
                    $tabela .= '        <form id="form-atualiza-cidade-'.$item->id.'" action="'.$edit_route.'" method="post" class="form">';
                    $tabela .= '            <div class="col-md-7">';
                    $tabela .= '                <input type="text" name="nm_cidade" class="form-control" value="'.$item->nm_cidade.'" maxlength="100">';
                    $tabela .= '            </div>';
                    $tabela .= '            <div class="col-md-3">';
                    $tabela .= '                <select name="estado_id" class="form-control js-single" required>';
                    foreach ($estados as $estado){
                        if($estado->id === $item->estado_id){
                            $selected = "selected";
                        }else{
                            $selected = null;
                        }
                    $tabela .= '                    <option value="'.$estado->id.'" '.$selected.'>'.$estado->ds_sigla.' - '.$estado->nm_estado.'</option>';
                    }
                    $tabela .= '                </select>';
                    $tabela .= '            </div>';
                    $tabela .= '        </form>';
                    $tabela .= '        <div class="col-md-2 text-center">';
                    $tabela .= '            <div class="btn-group">';
                    $tabela .= '                <button type="button" class="btn btn-info btn-sm btn-spin-pencil" title="Atualizar registro" onclick="submitForm(\''.$submit_edit.'\')">';
                    $tabela .= '                    <i class="fa fa-pencil fa-fw"></i>';
                    $tabela .= '                </button>';
                    $tabela .= '                <button type="button" class="btn btn-danger btn-sm btn-spin-trash-o" title="Remover registro" onclick="submitForm(\''.$submit_delete.'\')">';
                    $tabela .= '                    <i class="fa fa-trash-o fa-fw"></i>';
                    $tabela .= '                </button>';
                    $tabela .= '                <form id="form-remove-tipo-conta-'.$item->id.'" action="'.$delete_route.'" method="post" class="form">';
                    $tabela .= '                    <input type="hidden" name="tbody" value="#tbody_novo_registro">';
                    $tabela .= '                </form>';
                    $tabela .= '            </div>';
                    $tabela .= '        </div>';
                    $tabela .= '    </td>';
                    $tabela .= '</tr>';
                }

                $id_empresa = getIdEmpresa();

                // regista a ação de login do usuário na tabela de logs
                Log::create([
                    'empresa_id' => $id_empresa,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "C",
                    'ds_mensagem' => "Cidade: ". ucwords(tirarAcentos($request['nm_cidade']))
                ]);

                DB::commit();

                return Response::json([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Cidade criada",
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
        $cidade = Cidade::find($id);

        if( !$cidade ){
            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => "Registro não encontrado no banco de dados"
            ]);
        }

        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'nm_cidade'  => 'required|string|max:150',
                'estado_id'  => 'required',
            ], [
                'nm_cidade.required' => "Informe o nome de cidade",
                'estado_id.required' => "Informe o estado",

                "string"    => "A descrição deve conter letras e números",
                "max"       => "Informe no máximo :max caracteres",
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => $validator->errors()->all(),
                    'erro'      => 'erro'
                ]);
            } else {
                $cidade->update([
                    "nm_cidade"     => strtoupper(tirarAcentos($request['nm_cidade'])),
                    "estado_id"     => $request['estado_id']
                ]);

                // regista a ação de login do usuário na tabela de logs
                Log::create([
                    'empresa_id' => $id_empresa,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "A",
                    'ds_mensagem' => "Cidade: ". ucwords(tirarAcentos($request['nm_cidade']))
                ]);

                DB::commit();

                return Response::json([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Cidade atualizada",
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
        $cidade = Cidade::find($id);

        if( !$cidade ){
            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => "Registro não encontrado no banco de dados"
            ]);
        }

        $cidade->delete();

        $cidades = Cidade::orderBy("id", "ASC")->get();
        $estados = Estado::select('id', 'nm_estado', 'ds_sigla')->orderBy('nm_estado', 'ASC')->get();
        $tabela = '';
        $tbody = $request['tbody'];

        if( $cidades->count() > 0 ){
            foreach($cidades as $item){
                $edit_route = route('cidades.atualiza.registro', ['id' => $item->id]);
                $delete_route = route('cidades.remove.registro', ['id' => $item->id]);
                $submit_edit = "form-atualiza-cidade-".$item->id;
                $submit_delete = "form-remove-tipo-conta-".$item->id;

                $tabela .= '<tr>';
                $tabela .= '    <td>';
                $tabela .= '        <form id="form-atualiza-cidade-'.$item->id.'" action="'.$edit_route.'" method="post" class="form">';
                $tabela .= '            <div class="col-md-7">';
                $tabela .= '                <input type="text" name="nm_cidade" class="form-control" value="'.$item->nm_cidade.'" maxlength="100">';
                $tabela .= '            </div>';
                $tabela .= '            <div class="col-md-3">';
                $tabela .= '                <select name="estado_id" class="form-control js-single" required>';
                foreach ($estados as $estado){
                    if($estado->id === $item->estado_id){
                        $selected = "selected";
                    }else{
                        $selected = null;
                    }
                $tabela .= '                    <option value="'.$estado->id.'" '.$selected.'>'.$estado->ds_sigla.' - '.$estado->nm_estado.'</option>';
                }
                $tabela .= '                </select>';
                $tabela .= '            </div>';
                $tabela .= '        </form>';
                $tabela .= '        <div class="col-md-2 text-center">';
                $tabela .= '            <div class="btn-group">';
                $tabela .= '                <button type="button" class="btn btn-info btn-sm btn-spin-pencil" title="Atualizar registro" onclick="submitForm(\''.$submit_edit.'\')">';
                $tabela .= '                    <i class="fa fa-pencil fa-fw"></i>';
                $tabela .= '                </button>';
                $tabela .= '                <button type="button" class="btn btn-danger btn-sm btn-spin-trash-o" title="Remover registro" onclick="submitForm(\''.$submit_delete.'\')">';
                $tabela .= '                    <i class="fa fa-trash-o fa-fw"></i>';
                $tabela .= '                </button>';
                $tabela .= '                <form id="form-remove-tipo-conta-'.$item->id.'" action="'.$delete_route.'" method="post" class="form">';
                $tabela .= '                    <input type="hidden" name="tbody" value="#tbody_novo_registro">';
                $tabela .= '                </form>';
                $tabela .= '            </div>';
                $tabela .= '        </div>';
                $tabela .= '    </td>';
                $tabela .= '</tr>';
            }
        }else{
            $tabela .= '<tr id="tr_sem_registro">';
            $tabela .= '    <td class="text-center text-bold">Nenhum registro encontrado !!!</td>';
            $tabela .= '</tr>';
        }

        // regista a ação de login do usuário na tabela de logs
        Log::create([
            'empresa_id' => $id_empresa,
            'usuario_id'    => Auth::user()->id,
            'ds_acao'   => "E",
            'ds_mensagem' => "Cidade: ". ucwords($cidade->nm_cidade)
        ]);

        return Response::json([
            'titulo'    => "Sucesso!!!",
            'tipo'      => "success",
            'message'   => "Cidade removida",
            'tabela'    => $tabela,
            'tbody'     => $tbody
        ]);
    }
}
