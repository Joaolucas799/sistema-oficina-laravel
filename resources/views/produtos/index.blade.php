<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Estoque</p>
        <h1 class="text-xl font-medium text-gray-900">Produtos</h1>
    </x-slot>

    <div class="space-y-4">
        @if (session('sucesso'))
            <div class="p-4 bg-green-50 text-green-700 rounded-lg text-sm">{{ session('sucesso') }}</div>
        @endif

        <div class="flex items-center justify-between gap-4 flex-wrap">
            <a href="{{ route('produtos.create') }}"
               class="inline-flex items-center gap-2 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors"
               style="background:#0c1f33;" onmouseover="this.style.background='#16324d'" onmouseout="this.style.background='#0c1f33'">
                <i class="ti ti-plus text-base"></i>
                Novo produto
            </a>

            <div class="relative flex-1 min-w-[240px] max-w-md">
                <input type="text" id="busca_produto_lista" placeholder="Buscar por código ou nome..." autocomplete="off"
                       class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500 pl-9">
                <i class="ti ti-search text-gray-400 text-base absolute left-3 top-1/2 -translate-y-1/2"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Código</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Nome</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Categoria</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Fornecedor</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Estoque</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Preço venda</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela_produtos_lista">
                    @foreach ($produtos as $produto)
                        @php $baixo = $produto->estoque_atual <= $produto->estoque_minimo; @endphp
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors"
                            data-codigo="{{ strtolower($produto->codigo) }}" data-nome="{{ strtolower($produto->nome) }}">
                            <td class="px-5 py-3.5 text-gray-500">{{ $produto->codigo }}</td>
                            <td class="px-5 py-3.5 text-gray-900">{{ $produto->nome }}</td>
                            <td class="px-5 py-3.5 text-gray-500">{{ $produto->categoria?->nome ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-gray-500">{{ $produto->fornecedor?->nome ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-right {{ $baixo ? 'font-medium' : 'text-gray-700' }}" style="{{ $baixo ? 'color:#993C1D;' : '' }}">
                                {{ $produto->estoque_atual }}
                                @if ($baixo)
                                    <i class="ti ti-alert-triangle text-xs" style="color:#993C1D;"></i>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right text-gray-700">R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</td>
                            <td class="px-5 py-3.5 space-x-3">
                                <a href="{{ route('produtos.edit', $produto) }}" class="text-sm text-gray-500 hover:text-gray-700">Editar</a>
                                <form action="{{ route('produtos.destroy', $produto) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm" style="color:#A32D2D;">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($produtos->isEmpty())
                        <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400">Nenhum produto cadastrado ainda.</td></tr>
                    @endif
                </tbody>
            </table>
            <div id="busca_sem_resultado" class="px-5 py-8 text-center text-gray-400 hidden">Nenhum produto encontrado.</div>
        </div>
    </div>

    <script>
        const inputBuscaLista = document.getElementById('busca_produto_lista');
        const linhasProdutos = document.querySelectorAll('#tabela_produtos_lista tr[data-codigo]');
        const semResultado = document.getElementById('busca_sem_resultado');

        if (inputBuscaLista) {
            inputBuscaLista.addEventListener('input', function () {
                const termo = this.value.toLowerCase().trim();
                let algumVisivel = false;

                linhasProdutos.forEach(function (linha) {
                    const codigo = linha.getAttribute('data-codigo');
                    const nome = linha.getAttribute('data-nome');
                    const bate = codigo.includes(termo) || nome.includes(termo);

                    linha.style.display = bate ? '' : 'none';
                    if (bate) algumVisivel = true;
                });

                semResultado.classList.toggle('hidden', algumVisivel || linhasProdutos.length === 0);
            });
        }
    </script>
</x-app-layout>
