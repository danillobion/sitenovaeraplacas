<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Informativo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InformativoController extends Controller
{
    public function index()
    {
        $informativo = Informativo::first();

        return Inertia::render('Admin/Informativo/Index', [
            'informativo' => $informativo,
        ]);
    }

    public function editar($id = null)
    {
        // seu sistema padrão usa id?, mas aqui é registro único
        $informativo = Informativo::first();

        return Inertia::render('Admin/Informativo/Editar', [
            'informativo' => $informativo,
        ]);
    }

    public function salvar(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'habilitado' => 'boolean',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // como é um módulo de registro único
        $informativo = Informativo::firstOrNew(['id' => 1]);

        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('informativos', 'public');
            $data['imagem'] = $path;
        }

        $informativo->fill($data)->save();

        return redirect()
            ->route('informativos.index')
            ->with('success', 'Informativo salvo com sucesso!');
    }

    // Endpoint público
    public function ativo()
    {
        $informativo = Informativo::where('habilitado', true)->first();
        return response()->json($informativo);
    }
}
