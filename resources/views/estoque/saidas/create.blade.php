<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Estoque</p>
        <h1 class="text-xl font-medium text-gray-900">Registrar saída</h1>
    </x-slot>

    <div class="max-w-md">
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <form action="{{ route('saidas.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Produto (busque por código ou nome)</label>
                    <input type="text" id="busca_produto_saida" placeholder="Digite o código ou nome..." autocomplete="off"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    <input type="hidden" name="produto_id" id="produto_id_saida">
                    <div id="lista_produto_saida" class="absolute z-10 bg-white border border-gray-200 rounded-lg shadow-md mt-1 w-full max-h-56 overflow-y-auto hidden"></div>
                    @error('produto_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade retirada</label>
                    <input type="number" name="quantidade" min="1" value="{{ old('quantidade') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('quantidade') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivo / Observação</label>
                    <input type="text" name="motivo" placeholder="Ex: Uso na OS 123" value="{{ old('motivo') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="e_venda" id="e_venda" value="1"
                           class="rounded border-gray-300 text-blue-900 focus:ring-blue-500">
                    <label for="e_venda" class="text-sm text-gray-700">
                        Essa saída é uma venda no balcão (gera faturamento)
                    </label>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="submit"
                            class="text-white text-sm font-medium px-4 py-2.5 rounded-lg" style="background:#A32D2D;">
                        Registrar saída
                    </button>
                    <a href="{{ route('saidas.index') }}"
                       class="text-sm text-gray-600 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const produtosSaidaData = [
            @foreach ($produtos as $produto)
                { id: {{ $produto->id }}, codigo: "{{ $produto->codigo }}", nome: "{{ addslashes($produto->nome) }}", estoque: {{ $produto->estoque_atual }} },
            @endforeach
        ];

        const inputSaida = document.getElementById('busca_produto_saida');
        const listaSaida = document.getElementById('lista_produto_saida');
        const hiddenSaida = document.getElementById('produto_id_saida');

        inputSaida.addEventListener('input', function () {
            const termo = this.value.toLowerCase();
            hiddenSaida.value = '';

            if (termo.length === 0) {
                listaSaida.classList.add('hidden');
                return;
            }

            const filtrados = produtosSaidaData.filter(p =>
                p.codigo.toLowerCase().includes(termo) || p.nome.toLowerCase().includes(termo)
            );

            listaSaida.innerHTML = filtrados.length === 0
                ? '<div class="px-3 py-2 text-sm text-gray-400">Nenhum produto encontrado</div>'
                : filtrados.map(p => `
                    <div class="px-3 py-2 text-sm hover:bg-gray-50 cursor-pointer"
                         onclick="selecionarProdutoSaida(${p.id}, '${p.codigo}', '${p.nome.replace(/'/g, "\\'")}', ${p.estoque})">
                        <strong>${p.codigo}</strong> — ${p.nome} <span class="text-gray-400">(estoque: ${p.estoque})</span>
                    </div>
                `).join('');

            listaSaida.classList.remove('hidden');
        });

        function selecionarProdutoSaida(id, codigo, nome, estoque) {
            hiddenSaida.value = id;
            inputSaida.value = codigo + ' — ' + nome;
            listaSaida.classList.add('hidden');
        }

        document.addEventListener('click', function (e) {
            if (!inputSaida.contains(e.target) && !listaSaida.contains(e.target)) {
                listaSaida.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
