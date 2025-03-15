<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Inertia\Inertia;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UsuariosController extends Controller
{
    public function index(){
        $usuarios = Usuario::with('tipo')->get();
        return Inertia::render('Admin/Usuarios/index', [
            'usuarios' => $usuarios,
        ]);
    }

    public function editar($id = null){
        $usuario = !is_null($id) ? Usuario::with('tipo')->find($id) : new Usuario();
        $tipos = Tipo::get();
        return Inertia::render('Admin/Usuarios/edit', [
            'usuario' => $usuario,
            'tipos' => $tipos
        ]);
    }

    public function salvar(Request $request){
        $usuario = !is_null($request->id) ? Usuario::find($request->id) : new Usuario();
        $usuario->nome = $request->nome;
        $usuario->email = $request->email;
        $usuario->tipo_id = $request->tipo_id;  
        if(is_null($request->id)){
            $usuario->password = Hash::make('NovaEraPlacas@123');  
        }
        $usuario->save();
        return Redirect::route('usuarios.index')->with('success', 'Usuário salvo com sucesso!');
    }
}
