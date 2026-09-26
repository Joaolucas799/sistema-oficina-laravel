<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Cadastros</p>
        <h1 class="text-xl font-medium text-gray-900">Serviços</h1>
    </x-slot>

    <div class="space-y-4">
        @if (session('sucesso'))
            <div class="p-4 bg-green-50 text-green-700 rounded-lg text-sm">{{ session('sucesso') }}</div>
        @endif

        <div class="flex items-center justify-between gap-4 flex-wrap">
            <a href="{{ route('servicos.create') }}"
               class="inline-flex items-center gap-2 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors"
               style="background:#0c1f33;" onmouseover="this.style.background='#16324d'" onmouseout="this.style.background='#0c1f33'">
                <i class="ti ti-plus text-base"></i>
                Novo serviço
            </a>

            <div class="relative flex-1 min-w-[240px] max-w-md">
                <input type="text" id="busca_servico_lista" placeholder="Buscar por nome..." autocomplete="off"
                       class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500 pl-9">
                <i class="ti ti-search text-gray-400 text-base absolute left-3 top-1/2 -translate-y-1/2"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Nome</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Valor padrão</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela_servicos_lista">
                    @foreach ($servicos as $servico)
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors" data-nome="{{ strtolower($servico->nome) }}">
                            <td class="px-5 py-3.5 text-gray-900">{{ $servico->nome }}</td>
                            <td class="px-5 py-3.5 text-right text-gray-700">R$ {{ number_format($servico->valor_padrao, 2, ',', '.') }}</td>
                            <td class="px-5 py-3.5 space-x-3">
                                <a href="{{ route('servicos.edit', $servico) }}" class="text-sm text-gray-500 hover:text-gray-700">Editar</a>
                                <form action="{{ route('servicos.destroy', $servico) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm" style="color:#A32D2D;">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($servicos->isEmpty())
                        <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400">Nenhum serviço cadastrado ainda.</td></tr>
                    @endif
                </tbody>
            </table>
            <div id="busca_sem_resultado_servico" class="px-5 py-8 text-center text-gray-400 hidden">Nenhum serviço encontrado.</div>
        </div>
    </div>

    <script>
        const inputBuscaServico = document.getElementById('busca_servico_lista');
        const linhasServicos = document.querySelectorAll('#tabela_servicos_lista tr[data-nome]');
        const semResultadoServico = document.getElementById('busca_sem_resultado_servico');

        if (inputBuscaServico) {
            inputBuscaServico.addEventListener('input', function () {
                const termo = this.value.toLowerCase().trim();
                let algumVisivel = false;

                linhasServicos.forEach(function (linha) {
                    const bate = linha.getAttribute('data-nome').includes(termo);
                    linha.style.display = bate ? '' : 'none';
                    if (bate) algumVisivel = true;
                });

                semResultadoServico.classList.toggle('hidden', algumVisivel || linhasServicos.length === 0);
            });
        }
    </script>
</x-app-layout>
