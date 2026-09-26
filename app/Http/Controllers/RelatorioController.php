<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\MovimentacaoEstoque;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    public function faturamento(Request $request)
    {
        // Se o usuário não escolher mês/ano, usa o mês atual como padrão
        $mes = $request->input('mes', now()->month);
        $ano = $request->input('ano', now()->year);

        $osDoMes = OrdemServico::with('cliente', 'veiculo')
            ->where('status', 'concluida')
            ->whereMonth('data_conclusao', $mes)
            ->whereYear('data_conclusao', $ano)
            ->orderBy('data_conclusao')
            ->get();

        $faturamentoOs = $osDoMes->sum('valor_total');

        $vendasDoMes = MovimentacaoEstoque::with('produto')
            ->where('e_venda', true)
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->orderBy('created_at')
            ->get();

        $faturamentoVendas = $vendasDoMes->sum('valor_venda');

        $faturamentoTotal = $faturamentoOs + $faturamentoVendas;

        // Monta os dados dos últimos 6 meses, para o gráfico de comparação
        $historico = collect(range(5, 0))->map(function ($i) {
            $data = now()->subMonths($i);

            $totalOs = OrdemServico::where('status', 'concluida')
                ->whereMonth('data_conclusao', $data->month)
                ->whereYear('data_conclusao', $data->year)
                ->sum('valor_total');

            $totalVendas = MovimentacaoEstoque::where('e_venda', true)
                ->whereMonth('created_at', $data->month)
                ->whereYear('created_at', $data->year)
                ->sum('valor_venda');

            return [
                'label' => $data->translatedFormat('M/Y'),
                'total' => $totalOs + $totalVendas,
            ];
        });

        return view('relatorios.faturamento', compact(
            'mes', 'ano', 'osDoMes', 'vendasDoMes',
            'faturamentoOs', 'faturamentoVendas', 'faturamentoTotal', 'historico'
        ));
    }
}
