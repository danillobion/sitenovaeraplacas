<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Estampadora;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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
        $estampadora = is_null($request->id) ? new Estampadora() : Estampadora::find($request->id);
        $endereco = is_null($estampadora->endereco_id) ? new Endereco() : Endereco::find($estampadora->endereco_id);

        $endereco->fill($request->all());
        $endereco->save();

        $estampadora->fill($request->all());
        $estampadora->endereco_id = $endereco->id;
        $estampadora->save();

        return Redirect::route('estampadora.index')->with('success', 'Estampadora salva com sucesso!');
    }

    public function deletar(Request $request)
    {
        $estampadora = Estampadora::find($request->id);
        if (!$estampadora) {
            return response()->json(['error' => 'Estampadora não encontrada'], 404);
        }

        $estampadora->delete();

        return Redirect::route('estampadora.index')->with('success', 'Estampadora deletada com sucesso!');
    }
}
