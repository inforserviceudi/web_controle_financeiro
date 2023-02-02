<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\DataTables;

class UsuariosController extends Controller
{
    public function index($empresa_id)
    {
        $id_empresa = getIdEmpresa();

        if( $id_empresa == $empresa_id ){
            $emp_id = $empresa_id;
        }else{
            $emp_id = $id_empresa;
        }

        $admin = User::where("permissao", "admin")->first();
        $usuarios = User::where('empresa_id', $emp_id)->where('permissao', 'user')->get();
        $nr_registros = User::where('empresa_id', $emp_id)->where('permissao', 'user')->count();

        return view('cadastros.usuarios.index',
            compact('admin', 'usuarios', 'nr_registros', 'emp_id')
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

                // regista a ação de login do usuário na tabela de logs
                /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
                Log::create([
                    'empresa_id' => $this->empresa_id,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "C",
                    'ds_mensagem' => "Usuário: ". ucwords($request['name'])
                ]);

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
        // dd($request->all());
        $id = $request['id'];
        $empresa_id = getIdEmpresa();
        $logs = Log::where("empresa_id", $empresa_id)->where("usuario_id", $id)->orderBy("created_at", "DESC")->paginate(5);
        $nm_usuario = $logs[0]->usuario->name;
        $usuario_id = $logs[0]['usuario_id'];

        return view('cadastros.usuarios.modal-logs',
            compact('nm_usuario', 'logs', 'empresa_id', 'usuario_id')
        );
    }

    public function dataTable(Request $request)
    {
        // dd($request->all());
        if ($request->ajax()) {
            $empresa_id = $request['empresa_id'];
            $usuario_id = $request['usuario_id'];

            $data = Log::where("empresa_id", $empresa_id)->where("usuario_id", $usuario_id)->orderBy("created_at", "DESC")->get();

            return  DataTables::of($data)
                ->addColumn('created_at', function($data) {
                    return Carbon::parse($data->created_at)->format('d/m/Y H:i:s');
                })
                ->addColumn('ds_acao', function($data) {
                    /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
                    switch ($data->ds_acao) {
                        case 'A':
                            return '<span class="badge bg-info">Alterou</span>';
                            break;                        
                        case 'C':
                            return '<span class="badge bg-success">Criou</span>';
                            break;                        
                        case 'E':
                            return '<span class="badge bg-danger">Excluiu</span>';
                            break;                        
                        case 'L':
                            return '<span class="badge bg-warning">Login</span>';
                            break;                        
                        default:
                            # code...
                            break;
                    }
                })
                ->addColumn('ds_mensagem', function($data) {
                    return $data->ds_mensagem;
                })
                ->rawColumns(['created_at', 'ds_acao', 'ds_mensagem'])
                ->make(true);
        }

        return view("cadastros.usuarios.modal-logs");
    }
}
