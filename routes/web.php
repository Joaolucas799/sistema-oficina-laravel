<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\EntradaEstoqueController;
use App\Http\Controllers\SaidaEstoqueController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\OsItemController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\ContaPagarController;
use App\Http\Controllers\FiadoController;

Route::middleware(['auth'])->group(function () {

    Route::resource('categorias', CategoriaController::class);

    Route::resource('fornecedores', FornecedorController::class)
        ->parameters(['fornecedores' => 'fornecedor']);

    Route::resource('produtos', ProdutoController::class);

    Route::get('/entradas', [EntradaEstoqueController::class, 'index'])->name('entradas.index');
    Route::get('/entradas/novo', [EntradaEstoqueController::class, 'create'])->name('entradas.create');
    Route::post('/entradas', [EntradaEstoqueController::class, 'store'])->name('entradas.store');

    Route::get('/saidas', [SaidaEstoqueController::class, 'index'])->name('saidas.index');
    Route::get('/saidas/novo', [SaidaEstoqueController::class, 'create'])->name('saidas.create');
    Route::post('/saidas', [SaidaEstoqueController::class, 'store'])->name('saidas.store');

    Route::resource('clientes', ClienteController::class);

    Route::post('/clientes/{cliente}/veiculos', [VeiculoController::class, 'store'])->name('veiculos.store');
    Route::delete('/veiculos/{veiculo}', [VeiculoController::class, 'destroy'])->name('veiculos.destroy');

    Route::resource('servicos', ServicoController::class);

    Route::get('/os/novo', [OrdemServicoController::class, 'create'])->name('os.create');

    Route::resource('os', OrdemServicoController::class)
        ->except(['create', 'edit'])
        ->parameters(['os' => 'os']);

    Route::get('/os/{os}/imprimir', [OrdemServicoController::class, 'imprimir'])->name('os.imprimir');

    Route::post('/os/{os}/itens', [OsItemController::class, 'store'])->name('os.itens.store');
    Route::delete('/os-itens/{item}', [OsItemController::class, 'destroy'])->name('os.itens.destroy');

    Route::get('/relatorios/faturamento', [RelatorioController::class, 'faturamento'])->name('relatorios.faturamento');

    Route::resource('contas-pagar', ContaPagarController::class)
        ->except(['show'])
        ->parameters(['contas-pagar' => 'contasPagar']);

    Route::post('/contas-pagar/{contasPagar}/pagar', [ContaPagarController::class, 'pagar'])->name('contas-pagar.pagar');

    Route::get('/fiado', [FiadoController::class, 'index'])->name('fiado.index');
    Route::post('/fiado', [FiadoController::class, 'store'])->name('fiado.store');
    Route::get('/fiado/{fiado}', [FiadoController::class, 'show'])->name('fiado.show');
    Route::post('/fiado/{fiado}/pagar', [FiadoController::class, 'pagar'])->name('fiado.pagar');
    Route::delete('/fiado/{fiado}', [FiadoController::class, 'destroy'])->name('fiado.destroy');
    Route::get('/fiado/os/{os}/json', [FiadoController::class, 'osJson'])->name('fiado.os.json');
    Route::get('/fiado/cliente/{cliente}', [FiadoController::class, 'porCliente'])->name('fiado.cliente');

});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';