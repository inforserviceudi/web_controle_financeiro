<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\TipoConta;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ContasController extends Controller
{
    public function index()
    {
        $empresa_id = getIdEmpresa();
        $contas = Conta::select('id', 'ds_conta', 'ds_conta_principal', 'vr_saldo_inicial', 'tp_saldo_inicial', 'tipo_conta_id')
        ->where('empresa_id', $empresa_id)
            ->orderBy('id', "ASC")
            ->get();
        $tipos_contas = TipoConta::select('id', 'tp_conta')->orderBy('id', "ASC")->get();
        $nr_registros = $contas->count();

        return view('financas.contas.index',
            compact('contas', 'tipos_contas', 'nr_registros')
        );
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'ds_conta'  => 'required|string|max:100',
                'tipo_conta_id'  => 'required',
            ], [
                'ds_conta.required' => "Informe o nome da conta",
                'tipo_conta_id.required' => "Informe o tipo de conta",

                "ds_conta.string"    => "A descrição deve conter letras e números",
                "ds_conta.max"       => "Informe no máximo :max caracteres",
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => $validator->errors()->all(),
                    'erro'      => 'erro'
                ]);
            } else {
                if( isset($request['ds_conta_principal']) && $request['ds_conta_principal'] === "on"){
                    Conta::where('id', '>', 0)->update([
                        'ds_conta_principal'    => "N",
                    ]);
                }

                $cont_contas = Conta::where('id', '>', 0)->count();

                if( $cont_contas > 0 ){
                    $ds_conta_principal = ($request['ds_conta_principal'] == "on") ? "S" : "N";
                }else{
                    $ds_conta_principal = "S";
                }

                $empresa_id = getIdEmpresa();

                Conta::create([
                    'empresa_id'            => $empresa_id,
                    'ds_conta'              => strtoupper(tirarAcentos($request['ds_conta'])),
                    'ds_conta_principal'    => $ds_conta_principal,
                    'vr_saldo_inicial'      => formatValue($request['vr_saldo_inicial']),
                    'tp_saldo_inicial'      => strtoupper(tirarAcentos($request['tp_saldo_inicial'])),
                    'tipo_conta_id'         => $request['tipo_conta_id'],
                ]);

                $contas = Conta::where('empresa_id', $empresa_id)->orderBy("id", "ASC")->get();
                $tipos_contas = TipoConta::select('id', 'tp_conta')->orderBy('id', "ASC")->get();
                $tabela = '';

                foreach($contas as $item){
                    $edit_route = route('contas.atualiza.registro', ['id' => $item->id]);
                    $delete_route = route('contas.remove.registro', ['id' => $item->id]);
                    $submit_edit = "form-atualiza-conta-".$item->id;
                    $submit_delete = "form-remove-conta-".$item->id;
                    $checked = ($item->ds_conta_principal === "S") ? "checked" : "";
                    $selected = ($item->tp_saldo_inicial === "P") ? "selected" : ($item->tp_saldo_inicial === "N" ? "selected" : "");
                    $vr_saldo_inicial = number_format($item->vr_saldo_inicial, 2, ',', '.');

                    $tabela .= '<tr>';
                    $tabela .= '    <td>';
                    $tabela .= '        <form id="form-atualiza-conta-'.$item->id.'" action="'.$edit_route.'" method="post" class="form">';
                    $tabela .= '            <div class="col-md-4">';
                    $tabela .= '                <input type="text" name="ds_conta" class="form-control" value="'.$item->ds_conta.'" maxlength="150" required>';
                    $tabela .= '            </div>';
                    $tabela .= '            <div class="col-md-2">';
                    $tabela .= '                <div class="input-group mb-md">';
                    $tabela .= '                    <div class="input-group-btn">';
                    $tabela .= '                        <select name="tp_saldo_inicial" class="form-control" style="width: 40px;">';
                    $tabela .= '                            <option value="P" '.$selected.' style="font-size: 16px; font-weight: bold;"> + </option>';
                    $tabela .= '                            <option value="N" '.$selected.' style="font-size: 16px; font-weight: bold;"> - </option>';
                    $tabela .= '                        </select>';
                    $tabela .= '                    </div>';
                    $tabela .= '                    <input type="text" name="vr_saldo_inicial" class="form-control mask-valor" value="'.$vr_saldo_inicial.'" maxlength="12">';
                    $tabela .= '                </div>';
                    $tabela .= '            </div>';
                    $tabela .= '            <div class="col-md-2">';
                    $tabela .= '                <select name="tipo_conta_id" class="form-control js-single" required>';
                        foreach ($tipos_contas as $tpconta){
                            $tipo_conta_id = ($item->tipo_conta_id === $tpconta->id) ? "selected" : "";
                    $tabela .= '                    <option value="'.$tpconta->id.'" '.$tipo_conta_id.'>'.$tpconta->tp_conta.'</option>';
                        }
                    $tabela .= '                </select>';
                    $tabela .= '            </div>';
                    $tabela .= '            <div class="col-md-2">';
                    $tabela .= '                <div class="radio">';
                    $tabela .= '                    <label>';
                    $tabela .= '                        <input type="radio" name="ds_conta_principal" id="ds_conta_principal'.$item->id.'" '.$checked.'>Conta principal';
                    $tabela .= '                    </label>';
                    $tabela .= '                </div>';
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
                    $tabela .= '                <form id="form-remove-conta-'.$item->id.'" action="'.$delete_route.'" method="post" class="form">';
                    $tabela .= '                    <input type="hidden" name="tbody" value="#tbody_novo_registro">';
                    $tabela .= '                </form>';
                    $tabela .= '            </div>';
                    $tabela .= '        </div>';
                    $tabela .= '    </td>';
                    $tabela .= '</tr>';
                }

                DB::commit();

                return Response::json([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Conta criada",
                    'tabela'    => $tabela,
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
        // dd($request->all());
        $conta = Conta::find($id);

        if( !$conta ){
            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => "Registro não encontrado no banco de dados"
            ]);
        }

        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'ds_conta'  => 'required|string|max:100',
                'tipo_conta_id'  => 'required',
            ], [
                'ds_conta.required' => "Informe o nome da conta",
                'tipo_conta_id.required' => "Informe o tipo de conta",

                "ds_conta.string"    => "A descrição deve conter letras e números",
                "ds_conta.max"       => "Informe no máximo :max caracteres",
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => $validator->errors()->all(),
                    'erro'      => 'erro'
                ]);
            } else {

                $cont_contas = Conta::where('id', '>', 0)->count();

                if( $cont_contas == 1 ){
                    $ds_conta_principal = "S";
                }else{
                    if( $conta->ds_conta_principal === "S" ){
                        $ds_conta_principal = $conta->ds_conta_principal;
                    }else{
                        if( isset($request['ds_conta_principal']) && $request['ds_conta_principal'] === "on"){
                            Conta::where('id', '>', 0)->update([
                                'ds_conta_principal'    => "N",
                            ]);
                        }

                        $ds_conta_principal = ($request['ds_conta_principal'] == "on") ? "S" : "N";
                    }
                }

                $conta->update([
                    'ds_conta'              => strtoupper(tirarAcentos($request['ds_conta'])),
                    'ds_conta_principal'    => $ds_conta_principal,
                    'vr_saldo_inicial'      => formatValue($request['vr_saldo_inicial']),
                    'tp_saldo_inicial'      => ( $request['tp_saldo_inicial'] != $conta->tp_saldo_inicial ) ? strtoupper(tirarAcentos($request['tp_saldo_inicial'])) : $conta->tp_saldo_inicial,
                    'tipo_conta_id'         => $request['tipo_conta_id'],
                ]);

                $empresa_id = getIdEmpresa();

                $contas = Conta::where('empresa_id', $empresa_id)->orderBy("id", "ASC")->get();
                $tipos_contas = TipoConta::select('id', 'tp_conta')->orderBy('id', "ASC")->get();
                $tabela = '';

                foreach($contas as $item){
                    $edit_route = route('contas.atualiza.registro', ['id' => $item->id]);
                    $delete_route = route('contas.remove.registro', ['id' => $item->id]);
                    $submit_edit = "form-atualiza-conta-".$item->id;
                    $submit_delete = "form-remove-conta-".$item->id;
                    $checked = ($item->ds_conta_principal === "S") ? "checked" : "";
                    $vr_saldo_inicial = number_format($item->vr_saldo_inicial, 2, ',', '.');
                    $option_1 = ($item->tp_saldo_inicial === "P") ? "selected" : "";
                    $option_2 = ($item->tp_saldo_inicial === "N") ? "selected" : "";

                    $tabela .= '<tr>';
                    $tabela .= '    <td>';
                    $tabela .= '        <form id="form-atualiza-conta-'.$item->id.'" action="'.$edit_route.'" method="post" class="form">';
                    $tabela .= '            <div class="col-md-4">';
                    $tabela .= '                <input type="text" name="ds_conta" class="form-control" value="'.$item->ds_conta.'" maxlength="150" required>';
                    $tabela .= '            </div>';
                    $tabela .= '            <div class="col-md-2">';
                    $tabela .= '                <div class="input-group mb-md">';
                    $tabela .= '                    <div class="input-group-btn">';
                    $tabela .= '                        <select name="tp_saldo_inicial" class="form-control" style="width: 40px;">';
                    $tabela .= '                            <option value="P" '.$option_1.' style="font-size: 16px; font-weight: bold;"> + </option>';
                    $tabela .= '                            <option value="N" '.$option_2.' style="font-size: 16px; font-weight: bold;"> - </option>';
                    $tabela .= '                        </select>';
                    $tabela .= '                    </div>';
                    $tabela .= '                    <input type="text" name="vr_saldo_inicial" class="form-control mask-valor" value="'.$vr_saldo_inicial.'" maxlength="12">';
                    $tabela .= '                </div>';
                    $tabela .= '            </div>';
                    $tabela .= '            <div class="col-md-2">';
                    $tabela .= '                <select name="tipo_conta_id" class="form-control js-single" required>';
                        foreach ($tipos_contas as $tpconta){
                            $tipo_conta_id = ($item->tipo_conta_id === $tpconta->id) ? "selected" : "";
                    $tabela .= '                    <option value="'.$tpconta->id.'" '.$tipo_conta_id.'>'.$tpconta->tp_conta.'</option>';
                        }
                    $tabela .= '                </select>';
                    $tabela .= '            </div>';
                    $tabela .= '            <div class="col-md-2">';
                    $tabela .= '                <div class="radio">';
                    $tabela .= '                    <label>';
                    $tabela .= '                        <input type="radio" name="ds_conta_principal" id="ds_conta_principal'.$item->id.'" '.$checked.'>Conta principal';
                    $tabela .= '                    </label>';
                    $tabela .= '                </div>';
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
                    $tabela .= '                <form id="form-remove-conta-'.$item->id.'" action="'.$delete_route.'" method="post" class="form">';
                    $tabela .= '                    <input type="hidden" name="tbody" value="#tbody_novo_registro">';
                    $tabela .= '                </form>';
                    $tabela .= '            </div>';
                    $tabela .= '        </div>';
                    $tabela .= '    </td>';
                    $tabela .= '</tr>';
                }

                DB::commit();

                return Response::json([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Conta atualizada",
                    'tabela'    => $tabela,
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
        $conta = Conta::find($id);

        if( !$conta ){
            return Response::json([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => "Registro não encontrado no banco de dados"
            ]);
        }

        if( $conta->ds_conta_principal === "S" ){
            $update = Conta::where('ds_conta_principal', "N",)->orderBy('id', 'ASC')->first();

            if( $update ){
                $update->update([
                    'ds_conta_principal'    => "S",
                ]);
            }
        }

        $conta->delete();

        $empresa_id = getIdEmpresa();
        
        $contas = Conta::where('empresa_id', $empresa_id)->orderBy("id", "ASC")->get();
        $tipos_contas = TipoConta::select('id', 'tp_conta')->orderBy('id', "ASC")->get();
        $tabela = '';

        if( $contas->count() > 0 ){
            foreach($contas as $item){
                $edit_route = route('contas.atualiza.registro', ['id' => $item->id]);
                $delete_route = route('contas.remove.registro', ['id' => $item->id]);
                $submit_edit = "form-atualiza-conta-".$item->id;
                $submit_delete = "form-remove-conta-".$item->id;
                $checked = ($item->ds_conta_principal === "S") ? "checked" : "";
                $selected = ($item->tp_saldo_inicial === "P") ? "selected" : ($item->tp_saldo_inicial === "N" ? "selected" : "");
                $vr_saldo_inicial = number_format($item->vr_saldo_inicial, 2, ',', '.');

                $tabela .= '<tr>';
                $tabela .= '    <td>';
                $tabela .= '        <form id="form-atualiza-conta-'.$item->id.'" action="'.$edit_route.'" method="post" class="form">';
                $tabela .= '            <div class="col-md-4">';
                $tabela .= '                <input type="text" name="ds_conta" class="form-control" value="'.$item->ds_conta.'" maxlength="150" required>';
                $tabela .= '            </div>';
                $tabela .= '            <div class="col-md-2">';
                $tabela .= '                <div class="input-group mb-md">';
                $tabela .= '                    <div class="input-group-btn">';
                $tabela .= '                        <select name="tp_saldo_inicial" class="form-control" style="width: 40px;">';
                $tabela .= '                            <option value="P" '.$selected.' style="font-size: 16px; font-weight: bold;"> + </option>';
                $tabela .= '                            <option value="N" '.$selected.' style="font-size: 16px; font-weight: bold;"> - </option>';
                $tabela .= '                        </select>';
                $tabela .= '                    </div>';
                $tabela .= '                    <input type="text" name="vr_saldo_inicial" class="form-control mask-valor" value="'.$vr_saldo_inicial.'" maxlength="12">';
                $tabela .= '                </div>';
                $tabela .= '            </div>';
                $tabela .= '            <div class="col-md-2">';
                $tabela .= '                <select name="tipo_conta_id" class="form-control js-single" required>';
                    foreach ($tipos_contas as $tpconta){
                        $tipo_conta_id = ($item->tipo_conta_id === $tpconta->id) ? "selected" : "";
                $tabela .= '                    <option value="'.$tpconta->id.'" '.$tipo_conta_id.'>'.$tpconta->tp_conta.'</option>';
                    }
                $tabela .= '                </select>';
                $tabela .= '            </div>';
                $tabela .= '            <div class="col-md-2">';
                $tabela .= '                <div class="radio">';
                $tabela .= '                    <label>';
                $tabela .= '                        <input type="radio" name="ds_conta_principal" id="ds_conta_principal'.$item->id.'" '.$checked.'>Conta principal';
                $tabela .= '                    </label>';
                $tabela .= '                </div>';
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
                $tabela .= '                <form id="form-remove-conta-'.$item->id.'" action="'.$delete_route.'" method="post" class="form">';
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

        return Response::json([
            'titulo'    => "Sucesso!!!",
            'tipo'      => "success",
            'message'   => "Conta removida",
            'tabela'    => $tabela,
        ]);
    }
}
