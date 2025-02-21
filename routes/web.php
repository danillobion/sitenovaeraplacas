<?php

use Inertia\Inertia;
use App\Models\Produto;
use App\Models\Endereco;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\EstampadorasController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {

    Route::get('/cep/{numero}', [EnderecoController::class, 'cep'])->name('endereco.cep');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/estampadoras', [EstampadorasController::class, 'index'])->name('estampadora.index');
    Route::get('/estampadoras/edit/{id?}', [EstampadorasController::class, 'editar'])->name('estampadora.editar');
    Route::post('/estampadoras/salvar', [EstampadorasController::class, 'salvar'])->name('estampadora.salvar');
    Route::delete('/estampadoras/deletar', [EstampadorasController::class, 'deletar'])->name('estampadora.deletar');

    Route::get('/estampadoras/produtos/{estampadora_id}', [ProdutosController::class, 'index'])->name('estampadora.produtos.index');
    Route::get('/estampadoras/produtos/editar/{estampadora_id?}/{id?}', [ProdutosController::class, 'editar'])->name('estampadora.produtos.editar');
    Route::post('/estampadoras/produtos/salvar', [ProdutosController::class, 'salvar'])->name('estampadora.produtos.salvar');
    Route::delete('/estampadoras/produtos/deletar', [ProdutosController::class, 'deletar'])->name('estampadora.produtos.deletar');

});

require __DIR__.'/auth.php';
