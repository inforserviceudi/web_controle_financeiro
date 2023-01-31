<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class UsuariosController extends Controller
{
    public function index($empresa_id)
    {
        $admin = User::where("permissao", "admin")->first();
        $usuarios = User::where('empresa_id', $empresa_id)->where('permissao', 'user')->get();
        $nr_registros = User::where('empresa_id', $empresa_id)->where('permissao', 'user')->count();

        return view('cadastros.usuarios.index',
            compact('admin', 'usuarios', 'nr_registros')
        );
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'name'  => 'required|string|max:150',
                'email'  => 'required|string|max:150',
            ], [
                'name.required' => "Informe o nome do usuário",
                'email.required' => "Informe o email do usuário",

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
                $empresa_id = getIdEmpresa();

                User::create([
                    'empresa_id'    => $empresa_id,
                    'name'          => ucwords(tirarAcentos($request['name'])),
                    'email'         => tirarAcentos($request['email']),
                    'password'      => Hash::make(123456),
                    'permissao'     => 'user',
                ]);

                $usuarios = User::where('empresa_id', $empresa_id)->where('permissao', 'user')->orderBy("id", "ASC")->get();
                $tabela = '';

                foreach($usuarios as $item){
                    $delete_route = route('usuarios.remove.registro', ['id' => $item->id]);
                    $permissoes_route = route('usuarios.permissoes');
                    $route_logs = route('usuarios.modal.logs');
                    $submit_delete = "form-remove-usuario-".$item->id;

                    $tabela .= '<tr>';
                    $tabela .= '    <td>';
                    $tabela .= '        <div class="col-md-4">'. $item->name .'</div>';
                    $tabela .= '        <div class="col-md-4">'. $item->email .'</div>';
                    $tabela .= '        <div class="col-md-2">';
                    $tabela .= '            <strong>'. $item->permissao .'</strong> <br>';
                    $tabela .= '            <a href="'. $permissoes_route .'" class="btn btn-link"> Gerenciar </a>';
                    $tabela .= '        </div>';
                    $tabela .= '        <div class="col-md-1 text-center">';
                    $tabela .= '            <button type="button" class="btn btn-link btn-block modal-call" data-id="'. $item->id .'" data-width="modal-lg" data-url="'. $route_logs .'">';
                    $tabela .= '                Ver log';
                    $tabela .= '            </button>';
                    $tabela .= '        </div>';
                    $tabela .= '        <div class="col-md-1 text-center">';
                    $tabela .= '            <div class="btn-group">';
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
                    'message'   => "Usuário criado",
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

    public function modalLogs(Request $request)
    {
        dd($request->all());
    }
}
