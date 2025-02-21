<?php

namespace App\Http\Controllers;

use App\Models\Estampadora;
use Inertia\Inertia;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProdutosController extends Controller
{
    public function index($estampadora_id = null){
        $produtos = Produto::where("estampadora_id",$estampadora_id)->get();
        $estampadora_nome = Estampadora::find($estampadora_id)->nome;
        return Inertia::render('Admin/Estampadora/Produtos/index', [
            'produtos' => $produtos,
            'estampadora_id' => $estampadora_id,
            'estampadora_nome' => $estampadora_nome
        ]);
    }

    public function editar($estampadora_id = null,$id = null){
        $produto = !is_null($id) ? Produto::find($id) : new Produto();
        return Inertia::render('Admin/Estampadora/Produtos/edit', [
            'produto' => $produto,
            'estampadora_id' => $estampadora_id,
        ]);
    }   

    public function salvar(Request $request){
        $produto = is_null($request->id) ? new Produto() : Produto::find($request->id);
        $produto->estampadora_id = $request->estampadora_id;
        $produto->nome = $request->nome;
        $produto->descricao = $request->descricao;
        $produto->valor = $request->valor;
        $produto->save();
        return Redirect::route('estampadora.produtos.index', ['estampadora_id' => $request->estampadora_id])->with('success', 'Produto salvo com sucesso!');
    }

    public function deletar(Request $request){
        $request->validate([
            'id' => 'required|exists:produtos,id',
            'estampadora_id' => 'required|exists:estampadoras,id'
        ]);
        $produto = Produto::findOrFail($request->id);
        $produto->delete();
        return Redirect::route('estampadora.produtos.index', ['estampadora_id' => $request->estampadora_id])
            ->with('success', 'Produto deletado com sucesso!');
    }
}
