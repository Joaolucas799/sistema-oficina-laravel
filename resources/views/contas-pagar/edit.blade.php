<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Financeiro</p>
        <h1 class="text-xl font-medium text-gray-900">Editar Conta a Pagar</h1>
    </x-slot>

    <div class="bg-white border border-gray-200 rounded-2xl p-6 max-w-xl">
        <form action="{{ route('contas-pagar.update', $conta) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm text-gray-600 mb-1">Fornecedor</label>
                <select name="fornecedor_id" required class="w-full rounded-lg border-gray-300 text-sm">
                    @foreach ($fornecedores as $fornecedor)
                        <option value="{{ $fornecedor->id }}" @selected(old('fornecedor_id', $conta->fornecedor_id) == $fornecedor->id)>{{ $fornecedor->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Descrição</label>
                <input type="text" name="descricao" value="{{ old('descricao', $conta->descricao) }}" required class="w-full rounded-lg border-gray-300 text-sm">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Valor total (R$)</label>
                <input type="number" step="0.01" name="valor_total" value="{{ old('valor_total', $conta->valor_total) }}" required class="w-full rounded-lg border-gray-300 text-sm">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Data de vencimento</label>
                <input type="date" name="data_vencimento" value="{{ old('data_vencimento', $conta->data_vencimento->format('Y-m-d')) }}" required class="w-full rounded-lg border-gray-300 text-sm">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="text-white text-sm font-medium px-4 py-2.5 rounded-lg" style="background:#0c1f33;">Atualizar</button>
                <a href="{{ route('contas-pagar.index') }}" class="text-sm text-gray-500 px-4 py-2.5">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>