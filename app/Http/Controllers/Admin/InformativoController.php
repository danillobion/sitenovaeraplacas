<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informativo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class InformativoController extends Controller
{
    public function index()
    {
        $informativo = Informativo::first(); // apenas um registro
        return Inertia::render('Admin/Informativo/Index', [
            'informativo' => $informativo,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'habilitado' => 'boolean',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $informativo = Informativo::firstOrNew(['id' => 1]);

        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('informativos', 'public');
            $data['imagem'] = $path;
        }

        $informativo->fill($data);
        $informativo->save();

        return back()->with('success', 'Informativo atualizado com sucesso!');
    }

    // Endpoint público (para o Welcome)
    public function ativo()
    {
        $informativo = Informativo::where('habilitado', true)->first();
        return response()->json($informativo);
    }
}
