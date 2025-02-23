<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Estampadora;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class EstampadorasController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Estampadora/index', [
            'estampadoras' => Estampadora::with('endereco')->get(),
        ]);
    }

    public function findAll()
    {
        return response()->json(Estampadora::with('endereco')->get());
    }

    public function find($estampadora_id = null)
    {
        $estampadora = $estampadora_id ? Estampadora::with('endereco')->find($estampadora_id) : new Estampadora();
        return response()->json([
            'estampadora' => $estampadora,
            'endereco' => $estampadora->endereco ?? new Endereco(),
        ]);
    }

    public function editar($id = null)
    {
        $estampadora = !is_null($id) ? Estampadora::find($id) : new Estampadora();
        return Inertia::render('Admin/Estampadora/edit', [
            'estampadora' => $estampadora,
            'endereco' => !is_null($estampadora->endereco_id) ? Endereco::find($estampadora->endereco_id) : new Endereco(),
        ]);
    }

    public function salvar(Request $request)
    {
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
            $request->validate([
                'imagem' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }
    
        $estampadora = is_null($request->id) || $request->id == 'undefined' ? new Estampadora() : Estampadora::find($request->id);
        $endereco = is_null($request->id) || $request->id == 'undefined' ? new Endereco() : Endereco::find($estampadora->endereco_id);
    
        $endereco->fill($request->all());
        $endereco->cep = str_replace(['.', '-'], '', $endereco->cep);
        $endereco->save();
    
        $estampadora->fill($request->all());
        $estampadora->cnpj = str_replace(['.', '-', '/'], '', $estampadora->cnpj);
        $estampadora->endereco_id = $endereco->id;
        $estampadora->save();
    
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
    
            if ($estampadora->imagem) {
                $caminhoAnterior = public_path($estampadora->imagem);
                if (file_exists($caminhoAnterior)) {
                    unlink($caminhoAnterior);
                }
            }
    
            $extensao = $request->file('imagem')->getClientOriginalExtension();
            $nomeArquivo = 'estampadora_' . $estampadora->id . '.' . $extensao;
    
            $caminho = $request->file('imagem')->storeAs('estampadoras', $nomeArquivo, 'public');   

            if ($caminho) {
                $estampadora->imagem = 'storage/estampadoras/' . $nomeArquivo;
                $estampadora->save();
            } else {
                return back()->withErrors(['imagem' => 'Falha ao salvar a imagem no servidor.']);
            }
        }
    
        // Retorna para a lista de estampadoras com uma mensagem de sucesso
        return Redirect::route('estampadora.index')->with('success', 'Estampadora salva com sucesso!');
    }
    


    public function deletar(Request $request)
    {
        $request->validate([
            'estampadora_id' => 'required'
        ]);
        $estampadora = Estampadora::find($request->estampadora_id);
        if (!$estampadora) {
            return response()->json(['error' => 'Estampadora não encontrada'], 404);
        }
        $estampadora->delete();
        return Redirect::route('estampadora.index')->with('success', 'Estampadora deletada com sucesso!');
    }
}
