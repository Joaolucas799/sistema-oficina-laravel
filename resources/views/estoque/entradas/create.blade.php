<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Estoque</p>
        <h1 class="text-xl font-medium text-gray-900">Registrar entrada</h1>
    </x-slot>

    <div class="max-w-md">
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <form action="{{ route('entradas.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Produto (busque por código ou nome)</label>
                    <input type="text" id="busca_produto_entrada" placeholder="Digite o código ou nome..." autocomplete="off"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    <input type="hidden" name="produto_id" id="produto_id_entrada">
                    <div id="lista_produto_entrada" class="absolute z-10 bg-white border border-gray-200 rounded-lg shadow-md mt-1 w-full max-h-56 overflow-y-auto hidden"></div>
                    @error('produto_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade recebida</label>
                    <input type="number" name="quantidade" min="1" value="{{ old('quantidade') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('quantidade') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivo / Observação</label>
                    <input type="text" name="motivo" placeholder="Ex: Compra NF 12345" value="{{ old('motivo') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="submit"
                            class="text-white text-sm font-medium px-4 py-2.5 rounded-lg" style="background:#3B6D11;">
                        Registrar entrada
                    </button>
                    <a href="{{ route('entradas.index') }}"
                       class="text-sm text-gray-600 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const produtosEntradaData = [
            @foreach ($produtos as $produto)
                { id: {{ $produto->id }}, codigo: "{{ $produto->codigo }}", nome: "{{ addslashes($produto->nome) }}", estoque: {{ $produto->estoque_atual }} },
            @endforeach
        ];

        const inputEntrada = document.getElementById('busca_produto_entrada');
        const listaEntrada = document.getElementById('lista_produto_entrada');
        const hiddenEntrada = document.getElementById('produto_id_entrada');

        inputEntrada.addEventListener('input', function () {
            const termo = this.value.toLowerCase();
            hiddenEntrada.value = '';

            if (termo.length === 0) {
                listaEntrada.classList.add('hidden');
                return;
            }

            const filtrados = produtosEntradaData.filter(p =>
                p.codigo.toLowerCase().includes(termo) || p.nome.toLowerCase().includes(termo)
            );

            listaEntrada.innerHTML = filtrados.length === 0
                ? '<div class="px-3 py-2 text-sm text-gray-400">Nenhum produto encontrado</div>'
                : filtrados.map(p => `
                    <div class="px-3 py-2 text-sm hover:bg-gray-50 cursor-pointer"
                         onclick="selecionarProdutoEntrada(${p.id}, '${p.codigo}', '${p.nome.replace(/'/g, "\\'")}', ${p.estoque})">
                        <strong>${p.codigo}</strong> — ${p.nome} <span class="text-gray-400">(estoque: ${p.estoque})</span>
                    </div>
                `).join('');

            listaEntrada.classList.remove('hidden');
        });

        function selecionarProdutoEntrada(id, codigo, nome, estoque) {
            hiddenEntrada.value = id;
            inputEntrada.value = codigo + ' — ' + nome;
            listaEntrada.classList.add('hidden');
        }

        document.addEventListener('click', function (e) {
            if (!inputEntrada.contains(e.target) && !listaEntrada.contains(e.target)) {
                listaEntrada.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
