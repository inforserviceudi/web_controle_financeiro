<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use App\Models\Log;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // pega o id da empresa que está marcada como selecionada
            $empresa = Empresa::select("id")->where("empresa_selecionada", "S")->first();
            
            // inicia uma sessão com o id da empresa selecionada
            session_start();
            session(['id_empresa' => $empresa->id]);

            // regista a ação de login do usuário na tabela de logs
            Log::create([
                'empresa_id' => $empresa->id, 
                'usuario_id'    => Auth::user()->id, 
                'ds_acao'   => "L", 
                'ds_mensagem' => "Usuário fez login no sistema"
            ]);

            return redirect('/dashboard');
        }

        return $next($request);
    }
}
