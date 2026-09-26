<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\OrdemServico;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index()
{
    $totalProdutos = Produto::count();

    $produtosEstoqueBaixo = Produto::whereColumn('estoque_atual', '<=', 'estoque_minimo')
        ->orderBy('estoque_atual')
        ->get();

    $osAbertas = OrdemServico::whereIn('status', ['aberta', 'em_andamento'])->count();

    // Faturamento de OS concluídas no mês
    $faturamentoOs = OrdemServico::where('status', 'concluida')
        ->whereMonth('data_conclusao', now()->month)
        ->whereYear('data_conclusao', now()->year)
        ->sum('valor_total');

    // Faturamento de vendas diretas de balcão no mês
    $faturamentoVendas = \App\Models\MovimentacaoEstoque::where('e_venda', true)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('valor_venda');

    // Faturamento total do mês = OS concluídas + vendas de balcão
    $faturamentoMes = $faturamentoOs + $faturamentoVendas;

    $valorEmAndamento = OrdemServico::whereIn('status', ['aberta', 'em_andamento'])
        ->sum('valor_total');

    $ultimasOs = OrdemServico::with('cliente', 'veiculo')
        ->latest()
        ->take(5)
        ->get();

    return view('dashboard', compact(
        'totalProdutos',
        'produtosEstoqueBaixo',
        'osAbertas',
        'faturamentoMes',
        'valorEmAndamento',
        'ultimasOs'
    ));
}
}
