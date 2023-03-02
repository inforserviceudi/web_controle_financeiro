<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.login');
});

Route::get('/registro', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');

    Route::prefix('cadastros')->group(function(){
        Route::prefix('cidades')->name('cidades.')->group(function(){
            Route::get('/', 'CidadesController@index')->name('index');
            Route::post('/novo-registro', 'CidadesController@store')->name('insere.registro');
            Route::post('/atualiza-registro/{id}', 'CidadesController@update')->name('atualiza.registro');
            Route::post('/remove-registro/{id}', 'CidadesController@delete')->name('remove.registro');
        });
    });

    Route::prefix('categorias')->name('subcategorias.')->group(function(){
        Route::get('/', 'SubCategoriasController@index')->name('index');
        Route::post('/novo-registro', 'SubCategoriasController@store')->name('insere.registro');
        Route::post('/atualiza-registro/{id}', 'SubCategoriasController@update')->name('atualiza.registro');
        Route::post('/modal-delete', 'SubCategoriasController@modalDelete')->name('modal.delete');
        Route::get('/remove-registro/{id}', 'SubCategoriasController@delete')->name('remove.registro');
    });

    Route::prefix('contatos')->name('contatos.')->group(function(){
        Route::get('/', 'ContatosController@index')->name('index');
        Route::get('/lista',    'ContatosController@dataTable')->name('dataTable');
        Route::post('/novo-registro', 'ContatosController@store')->name('insere.registro');
        Route::post('/atualiza-registro/{id}', 'ContatosController@update')->name('atualiza.registro');
        Route::post('/modal-create-edit', 'ContatosController@modalCreateEdit')->name('modal.create-edit');
        Route::post('/modal-delete', 'ContatosController@modalDelete')->name('modal.delete');
        Route::get('/remove-registro/{id}', 'ContatosController@delete')->name('remove.registro');
    });

    Route::prefix('financas')->group(function(){
        Route::prefix('contas')->name('contas.')->group(function(){
            Route::get('/', 'ContasController@index')->name('index');
            Route::post('/novo-registro', 'ContasController@store')->name('insere.registro');
            Route::post('/atualiza-registro/{id}', 'ContasController@update')->name('atualiza.registro');
            Route::post('/remove-registro/{id}', 'ContasController@delete')->name('remove.registro');
        });

        Route::prefix('tipos-contas')->name('tipos-contas.')->group(function(){
            Route::get('/', 'TiposContasController@index')->name('index');
            Route::post('/novo-registro', 'TiposContasController@store')->name('insere.registro');
            Route::post('/atualiza-registro/{id}', 'TiposContasController@update')->name('atualiza.registro');
            Route::post('/remove-registro/{id}', 'TiposContasController@delete')->name('remove.registro');
        });
    });

    Route::prefix('empresas')->name('empresas.')->group(function(){
        Route::get('/', 'EmpresasController@index')->name('index');
        Route::post('/', 'EmpresasController@empresaSistema')->name('sistema.geral');
        Route::get('/novo-registro', 'EmpresasController@create')->name('criar.registro');
        Route::post('/insere-registro', 'EmpresasController@store')->name('insere.registro');
        Route::post('/atualiza-registro/{id}', 'EmpresasController@update')->name('atualiza.registro');
        Route::get('/remove-registro/{id}', 'EmpresasController@delete')->name('remove.registro');
        Route::get('/principal/{id}', 'EmpresasController@empresaPrincipal')->name('principal');
        Route::get('/{id}', 'EmpresasController@visaulizarEmpresaSelecionada')->name('visualizar.selecionado');
    });

    Route::prefix('usuarios')->name('usuarios.')->group(function(){
        Route::get('/{empresa_id}', 'UsuariosController@index')->name('index');
        Route::get('/{empresa_id}/lista',    'UsuariosController@dataTable')->name('dataTable');
        Route::post('/{empresa_id}/novo-registro', 'UsuariosController@store')->name('insere.registro');
        Route::post('/atualiza-registro/{id}', 'UsuariosController@update')->name('atualiza.registro');
        Route::post('/remove-registro/{id}', 'UsuariosController@delete')->name('remove.registro');
        Route::post('/modal-logs', 'UsuariosController@modalLogs')->name('modal.logs');
        Route::get('/{usuario_id}/permissoes', 'UsuariosController@permissoes')->name('permissoes');
        Route::post('/{usuario_id}/atualiza-permissoes', 'UsuariosController@updatePermissoes')->name('atualiza.permissoes');
    });

    Route::prefix('transacoes')->name('transacoes.')->group(function(){
        Route::get('/', 'TransacoesController@index')->name('index');
        Route::post('/', 'TransacoesController@selecionaConta')->name('seleciona.conta');
        Route::post('/novo-registro', 'TransacoesController@store')->name('insere.registro');
        Route::post('/atualiza-registro/{id}', 'TransacoesController@update')->name('atualiza.registro');
        Route::post('/modal-create-edit', 'TransacoesController@modalCreateEdit')->name('modal.create-edit');
        Route::any('/modal-parcelas', 'TransacoesController@modalParcelas')->name('modal.parcelas');
        Route::any('/modal-transferencias', 'TransacoesController@modalTransferencias')->name('modal.transferencias');
        Route::post('/modal-delete', 'TransacoesController@modalDelete')->name('modal.delete');
        Route::post('/atualiza-parcelas', 'TransacoesController@atualizaParcelas')->name('atualiza.parcelas');
        Route::post('/informar-pagamento', 'TransacoesController@informarPagamento')->name('informar.pagamento');
        Route::get('/remove-registro/{id}', 'TransacoesController@delete')->name('remove.registro');
        Route::post('/ajax-transacao-parcelamento', 'TransacoesController@ajaxParcelamento')->name('ajax.parcelamento');
        Route::post('/delete-parcelas', 'TransacoesController@deleteParcelas')->name('excluir.parcelas');
        Route::post('/gera-transferencia', 'TransacoesController@geraTransferencia')->name('gera.transferencia');
        Route::post('/duplica-transferencia/{id}', 'TransacoesController@duplicaTransferencia')->name('duplica.transferencia');
    });
});
