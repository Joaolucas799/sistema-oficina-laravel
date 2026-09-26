<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Painel geral</p>
        <h1 class="text-xl font-medium text-gray-900">Olá, {{ auth()->user()->name }}</h1>
    </x-slot>

    <div class="space-y-6">

        {{-- Cartões de indicador, divididos por linhas finas --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-px bg-gray-200 border border-gray-200 rounded-2xl overflow-hidden">
            <div class="bg-white p-5">
                <p class="text-[12.5px] text-gray-500 mb-1.5">Produtos</p>
                <p class="text-2xl font-medium text-gray-900">{{ $totalProdutos }}</p>
            </div>

            <div class="bg-white p-5">
                <p class="text-[12.5px] text-gray-500 mb-1.5">Estoque baixo</p>
                <p class="text-2xl font-medium text-gray-900">{{ $produtosEstoqueBaixo->count() }}</p>
            </div>

            <div class="bg-white p-5">
                <p class="text-[12.5px] text-gray-500 mb-1.5">OS abertas</p>
                <p class="text-2xl font-medium text-gray-900">{{ $osAbertas }}</p>
            </div>

            <div class="p-5" style="background:#0c1f33;">
                <p class="text-[12.5px] mb-1.5" style="color:#9db3c6;">Faturamento (mês)</p>
                <p class="text-2xl font-medium" style="color:#85B7EB;">
                    R$ {{ number_format($faturamentoMes, 2, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Peças com estoque baixo --}}
        <div>
            <p class="text-sm font-medium text-gray-600 mb-2">Peças com estoque baixo</p>
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Produto</th>
                            <th class="px-4 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Estoque atual</th>
                            <th class="px-4 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Estoque mínimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produtosEstoqueBaixo as $produto)
                            <tr class="border-t border-gray-100">
                                <td class="px-4 py-3">{{ $produto->nome }}</td>
                                <td class="px-4 py-3 text-right" style="color:#993C1D;">{{ $produto->estoque_atual }}</td>
                                <td class="px-4 py-3 text-right text-gray-500">{{ $produto->estoque_minimo }}</td>
                            </tr>
                        @endforeach

                        @if ($produtosEstoqueBaixo->isEmpty())
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-400">
                                    Nenhuma peça com estoque baixo.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Últimas Ordens de Serviço --}}
        <div>
            <p class="text-sm font-medium text-gray-600 mb-2">Ordens de serviço recentes</p>
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Cliente</th>
                            <th class="px-4 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Veículo</th>
                            <th class="px-4 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Placa</th>
                            <th class="px-4 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Status</th>
                            <th class="px-4 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cores = [
                                'aberta' => '#185FA5',
                                'em_andamento' => '#5f5e5a',
                                'concluida' => '#3B6D11',
                                'cancelada' => '#A32D2D',
                            ];
                        @endphp
                        @foreach ($ultimasOs as $os)
                            <tr class="border-t border-gray-100">
                                <td class="px-4 py-3">{{ $os->cliente->nome }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $os->veiculo->marca }} {{ $os->veiculo->modelo }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $os->veiculo->placa }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span style="color: {{ $cores[$os->status] }}">
                                        ● {{ str_replace('_', ' ', $os->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-gray-400">{{ $os->created_at->format('d M') }}</td>
                            </tr>
                        @endforeach

                        @if ($ultimasOs->isEmpty())
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                                    Nenhuma Ordem de Serviço registrada ainda.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>