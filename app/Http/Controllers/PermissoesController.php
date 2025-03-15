<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Permissao;
use App\Models\TipoPermissao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PermissoesController extends Controller
{
    public function editar($tipo_id = null){
        $permissoes = Permissao::get();
        foreach($permissoes as $permissao){
            $tipo_permissao = TipoPermissao ::where('tipo_id', $tipo_id)->where('permissao_id', $permissao->id)->exists();
            if($tipo_permissao){
                $permissao->vinculado = true;
            }else{
                $permissao->vinculado = false;
            }
        };
        return Inertia::render('Admin/Permissoes/edit', [
            'tipo_id' => $tipo_id,
            'permissoes' => $permissoes
        ]);
    }

    public function salvar(Request $request){
        $tipo_permissao = TipoPermissao::where('tipo_id', $request->tipo_id)->where('permissao_id', $request->permissao_id)->first();
        if(!is_null($tipo_permissao)){
            $tipo_permissao->delete();
        }else{
            $tipo_permissao = new TipoPermissao();
            $tipo_permissao->tipo_id = $request->tipo_id;
            $tipo_permissao->permissao_id = $request->permissao_id;
            $tipo_permissao->save();
        }
        return Redirect::route('permissao.editar', $request->tipo_id)->with('success', 'Permissão vinculada ao tipo com sucesso!');
    }
}