<?php

use Inertia\Inertia;
use App\Models\Produto;
use App\Models\Endereco;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CepController;
use App\Http\Controllers\CnpjController;
use App\Http\Controllers\TiposController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissoesController;
use App\Http\Controllers\ApresentacaoController;
use App\Http\Controllers\EstampadorasController;

Route::get('/', [ApresentacaoController::class, 'index'])->name('apresentacao.index');

Route::get('/dashboard',[DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/cep/{numero}', [EnderecoController::class, 'cep'])->name('endereco.cep');
    Route::get('/cnpj/{numero}', [CnpjController::class,'cnpj'])->name('consultar.cnpj');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['auth', 'permission:tela-listagem-estampadoras'])->group(function () {
        Route::get('/estampadoras', [EstampadorasController::class, 'index'])->name('estampadora.index');
    });

    Route::middleware(['auth', 'permission:tela-alterar-estampadoras'])->group(function () {
        Route::get('/estampadoras/edit/{id?}', [EstampadorasController::class, 'editar'])->name('estampadora.editar');
        Route::post('/estampadoras/salvar', [EstampadorasController::class, 'salvar'])->name('estampadora.salvar');
        Route::delete('/estampadoras/deletar', [EstampadorasController::class, 'deletar'])->name('estampadora.deletar');
    });

    Route::middleware(['auth', 'permission:tela-listagem-produtos'])->group(function () {
        Route::get('/produtos', [ProdutosController::class, 'index'])->name('produtos.index');
    });

    Route::middleware(['auth', 'permission:tela-alterar-produtos'])->group(function () {
        Route::get('/produto/editar/{id?}', [ProdutosController::class, 'editar'])->name('produto.editar');
        Route::post('/produto/salvar', [ProdutosController::class, 'salvar'])->name('produto.salvar');
        Route::delete('/produto/deletar', [ProdutosController::class, 'deletar'])->name('produto.deletar');
    });
        
    Route::middleware(['auth', 'permission:tela-listagem-usuarios'])->group(function () {
        Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');
        Route::get('/usuario/editar/{id?}', [UsuariosController::class, 'editar'])->name('usuario.editar');
        Route::post('/usuario/salvar', [UsuariosController::class, 'salvar'])->name('usuario.salvar');
    });
    
    Route::middleware(['auth', 'permission:tela-tipos-usuarios'])->group(function () {
        Route::get('/tipos', [TiposController::class, 'index'])->name('tipos.index');
        Route::get('/tipo/editar/{id?}', [TiposController::class, 'editar'])->name('tipo.editar');
        Route::post('/tipo/salvar', [TiposController::class, 'salvar'])->name('tipo.salvar');

        Route::get('/permissao/editar/{tipo_id}', [PermissoesController::class, 'editar'])->name('permissao.editar');
        Route::post('/permissao/salvar', [PermissoesController::class, 'salvar'])->name('permissao.salvar');
    });
            
    Route::middleware(['auth', 'permission:tela-consultar-cep'])->group(function () {
        Route::get('/consultar/cep', [CepController::class, 'index'])->name('consultar.cep.index');
        Route::get('/consultar/cep/{numero}', [CepController::class, 'consultarCep'])->name('consultar.cep');
    });
            
    Route::middleware(['auth', 'permission:tela-consultar-cnpj'])->group(function () {
        Route::get('/consultar/cnpj', [CnpjController::class, 'index'])->name('consultar.cnpj.index');
        Route::get('/consultar/cnpj/{numero}', [CnpjController::class, 'consultarCnpj'])->name('tela.consultar.cnpj');
    });
});

require __DIR__.'/auth.php';
