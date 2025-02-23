<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estampadora;
use App\Models\Produto;
use Inertia\Inertia;

class ApresentacaoController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'estampadoras' => Estampadora::with('endereco')->get(),
            'produtos' => Produto::get(),
        ]);
    }
}
