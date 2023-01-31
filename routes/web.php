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
});

