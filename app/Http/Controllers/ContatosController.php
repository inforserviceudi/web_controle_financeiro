<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Contato;
use App\Models\Estado;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ContatosController extends Controller
{
    public function index()
    {
        $empresa_id = getIdEmpresa();

        return view('cadastros.contatos.index',
            compact('empresa_id')
        );
    }

    public function dataTable(Request $request)
    {
        // dd($request->all());
        if ($request->ajax()) {
            $empresa_id = $request['empresa_id'];

            $data = Contato::where("empresa_id", $empresa_id)->orderBy("created_at", "DESC")->get();

            return  DataTables::of($data)
                ->addColumn('rz_social', function($data) {
                    return $data->rz_social;
                })
                ->addColumn('tp_contato', function($data) {
                    /// 1 - CLIENTE / 2 - FORNCEDEDOR / 3 - FUNCIONARIO
                    switch ($data->tp_contato) {
                        case 1:
                            return '<span class="badge">Cliente</span>';
                            break;
                        case 2:
                            return '<span class="badge">Fornecedor</span>';
                            break;
                        case 3:
                            return '<span class="badge">Funcionário</span>';
                            break;
                        default:
                            # code...
                            break;
                    }
                })
                ->addColumn('acoes', function($data) {
                    $route = route("contatos.modal.create-edit");
                    $route2 = route('contatos.modal.delete');

                    return '<div class="btn-group">
                                <button type="button" class="btn btn-info btn-sm text-white modal-call" data-id="'. $data->id .'" data-width="modal-md" data-url="'. $route .'" title="Editar informações">
                                    <i class="fa fa-pencil fa-fw"></i>
                                </button>
                                <button class="btn btn-danger btn-sm text-white modal-call" data-id="'. $data->id .'" data-width="modal-md" data-url="'. $route2 .'" title="Remover registro">
                                    <i class="fa fa-trash-o fa-fw"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['rz_social', 'tp_contato', 'acoes'])
                ->make(true);
        }

        return view('cadastros.contatos.index');
    }

    public function modalCreateEdit(Request $request)
    {
        $id = $request['id'];
        $contato = Contato::find($id);
        $estados = Estado::select('id', 'ds_sigla', 'nm_estado')->orderBy('nm_estado', 'ASC')->get();
        $cidades = Cidade::select('id', 'nm_cidade')->orderBy('nm_cidade', 'ASC')->get();

        if( $id == 0 ){
            $modal_title = "Cadastrar contato";
        }else{
            $modal_title = "Editar contato" . $contato->rz_social;
        }

        return view('cadastros.contatos.modal-create-edit',
            compact('id', 'modal_title', 'contato', 'estados', 'cidades')
        );
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'tp_pessoa'  => 'required|string|max:1',
                'tp_contato'  => 'required|string|max:1',
                'rz_social'  => 'required|string|max:150',
            ], [
                'tp_pessoa.required' => "Informe se é pessoa física ou jurídica",
                'tp_contato.required' => "Informe a categoria do contato",
                'rz_social.required' => "Informe a razão social",

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

                Contato::create([
                    'empresa_id'            => $empresa_id,
                    'tp_contato'            => ucfirst(tirarAcentos($request['tp_contato'])),
                    'nm_fantasia'           => ucfirst(tirarAcentos($request['nm_fantasia'])),
                    'rz_social'             => ucfirst(tirarAcentos($request['rz_social'])),
                    'tp_pessoa'             => ucfirst(tirarAcentos($request['tp_pessoa'])),
                    'cpf_cnpj'              => $request['cpf_cnpj'],
                    'ds_insc_estadual'      => $request['ds_insc_estadual'],
                    'ds_email'              => strtolower(tirarAcentos($request['ds_email'])),
                    'ds_telefone'           => $request['ds_telefone'],
                    'ds_celular'            => $request['ds_celular'],
                    'nm_contato_emp'        => ucfirst(tirarAcentos($request['nm_contato_emp'])),
                    'ds_endereco'           => ucfirst(tirarAcentos($request['ds_endereco'])),
                    'nr_endereco'           => $request['nr_endereco'],
                    'ds_complemento'        => ucfirst(tirarAcentos($request['ds_complemento'])),
                    'ds_bairro'             => ucfirst(tirarAcentos($request['ds_bairro'])),
                    'ds_cep'                => $request['ds_cep'],
                    'estado_id'             => $request['estado_id'],
                    'cidade_id'             => $request['cidade_id'],
                    'ds_descricao'          => $request['ds_descricao'],
                ]);

                // regista a ação de login do usuário na tabela de logs
                /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
                Log::create([
                    'empresa_id' => $empresa_id,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "C",
                    'ds_mensagem' => "Contato: ". ucfirst(tirarAcentos($request['rz_social']))
                ]);

                DB::commit();
                $href = route('contatos.index');

                return Response::json([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Contato criado",
                    'href'      => $href,
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
        $contato = Contato::find($id);

        if( !$contato ){
            return Response::json([
                'titulo'    => "Atenção!!!",
                'tipo'      => "warning",
                'message'   => "Contato não encontrado no banco de dados",
                'erro'      => 'erro'
            ]);
        }

        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'tp_pessoa'  => 'required|string|max:1',
                'tp_contato'  => 'required|string|max:1',
                'rz_social'  => 'required|string|max:150',
            ], [
                'tp_pessoa.required' => "Informe se é pessoa física ou jurídica",
                'tp_contato.required' => "Informe a categoria do contato",
                'rz_social.required' => "Informe a razão social",

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

                $contato->update([
                    'tp_contato'            => ucfirst(tirarAcentos($request['tp_contato'])),
                    'nm_fantasia'           => ucfirst(tirarAcentos($request['nm_fantasia'])),
                    'rz_social'             => ucfirst(tirarAcentos($request['rz_social'])),
                    'tp_pessoa'             => ucfirst(tirarAcentos($request['tp_pessoa'])),
                    'cpf_cnpj'              => $request['cpf_cnpj'],
                    'ds_insc_estadual'      => $request['ds_insc_estadual'],
                    'ds_email'              => strtolower(tirarAcentos($request['ds_email'])),
                    'ds_telefone'           => $request['ds_telefone'],
                    'ds_celular'            => $request['ds_celular'],
                    'nm_contato_emp'        => ucfirst(tirarAcentos($request['nm_contato_emp'])),
                    'ds_endereco'           => ucfirst(tirarAcentos($request['ds_endereco'])),
                    'nr_endereco'           => $request['nr_endereco'],
                    'ds_complemento'        => ucfirst(tirarAcentos($request['ds_complemento'])),
                    'ds_bairro'             => ucfirst(tirarAcentos($request['ds_bairro'])),
                    'ds_cep'                => $request['ds_cep'],
                    'estado_id'             => $request['estado_id'],
                    'cidade_id'             => $request['cidade_id'],
                    'ds_descricao'          => $request['ds_descricao'],
                ]);

                // regista a ação de login do usuário na tabela de logs
                /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
                Log::create([
                    'empresa_id' => $empresa_id,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "A",
                    'ds_mensagem' => "Contato: ". ucfirst(tirarAcentos($request['rz_social']))
                ]);

                DB::commit();
                $href = route('contatos.index');

                return Response::json([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Contato atualizado",
                    'href'      => $href,
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

    public function modalDelete(Request $request)
    {
        $id = $request['id'];

        return view('cadastros.contatos.modal-delete',
            compact('id')
        );
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try{
            $contato = Contato::find($id);

            if( !$contato  ){
                return redirect()->back()->with([
                    'titulo'    => "Falhou !!!",
                    'tipo'      => "error",
                    'message'   => "Contato não localizada no banco de dados",
                ]);
            }

            $id_empresa = getIdEmpresa();
            // regista a ação de login do usuário na tabela de logs
            /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
            Log::create([
                'empresa_id' => $id_empresa,
                'usuario_id'    => Auth::user()->id,
                'ds_acao'   => "E",
                'ds_mensagem' => "Contato: ". ucfirst($contato->nome)
            ]);

            $contato->delete();

            DB::commit();

            return redirect()->back()->with([
                'titulo'    => "Sucesso!!!",
                'tipo'      => "success",
                'message'   => "Contato removida",
            ]);
        } catch (QueryException $e) {
            DB::rollback();

            return redirect()->back()->with([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => $e->getMessage()
            ]);
        }
    }
}
