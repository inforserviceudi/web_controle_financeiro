<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Empresa;
use App\Models\Estado;
use App\Models\ValidadorCpfCnpj;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class EmpresasController extends Controller
{
    public function index()
    {
        $empresas = Empresa::orderBy("empresa_principal", "DESC")->get();
        $emp_principal = Empresa::where("empresa_principal", "S")->first();
        $estados = Estado::select('id', 'nm_estado', 'ds_sigla')->orderBy('nm_estado', 'ASC')->get();
        $cidades = Cidade::select('id', 'nm_cidade', 'estado_id')->orderBy('id', "ASC")->get();
        $ds_cpf_cnpj = "";

        if( !$emp_principal ){
            $emp_principal = null;
        }else{
            if( strlen($emp_principal->ds_cpf_cnpj) == 14 ){
                $ds_cpf_cnpj = "cpf";
            }elseif( strlen($emp_principal->ds_cpf_cnpj) == 18 ){
                $ds_cpf_cnpj = "cnpj";
            }
        }

        return view('cadastros.empresas.index',
            compact('empresas', 'emp_principal', 'estados', 'cidades', 'ds_cpf_cnpj')
        );
    }

    public function create()
    {
        $estados = Estado::select('id', 'nm_estado', 'ds_sigla')->orderBy('nm_estado', 'ASC')->get();
        $cidades = Cidade::select('id', 'nm_cidade', 'estado_id')->orderBy('id', "ASC")->get();

        return view('cadastros.empresas.create',
            compact('estados', 'cidades')
        );
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'nm_empresa'  => 'required|string|max:150',
                'ds_cpf_cnpj'  => 'nullable|string|min: 14',
            ], [
                'nm_empresa.required' => "Informe o nome da empresa",

                "nm_empresa.string"    => "A descrição deve conter letras e números",
                "nm_empresa.max"       => "Informe no máximo :max caracteres",
                "min"                   => "Informe no mínimo :min caracteres no campo :attribute",
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'titulo'    => "Atenção!!!",
                    'tipo'      => "warning",
                    'message'   => $validator->errors()->all(),
                    'erro'      => 'erro'
                ]);
            } else {
                $cont_empresas = Empresa::count();

                if( $cont_empresas == 0 ){
                    $empresa_principal = "S";
                }else{
                    $empresa = Empresa::where("empresa_principal", "S")->first();

                    if( $empresa ){
                        $empresa_principal = "N";
                    }else{
                        $empresa1 = Empresa::where("empresa_principal", "N")->orderBy('id', 'asc')->first();
                        $empresa1->update([
                            "empresa_principal" => "S",
                        ]);
                        $empresa_principal = "N";
                    }
                }

                if( $request['ds_cpf_cnpj'] !== null ){
                    $cpf_cnpj = new ValidadorCpfCnpj( $request['ds_cpf_cnpj'] );

                    // Verifica se o CPF ou CNPJ é válido
                    if ( !$cpf_cnpj->valida() ) {
                        return Response::json([
                            'titulo'    => "Atenção!!!",
                            'tipo'      => "warning",
                            'message'   => 'CPF ou CNPJ inválido',
                            'erro'      => 'erro'
                        ]);
                    }
                }else{
                    $cpf_cnpj = null;
                }

                $file = $request['arquivo'];

                if( isset($file) ){
                    $extensao = $file->getClientOriginalExtension();  //Resgata a extensão do arquivo.
                    $fileName = strtolower($request['nm_empresa']);
                    $fileName = str_replace(" ", "-", $fileName);
                    $fileName = $fileName. '-' . time() . '.' . $extensao;

                    $diretorio = public_path() .'/images/empresas';
                    $permitido = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");  //Extesões válidas.

                    if (!in_array($extensao, $permitido)) {//Verifica se tem as extesões corretas
                        return Response::json([
                            'titulo'    => 'Erro!!!',
                            'message'   => "Tipos de arquivo permitido: .jpg, .jpeg, .png",
                            'tipo'      => 'danger',
                            'erro'      => 'erro'
                        ]);
                    } else {
                        if($file->move($diretorio, $fileName)) {
                            //Sucesso no upload
                            Empresa::create([
                                'empresa_principal' => $empresa_principal,
                                'nm_empresa'        => strtoupper(tirarAcentos($request['nm_empresa'])),
                                'ds_cpf_cnpj'       => $request['ds_cpf_cnpj'],
                                'estado_id'         => $request['estado_id'],
                                'cidade_id'         => $request['cidade_id'],
                                'vr_saldo_inicial'  => formatValue($request['vr_saldo_inicial']),
                                'tp_saldo_inicial'  => strtoupper(tirarAcentos($request['tp_saldo_inicial'])),
                                'qt_funcionarios'   => $request['qt_funcionarios'],
                                'ds_logomarca'      => $fileName,
                            ]);
                        } else {
                            //Erro ao fazer upload
                            return Response::json([
                                'titulo'    => 'Erro!!!',
                                'message'   => "Erro no upload da imagem do participante",
                                'tipo'      => 'danger',
                                'erro'      => 'erro'
                            ]);
                        }
                    }
                }else{
                    Empresa::create([
                        'empresa_principal' => $empresa_principal,
                        'nm_empresa'        => strtoupper(tirarAcentos($request['nm_empresa'])),
                        'ds_cpf_cnpj'       => $request['ds_cpf_cnpj'],
                        'estado_id'         => $request['estado_id'],
                        'cidade_id'         => $request['cidade_id'],
                        'vr_saldo_inicial'  => formatValue($request['vr_saldo_inicial']),
                        'tp_saldo_inicial'  => strtoupper(tirarAcentos($request['tp_saldo_inicial'])),
                        'qt_funcionarios'   => $request['qt_funcionarios'],
                        'ds_logomarca'      => null,
                    ]);
                }

                $href = route('empresas.index');

                DB::commit();

                return Response::json([
                    'titulo'    => "Sucesso!!!",
                    'tipo'      => "success",
                    'message'   => "Empresa criada",
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
}
