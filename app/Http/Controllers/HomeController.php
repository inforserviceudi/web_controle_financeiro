<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $id_empresa = getIdEmpresa();
        $contas = Conta::where("empresa_id", $id_empresa)->get();

        return view('layouts.dashboard',
            compact('contas')
        );
    }
}
