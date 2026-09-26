<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Estoque</p>
        <h1 class="text-xl font-medium text-gray-900">Saídas de estoque</h1>
    </x-slot>

    <div class="space-y-4">
        @if (session('sucesso'))
            <div class="p-4 bg-green-50 text-green-700 rounded-lg text-sm">{{ session('sucesso') }}</div>
        @endif

        <div class="flex items-center justify-between gap-4 flex-wrap">
            <a href="{{ route('saidas.create') }}"
               class="inline-flex items-center gap-2 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors"
               style="background:#0c1f33;" onmouseover="this.style.background='#16324d'" onmouseout="this.style.background='#0c1f33'">
                <i class="ti ti-plus text-base"></i>
                Registrar saída
            </a>

            <div class="relative flex-1 min-w-[240px] max-w-md">
                <input type="text" id="busca_saida_lista" placeholder="Buscar por produto..." autocomplete="off"
                       class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500 pl-9">
                <i class="ti ti-search text-gray-400 text-base absolute left-3 top-1/2 -translate-y-1/2"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Data</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Produto</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Quantidade</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Motivo</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Valor</th>
                    </tr>
                </thead>
                <tbody id="tabela_saidas_lista">
                    @foreach ($saidas as $saida)
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors" data-produto="{{ strtolower($saida->produto->nome) }}">
                            <td class="px-5 py-3.5 text-gray-500">{{ $saida->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3.5 text-gray-900">{{ $saida->produto->nome }}</td>
                            <td class="px-5 py-3.5 text-right font-medium" style="color:#A32D2D;">-{{ $saida->quantidade }}</td>
                            <td class="px-5 py-3.5 text-gray-500">{{ $saida->motivo ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-right {{ $saida->e_venda ? 'font-medium' : 'text-gray-400' }}" style="{{ $saida->e_venda ? 'color:#3B6D11;' : '' }}">
                              {{ $saida->e_venda ? 'R$ ' . number_format($saida->valor_venda, 2, ',', '.') : '—' }}
                           </td>
                        </tr>
                    @endforeach
                    @if ($saidas->isEmpty())
                        <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Nenhuma saída registrada ainda.</td></tr>
                    @endif
                </tbody>
            </table>
            <div id="busca_sem_resultado_saida" class="px-5 py-8 text-center text-gray-400 hidden">Nenhum resultado encontrado.</div>
        </div>
    </div>

    <script>
        const inputBuscaSaida = document.getElementById('busca_saida_lista');
        const linhasSaidas = document.querySelectorAll('#tabela_saidas_lista tr[data-produto]');
        const semResultadoSaida = document.getElementById('busca_sem_resultado_saida');

        if (inputBuscaSaida) {
            inputBuscaSaida.addEventListener('input', function () {
                const termo = this.value.toLowerCase().trim();
                let algumVisivel = false;

                linhasSaidas.forEach(function (linha) {
                    const bate = linha.getAttribute('data-produto').includes(termo);
                    linha.style.display = bate ? '' : 'none';
                    if (bate) algumVisivel = true;
                });

                semResultadoSaida.classList.toggle('hidden', algumVisivel || linhasSaidas.length === 0);
            });
        }
    </script>
</x-app-layout>
