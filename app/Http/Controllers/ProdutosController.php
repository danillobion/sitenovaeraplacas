<?php

namespace App\Http\Controllers;

use App\Models\Estampadora;
use Inertia\Inertia;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;


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

    public function salvar(Request $request)
    {
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
            $request->validate([
                'imagem' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }

        $produto = $request->id ? Produto::find($request->id) : new Produto();
    
        $produto->nome = $request->nome;
        $produto->descricao = $request->descricao;
        $produto->valor = $request->valor;
        $produto->exibir_valor = $request->exibir_valor == 'true' ? true : false;
    
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
    
            if ($produto->imagem) {
                $caminhoAnterior = public_path($produto->imagem);
                if (file_exists($caminhoAnterior)) {
                    unlink($caminhoAnterior);
                }
            }
    
            $extensao = $request->file('imagem')->getClientOriginalExtension();
            $nomeArquivo = 'produto_' . uniqid() . '.' . $extensao;
            $caminho = $request->file('imagem')->storeAs('produtos', $nomeArquivo, 'public');
    
            if ($caminho) {
                $produto->imagem = 'storage/' . $caminho;
            } else {
                return back()->withErrors(['imagem' => 'Falha ao salvar a imagem no servidor.']);
            }
        }
    
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
