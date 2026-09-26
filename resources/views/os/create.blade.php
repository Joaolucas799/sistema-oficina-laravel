<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Oficina</p>
        <h1 class="text-xl font-medium text-gray-900">Abrir ordem de serviço</h1>
    </x-slot>

    <div class="max-w-md">
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <form action="{{ route('os.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                    <select name="cliente_id" id="cliente_id" onchange="atualizarVeiculos()"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Selecione o cliente --</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                    @error('cliente_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Veículo</label>
                    <select name="veiculo_id" id="veiculo_id" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Selecione o cliente primeiro --</option>
                    </select>
                    @error('veiculo_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea name="observacoes" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="submit"
                            class="text-white text-sm font-medium px-4 py-2.5 rounded-lg" style="background:#0c1f33;">
                        Abrir OS
                    </button>
                    <a href="{{ route('os.index') }}"
                       class="text-sm text-gray-600 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const veiculosPorCliente = {
            @foreach ($clientes as $cliente)
                {{ $cliente->id }}: [
                    @foreach ($cliente->veiculos as $veiculo)
                        { id: {{ $veiculo->id }}, texto: "{{ $veiculo->placa }} — {{ $veiculo->modelo }}" },
                    @endforeach
                ],
            @endforeach
        };

        function atualizarVeiculos() {
            const clienteId = document.getElementById('cliente_id').value;
            const selectVeiculo = document.getElementById('veiculo_id');
            selectVeiculo.innerHTML = '<option value="">-- Selecione o veículo --</option>';

            if (clienteId && veiculosPorCliente[clienteId]) {
                veiculosPorCliente[clienteId].forEach(function (veiculo) {
                    const option = document.createElement('option');
                    option.value = veiculo.id;
                    option.textContent = veiculo.texto;
                    selectVeiculo.appendChild(option);
                });
            }
        }
    </script>
</x-app-layout>