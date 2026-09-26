<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Financeiro</p>
        <h1 class="text-xl font-medium text-gray-900">Contas a Pagar</h1>
    </x-slot>

    <div class="space-y-4">
        @if (session('sucesso'))
            <div class="p-4 bg-green-50 text-green-700 rounded-lg text-sm">{{ session('sucesso') }}</div>
        @endif

        <div class="flex items-center justify-between">
            <a href="{{ route('contas-pagar.create') }}"
               class="inline-flex items-center gap-2 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors"
               style="background:#0c1f33;" onmouseover="this.style.background='#16324d'" onmouseout="this.style.background='#0c1f33'">
                <i class="ti ti-plus text-base"></i>
                Nova conta
            </a>

            <div class="flex gap-2 text-sm">
                <a href="{{ route('contas-pagar.index') }}" class="px-3 py-1.5 rounded-lg {{ !request('status') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600' }}">Todas</a>
                <a href="{{ route('contas-pagar.index', ['status' => 'pendente']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') == 'pendente' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600' }}">Pendentes</a>
                <a href="{{ route('contas-pagar.index', ['status' => 'parcial']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') == 'parcial' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600' }}">Parciais</a>
                <a href="{{ route('contas-pagar.index', ['status' => 'pago']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') == 'pago' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600' }}">Pagas</a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Fornecedor</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Descrição</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Total</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Pago</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Restante</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Vencimento</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Status</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contas as $conta)
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5 text-gray-900">{{ $conta->fornecedor->nome }}</td>
                            <td class="px-5 py-3.5 text-gray-500">{{ $conta->descricao }}</td>
                            <td class="px-5 py-3.5 text-gray-500">R$ {{ number_format($conta->valor_total, 2, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-gray-500">R$ {{ number_format($conta->valor_pago, 2, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-gray-500">R$ {{ number_format($conta->valor_restante, 2, ',', '.') }}</td>
                            <td class="px-5 py-3.5 {{ $conta->vencida ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                {{ $conta->data_vencimento->format('d/m/Y') }}
                                @if ($conta->vencida) <span class="text-xs">(vencida)</span> @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @php
                                    $cores = ['pendente' => 'bg-gray-100 text-gray-600', 'parcial' => 'bg-yellow-50 text-yellow-700', 'pago' => 'bg-green-50 text-green-700'];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs {{ $cores[$conta->status] }}">{{ ucfirst($conta->status) }}</span>
                            </td>
                            <td class="px-5 py-3.5 space-x-3">
                                @if ($conta->status !== 'pago')
                                    <button type="button" onclick="document.getElementById('pagar-{{ $conta->id }}').classList.toggle('hidden')" class="text-sm text-blue-600 hover:text-blue-800">Pagar</button>
                                @endif
                                <a href="{{ route('contas-pagar.edit', $conta) }}" class="text-sm text-gray-500 hover:text-gray-700">Editar</a>
                                <form action="{{ route('contas-pagar.destroy', $conta) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm" style="color:#A32D2D;">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        @if ($conta->status !== 'pago')
                            <tr id="pagar-{{ $conta->id }}" class="hidden bg-gray-50">
                                <td colspan="8" class="px-5 py-4">
                                    <form action="{{ route('contas-pagar.pagar', $conta) }}" method="POST" class="flex items-end gap-3">
                                        @csrf
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">Valor a pagar (restante: R$ {{ number_format($conta->valor_restante, 2, ',', '.') }})</label>
                                            <input type="number" step="0.01" name="valor" max="{{ $conta->valor_restante }}" required class="rounded-lg border-gray-300 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">Data do pagamento</label>
                                            <input type="date" name="data_pagamento" value="{{ date('Y-m-d') }}" required class="rounded-lg border-gray-300 text-sm">
                                        </div>
                                        <button type="submit" class="text-white text-sm font-medium px-4 py-2 rounded-lg" style="background:#0c1f33;">Confirmar</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @if ($contas->isEmpty())
                        <tr><td colspan="8" class="px-5 py-8 text-center text-gray-400">Nenhuma conta a pagar cadastrada.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>