<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Fiado</p>
        <h1 class="text-xl font-medium text-gray-900">{{ $fiado->cliente->nome }}</h1>
    </x-slot>

    <div class="space-y-6 max-w-2xl">

        @if (session('success'))
            <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500">{{ $fiado->descricao ?? 'Sem descrição' }}</p>
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($fiado->data)->format('d/m/Y') }}</p>
                </div>
                @if ($fiado->status === 'pago')
                    <span class="text-xs text-green-700 bg-green-50 px-2 py-0.5 rounded-full">Pago</span>
                @elseif ($fiado->status === 'parcial')
                    <span class="text-xs text-yellow-700 bg-yellow-50 px-2 py-0.5 rounded-full">Parcial</span>
                @else
                    <span class="text-xs text-red-600 bg-red-50 px-2 py-0.5 rounded-full">Pendente</span>
                @endif
            </div>

            <div class="grid grid-cols-3 gap-4 text-center border-t border-b py-4">
                <div>
                    <p class="text-xs text-gray-500">Valor total</p>
                    <p class="text-lg font-semibold text-gray-800">R$ {{ number_format($fiado->valor_total, 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Pago</p>
                    <p class="text-lg font-semibold text-green-700">R$ {{ number_format($fiado->valorPago(), 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Restante</p>
                    <p class="text-lg font-semibold text-red-600">R$ {{ number_format($fiado->valorRestante(), 2, ',', '.') }}</p>
                </div>
            </div>

            @if ($fiado->status !== 'pago')
                <form action="{{ route('fiado.pagar', $fiado) }}" method="POST" class="flex gap-2 pt-4">
                    @csrf
                    <input type="number" step="0.01" name="valor" placeholder="Valor do pagamento"
                           class="flex-1 rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <button type="submit"
                            class="text-white text-sm font-medium px-4 py-2.5 rounded-lg"
                            style="background:#0c1f33;">Registrar pagamento</button>
                </form>
                @error('valor') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-sm font-semibold text-gray-700">Histórico de pagamentos</h2>
            </div>
            <div class="divide-y">
                @forelse ($fiado->pagamentos as $pagamento)
                    <div class="flex items-center justify-between px-6 py-3">
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y') }}</p>
                        <p class="text-sm font-medium text-green-700">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="px-6 py-6 text-sm text-gray-500 text-center">Nenhum pagamento registrado ainda.</p>
                @endforelse
            </div>
        </div>

        <a href="{{ route('fiado.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Voltar para lista de fiados</a>

    </div>
</x-app-layout>
