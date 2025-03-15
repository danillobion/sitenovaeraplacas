<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Inertia\Inertia;
use App\Models\Permissao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TiposController extends Controller
{
    public function index(){
        $tipos = Tipo::get();
        return Inertia::render('Admin/Tipos/index', [
            'tipos' => $tipos,
        ]);
    }

    public function editar($id = null){
        $tipo = !is_null($id) ? Tipo::with('permissoes')->find($id) : new Tipo();
        return Inertia::render('Admin/Tipos/edit', [
            'tipo' => $tipo
        ]);
    }

    public function salvar(Request $request){
        $tipos = !is_null($request->id) ? Tipo::find($request->id) : new Tipo();
        $tipos->nome = $request->nome;
        $tipos->descricao = $request->descricao;
        $tipos->save();
        
        return Redirect::route('tipos.index')->with('success', 'Tipo salvo com sucesso!');
    }
}
