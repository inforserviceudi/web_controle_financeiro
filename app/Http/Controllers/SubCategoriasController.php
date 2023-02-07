<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Log;
use App\Models\SubCategoria;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class SubCategoriasController extends Controller
{
    public function index(Request $request)
    {
        $id_empresa = getIdEmpresa();
        $categorias = Categoria::select('id', 'nome')->get();

        if( count($request->all()) > 0 ){
            $categ_id = $request['categoria_id'];
            $subcategorias = SubCategoria::select('id', 'nome')
            ->where('empresa_id', $id_empresa)
            ->where('categoria_id', $categ_id)
            ->orderBy('id', 'DESC')
            ->get();
        }else{
            $categ = Categoria::select('id')->orderBy('id', 'ASC')->first();
            $categ_id = $categ->id;
            $subcategorias = SubCategoria::select('id', 'nome')
            ->where('empresa_id', $id_empresa)
            ->where('categoria_id', $categ_id)
            ->orderBy('id', 'DESC')
            ->get();
        }

        return view('cadastros.subcategorias.index',
            compact('categorias', 'subcategorias', 'categ_id')
        );
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'nm_subcategoria'  => 'required|string|max:150',
            ], [
                'nm_subcategoria.required' => "Informe o nome da categoria",
                "nm_subcategoria.max"       => "Informe no máximo :max caracteres",
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => $validator->errors()->all(),
                    'erro'      => 'erro'
                ]);
            } else {
                $id_empresa = getIdEmpresa();

                SubCategoria::create([
                    'empresa_id'    => $id_empresa,
                    'categoria_id'  => $request['categoria_id'],
                    'nome'  => ucfirst(tirarAcentos($request['nm_subcategoria']))
                ]);

                // regista a ação de login do usuário na tabela de logs
                Log::create([
                    'empresa_id' => $id_empresa,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "C",
                    'ds_mensagem' => "Categoria: ". ucfirst(tirarAcentos($request['nm_subcategoria']))
                ]);

                DB::commit();

                return redirect()->back()->with([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Categoria criada",
                ]);
            }
        } catch (QueryException $e) {
            DB::rollback();

            return redirect()->back()->with([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'nome_subcategoria'  => 'required|string|max:150',
            ], [
                'nome_subcategoria.required' => "Informe o nome da categoria",
                "nome_subcategoria.max"       => "Informe no máximo :max caracteres",
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => $validator->errors()->all(),
                    'erro'      => 'erro'
                ]);
            } else {
                $id_empresa = getIdEmpresa();

                $subcategoria = SubCategoria::find($id);

                if( !$subcategoria  ){
                    return redirect()->back()->with([
                        'titulo'    => "Falhou !!!",
                        'tipo'      => "error",
                        'message'   => "Categoria não localizada no banco de dados",
                    ]);
                }

                $subcategoria->update([
                    'nome'  => ucfirst(tirarAcentos($request['nome_subcategoria']))
                ]);

                // regista a ação de login do usuário na tabela de logs
                /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
                Log::create([
                    'empresa_id' => $id_empresa,
                    'usuario_id'    => Auth::user()->id,
                    'ds_acao'   => "A",
                    'ds_mensagem' => "Categoria: ". ucfirst(tirarAcentos($request['nome_subcategoria']))
                ]);

                DB::commit();

                return redirect()->back()->with([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Categoria atualizada",
                ]);
            }
        } catch (QueryException $e) {
            DB::rollback();

            return redirect()->back()->with([
                'titulo'    => 'Falhou!!!',
                'tipo'      => "error",
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function modalDelete(Request $request)
    {
        $id = $request['id'];

        return view('cadastros.subcategorias.modal-delete',
            compact('id')
        );
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try{
            $subcategoria = SubCategoria::find($id);

            if( !$subcategoria  ){
                return redirect()->back()->with([
                    'titulo'    => "Falhou !!!",
                    'tipo'      => "error",
                    'message'   => "Categoria não localizada no banco de dados",
                ]);
            }

            $id_empresa = getIdEmpresa();
            // regista a ação de login do usuário na tabela de logs
            /// A - ALTEROU // C - CRIOU // E - EXCLUIU // L - LOGIN
            Log::create([
                'empresa_id' => $id_empresa,
                'usuario_id'    => Auth::user()->id,
                'ds_acao'   => "E",
                'ds_mensagem' => "Categoria: ". ucfirst($subcategoria->nome)
            ]);

            $subcategoria->delete();

            DB::commit();

            return redirect()->back()->with([
                'titulo'    => "Sucesso!!!",
                'tipo'      => "success",
                'message'   => "Categoria removida",
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
