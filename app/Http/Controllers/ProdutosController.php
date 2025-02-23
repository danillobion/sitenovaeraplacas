<?php

namespace App\Http\Controllers;

use App\Models\Estampadora;
use Inertia\Inertia;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProdutosController extends Controller
{
    public function index(){
        $produtos = Produto::get();
        return Inertia::render('Admin/Produtos/index', [
            'produtos' => $produtos,
        ]);
    }

    public function editar($id = null){
        $produto = !is_null($id) ? Produto::find($id) : new Produto();
        return Inertia::render('Admin/Produtos/edit', [
            'produto' => $produto
        ]);
    }   

    public function salvar(Request $request){
        $produto = is_null($request->id) ? new Produto() : Produto::find($request->id);
        $produto->nome = $request->nome;
        $produto->caminho_imagem = "";
        $produto->descricao = $request->descricao;
        $produto->valor = $request->valor;
        $produto->save();
        return Redirect::route('produtos.index')->with('success', 'Produto salvo com sucesso!');
    }

    public function deletar(Request $request){
        $request->validate([
            'produto_id' => 'required|exists:produtos,id'
        ]);
        $produto = Produto::findOrFail($request->produto_id);
        $produto->delete();
        return Redirect::route('produtos.index')
            ->with('success', 'Produto deletado com sucesso!');
    }
}
