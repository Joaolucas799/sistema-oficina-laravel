<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Oficina</p>
        <h1 class="text-xl font-medium text-gray-900">Ordens de serviço</h1>
    </x-slot>

    <div class="space-y-4">
        @if (session('sucesso'))
            <div class="p-4 bg-green-50 text-green-700 rounded-lg text-sm">{{ session('sucesso') }}</div>
        @endif

        <div class="flex items-center justify-between gap-4 flex-wrap">
            <a href="{{ route('os.create') }}"
               class="inline-flex items-center gap-2 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors"
               style="background:#0c1f33;" onmouseover="this.style.background='#16324d'" onmouseout="this.style.background='#0c1f33'">
                <i class="ti ti-plus text-base"></i>
                Abrir OS
            </a>

            <div class="relative flex-1 min-w-[240px] max-w-md">
                <input type="text" id="busca_os_lista" placeholder="Buscar por cliente, veículo ou status..." autocomplete="off"
                       class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500 pl-9">
                <i class="ti ti-search text-gray-400 text-base absolute left-3 top-1/2 -translate-y-1/2"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Cliente</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Veículo</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Status</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal text-right">Valor total</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Aberta em</th>
                        <th class="px-5 py-3 text-[11.5px] uppercase tracking-wide text-gray-400 font-normal">Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela_os_lista">
                    @php
                        $cores = ['aberta' => '#185FA5', 'em_andamento' => '#5f5e5a', 'concluida' => '#3B6D11', 'cancelada' => '#A32D2D'];
                    @endphp
                    @foreach ($ordens as $ordem)
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors"
                            data-cliente="{{ strtolower($ordem->cliente->nome) }}"
                            data-veiculo="{{ strtolower($ordem->veiculo->placa . ' ' . $ordem->veiculo->modelo) }}"
                            data-status="{{ strtolower(str_replace('_', ' ', $ordem->status)) }}">
                            <td class="px-5 py-3.5 text-gray-900">{{ $ordem->cliente->nome }}</td>
                            <td class="px-5 py-3.5 text-gray-500">{{ $ordem->veiculo->placa }} — {{ $ordem->veiculo->modelo }}</td>
                            <td class="px-5 py-3.5">
                                <span style="color: {{ $cores[$ordem->status] }}">● {{ str_replace('_', ' ', $ordem->status) }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right text-gray-700">R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-gray-500">{{ $ordem->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('os.show', $ordem) }}" class="text-sm" style="color:#185FA5;">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                    @if ($ordens->isEmpty())
                        <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Nenhuma Ordem de Serviço registrada ainda.</td></tr>
                    @endif
                </tbody>
            </table>
            <div id="busca_sem_resultado_os" class="px-5 py-8 text-center text-gray-400 hidden">Nenhuma OS encontrada.</div>
        </div>
    </div>

    <script>
        const inputBuscaOs = document.getElementById('busca_os_lista');
        const linhasOs = document.querySelectorAll('#tabela_os_lista tr[data-cliente]');
        const semResultadoOs = document.getElementById('busca_sem_resultado_os');

        if (inputBuscaOs) {
            inputBuscaOs.addEventListener('input', function () {
                const termo = this.value.toLowerCase().trim();
                let algumVisivel = false;

                linhasOs.forEach(function (linha) {
                    const bate = linha.getAttribute('data-cliente').includes(termo)
                        || linha.getAttribute('data-veiculo').includes(termo)
                        || linha.getAttribute('data-status').includes(termo);
                    linha.style.display = bate ? '' : 'none';
                    if (bate) algumVisivel = true;
                });

                semResultadoOs.classList.toggle('hidden', algumVisivel || linhasOs.length === 0);
            });
        }
    </script>
</x-app-layout>
