<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Cadastros</p>
        <h1 class="text-xl font-medium text-gray-900">Editar serviço</h1>
    </x-slot>

    <div class="max-w-md">
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <form action="{{ route('servicos.update', $servico) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do serviço</label>
                    <input type="text" name="nome" value="{{ old('nome', $servico->nome) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('nome') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor padrão (R$)</label>
                    <input type="number" step="0.01" name="valor_padrao" value="{{ old('valor_padrao', $servico->valor_padrao) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('valor_padrao') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit"
                            class="text-white text-sm font-medium px-4 py-2.5 rounded-lg"
                            style="background:#0c1f33;">Atualizar</button>
                    <a href="{{ route('servicos.index') }}"
                       class="text-sm text-gray-600 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>