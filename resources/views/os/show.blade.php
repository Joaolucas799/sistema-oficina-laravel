<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Oficina</p>
        <h1 class="text-xl font-medium text-gray-900">Ordem de Serviço #{{ $os->id }}</h1>
    </x-slot>

    <div class="max-w-3xl space-y-6">

        @if (session('sucesso'))
            <div class="p-4 bg-green-50 text-green-700 rounded-lg text-sm">{{ session('sucesso') }}</div>
        @endif

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <div class="flex justify-between items-start mb-5">
                <div class="text-sm text-gray-600 space-y-1">
                    <p>Cliente: <span class="text-gray-900 font-medium">{{ $os->cliente->nome }}</span></p>
                    <p>Veículo: <span class="text-gray-900 font-medium">{{ $os->veiculo->placa }} — {{ $os->veiculo->marca }} {{ $os->veiculo->modelo }}</span></p>
                    <p class="text-gray-400 text-xs">Aberta em {{ $os->created_at->format('d/m/Y H:i') }}</p>
                    <a href="{{ route('os.imprimir', $os) }}" target="_blank"
                       class="text-sm inline-flex items-center gap-1 mt-2" style="color:#185FA5;">
                        <i class="ti ti-printer"></i> Imprimir OS
                    </a>
                </div>
                <p class="text-2xl font-medium text-gray-900">R$ {{ number_format($os->valor_total, 2, ',', '.') }}</p>
            </div>

            <form action="{{ route('os.update', $os) }}" method="POST" class="flex items-end gap-2">
                @csrf
                @method('PUT')
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="aberta" {{ $os->status == 'aberta' ? 'selected' : '' }}>Aberta</option>
                        <option value="em_andamento" {{ $os->status == 'em_andamento' ? 'selected' : '' }}>Em andamento</option>
                        <option value="concluida" {{ $os->status == 'concluida' ? 'selected' : '' }}>Concluída</option>
                        <option value="cancelada" {{ $os->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <input type="hidden" name="observacoes" value="{{ $os->observacoes }}">
                <button type="submit" class="text-white text-sm font-medium px-4 py-2.5 rounded-lg" style="background:#0c1f33;">
                    Atualizar status
                </button>
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <p class="text-sm font-medium text-gray-700 mb-4">Peças e serviços</p>

            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-2.5 text-[11px] uppercase tracking-wide text-gray-400 font-normal">Item</th>
                            <th class="px-4 py-2.5 text-[11px] uppercase tracking-wide text-gray-400 font-normal text-right">Qtd</th>
                            <th class="px-4 py-2.5 text-[11px] uppercase tracking-wide text-gray-400 font-normal text-right">Valor unit.</th>
                            <th class="px-4 py-2.5 text-[11px] uppercase tracking-wide text-gray-400 font-normal text-right">Subtotal</th>
                            <th class="px-4 py-2.5 text-[11px] uppercase tracking-wide text-gray-400 font-normal">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($os->itens as $item)
                            <tr class="border-t border-gray-100">
                                <td class="px-4 py-3">
                                    {{ $item->nomeItem() }}
                                    <span class="text-xs text-gray-400">
                                        ({{ $item->produto_id ? 'peça' : ($item->servico_id ? 'serviço' : 'serviço avulso') }})
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-gray-600">{{ $item->quantidade }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-gray-900">R$ {{ number_format($item->quantidade * $item->valor_unitario, 2, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('os.itens.destroy', $item) }}" method="POST" onsubmit="return confirm('Remover este item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs" style="color:#A32D2D;">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($os->itens->isEmpty())
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Nenhum item adicionado ainda.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <p class="text-sm font-medium text-gray-700 mb-3">Adicionar peça</p>
            <form action="{{ route('os.itens.store', $os) }}" method="POST" class="flex gap-2 items-end">
                @csrf
                <input type="hidden" name="tipo" value="produto">

                <div class="flex-1 relative">
                    <label class="block text-xs text-gray-500 mb-1">Produto (busque por código ou nome)</label>
                    <input type="text" id="busca_produto_os" placeholder="Digite o código ou nome..." autocomplete="off"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    <input type="hidden" name="produto_id" id="produto_id_os">
                    <div id="lista_produto_os" class="absolute z-10 bg-white border border-gray-200 rounded-lg shadow-md mt-1 w-full max-h-56 overflow-y-auto hidden"></div>
                </div>

                <input type="number" name="quantidade" value="1" min="1"
                       class="w-20 rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                <input type="number" step="0.01" name="valor_unitario" id="valor_produto_os" placeholder="Valor unit."
                       class="w-28 rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="submit" class="text-white text-sm font-medium px-4 py-2.5 rounded-lg" style="background:#3B6D11;">Adicionar</button>
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <p class="text-sm font-medium text-gray-700 mb-3">Adicionar serviço</p>

            <form action="{{ route('os.itens.store', $os) }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="tipo" value="servico">

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Serviço do catálogo (opcional)</label>
                    <select name="servico_id" id="select_servico" onchange="preencherValorServico()"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Nenhum --</option>
                        @foreach ($servicos as $servico)
                            <option value="{{ $servico->id }}" data-preco="{{ $servico->valor_padrao }}">
                                {{ $servico->nome }} — R$ {{ number_format($servico->valor_padrao, 2, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Descrição adicional (opcional)</label>
                    <input type="text" name="descricao" placeholder="Ex: Troca de correia dentada, detalhe extra..."
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('descricao') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-2 items-end">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Qtd</label>
                        <input type="number" name="quantidade" value="1" min="1"
                               class="w-20 rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Valor unit.</label>
                        <input type="number" step="0.01" name="valor_unitario" id="valor_servico" placeholder="0,00"
                               class="w-28 rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="text-white text-sm font-medium px-4 py-2.5 rounded-lg" style="background:#185FA5;">
                        Adicionar
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        function preencherValorServico() {
            const select = document.getElementById('select_servico');
            const preco = select.options[select.selectedIndex]?.dataset.preco;
            document.getElementById('valor_servico').value = preco ?? '';
        }

        const produtosOsData = [
            @foreach ($produtos as $produto)
                { id: {{ $produto->id }}, codigo: "{{ $produto->codigo }}", nome: "{{ addslashes($produto->nome) }}", estoque: {{ $produto->estoque_atual }}, preco: {{ $produto->preco_venda }} },
            @endforeach
        ];

        const inputOs = document.getElementById('busca_produto_os');
        const listaOs = document.getElementById('lista_produto_os');
        const hiddenOs = document.getElementById('produto_id_os');
        const valorOs = document.getElementById('valor_produto_os');

        inputOs.addEventListener('input', function () {
            const termo = this.value.toLowerCase();
            hiddenOs.value = '';

            if (termo.length === 0) {
                listaOs.classList.add('hidden');
                return;
            }

            const filtrados = produtosOsData.filter(p =>
                p.codigo.toLowerCase().includes(termo) || p.nome.toLowerCase().includes(termo)
            );

            listaOs.innerHTML = filtrados.length === 0
                ? '<div class="px-3 py-2 text-sm text-gray-400">Nenhum produto encontrado</div>'
                : filtrados.map(p => `
                    <div class="px-3 py-2 text-sm hover:bg-gray-50 cursor-pointer"
                         onclick="selecionarProdutoOs(${p.id}, '${p.codigo}', '${p.nome.replace(/'/g, "\\'")}', ${p.preco})">
                        <strong>${p.codigo}</strong> — ${p.nome} <span class="text-gray-400">(estoque: ${p.estoque})</span>
                    </div>
                `).join('');

            listaOs.classList.remove('hidden');
        });

        function selecionarProdutoOs(id, codigo, nome, preco) {
            hiddenOs.value = id;
            inputOs.value = codigo + ' — ' + nome;
            listaOs.classList.add('hidden');
            valorOs.value = preco;
        }

        document.addEventListener('click', function (e) {
            if (!inputOs.contains(e.target) && !listaOs.contains(e.target)) {
                listaOs.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
