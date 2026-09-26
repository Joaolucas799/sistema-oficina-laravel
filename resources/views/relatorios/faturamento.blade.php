<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Relatórios</p>
        <h1 class="text-xl font-medium text-gray-900">Faturamento</h1>
    </x-slot>

    <div class="space-y-6">

        <form method="GET" class="bg-white border border-gray-200 rounded-2xl p-5 flex gap-3 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mês</label>
                <select name="mes" class="rounded-lg border-gray-300 shadow-sm text-sm">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ano</label>
                <select name="ano" class="rounded-lg border-gray-300 shadow-sm text-sm">
                    @foreach (range(now()->year, now()->year - 3) as $a)
                        <option value="{{ $a }}" {{ $ano == $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="text-white text-sm font-medium px-4 py-2.5 rounded-lg" style="background:#0c1f33;">
                Filtrar
            </button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-px bg-gray-200 border border-gray-200 rounded-2xl overflow-hidden">
            <div class="bg-white p-5">
                <p class="text-[12.5px] text-gray-500 mb-1.5">Faturamento em OS</p>
                <p class="text-2xl font-medium text-gray-900">R$ {{ number_format($faturamentoOs, 2, ',', '.') }}</p>
            </div>
            <div class="bg-white p-5">
                <p class="text-[12.5px] text-gray-500 mb-1.5">Vendas de balcão</p>
                <p class="text-2xl font-medium text-gray-900">R$ {{ number_format($faturamentoVendas, 2, ',', '.') }}</p>
            </div>
            <div class="p-5" style="background:#0c1f33;">
                <p class="text-[12.5px] mb-1.5" style="color:#9db3c6;">Total do período</p>
                <p class="text-2xl font-medium" style="color:#85B7EB;">R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}</p>
            </div>
        </div>

        <div>
            <p class="text-sm font-medium text-gray-600 mb-2">Últimos 6 meses</p>
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Mês</th>
                            <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Faturamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historico as $h)
                            <tr class="border-t border-gray-100">
                                <td class="px-5 py-3">{{ $h['label'] }}</td>
                                <td class="px-5 py-3 text-right">R$ {{ number_format($h['total'], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <p class="text-sm font-medium text-gray-600 mb-2">Ordens de serviço concluídas no período</p>
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Cliente</th>
                            <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Veículo</th>
                            <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Concluída em</th>
                            <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($osDoMes as $os)
                            <tr class="border-t border-gray-100">
                                <td class="px-5 py-3">{{ $os->cliente->nome }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $os->veiculo->placa }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $os->data_conclusao->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 text-right">R$ {{ number_format($os->valor_total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        @if ($osDoMes->isEmpty())
                            <tr><td colspan="4" class="px-5 py-6 text-center text-gray-400">Nenhuma OS concluída neste período.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <p class="text-sm font-medium text-gray-600 mb-2">Vendas de balcão no período</p>
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Produto</th>
                            <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Data</th>
                            <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendasDoMes as $venda)
                            <tr class="border-t border-gray-100">
                                <td class="px-5 py-3">{{ $venda->produto->nome }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $venda->created_at->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 text-right">R$ {{ number_format($venda->valor_venda, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        @if ($vendasDoMes->isEmpty())
                            <tr><td colspan="3" class="px-5 py-6 text-center text-gray-400">Nenhuma venda de balcão neste período.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>