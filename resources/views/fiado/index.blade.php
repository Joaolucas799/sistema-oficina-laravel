<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Financeiro</p>
        <h1 class="text-xl font-medium text-gray-900">Fiado</h1>
    </x-slot>

    <div class="space-y-6">

        @if (session('success'))
            <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 text-sm rounded-lg p-3">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Novo fiado</h2>

            <div class="flex gap-2 mb-4">
                <button type="button" id="tabManualBtn"
                        class="text-sm font-medium px-3 py-1.5 rounded-lg bg-[#0c1f33] text-white">Cadastro manual</button>
                <button type="button" id="tabOsBtn"
                        class="text-sm font-medium px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700">Puxar de uma OS</button>
            </div>

            <form action="{{ route('fiado.store') }}" method="POST" id="fiadoForm">
                @csrf
                <input type="hidden" name="ordem_servico_id" id="ordemServicoIdInput" value="">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div id="clienteWrapper">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                        <select name="cliente_id" id="clienteSelect" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Selecione --</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="osWrapper" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ordem de Serviço</label>
                        <select id="osSelect" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Selecione uma OS --</option>
                            @foreach ($ordensServico as $os)
                                <option value="{{ $os->id }}" data-cliente="{{ $os->cliente_id }}">
                                    OS #{{ $os->id }} · {{ $os->cliente->nome }} · R$ {{ number_format($os->valor_total, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="itensManualBox">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Itens do fiado</label>
                    <div id="itensList" class="space-y-2"></div>
                    <button type="button" id="addItemBtn"
                            class="text-xs font-medium text-blue-700 mt-2 inline-flex items-center gap-1">
                        <i class="ti ti-plus text-sm"></i> Adicionar item
                    </button>
                </div>

                <div id="itensOsPreview" class="hidden mt-2 border border-gray-200 rounded-lg p-3">
                    <p class="text-xs text-gray-500 mb-2">Itens da OS selecionada:</p>
                    <ul id="itensOsPreviewList" class="text-sm text-gray-700 space-y-1"></ul>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <p class="text-sm text-gray-600">Total: <span id="totalPreview" class="font-semibold text-gray-900">R$ 0,00</span></p>
                    <button type="submit"
                            class="text-white text-sm font-medium px-4 py-2.5 rounded-lg"
                            style="background:#0c1f33;">Registrar fiado</button>
                </div>
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-sm font-semibold text-gray-700">Fiados registrados</h2>
            </div>
            <div class="divide-y">
                @forelse ($fiados as $fiado)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50">
                        <a href="{{ route('fiado.show', $fiado) }}" class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $fiado->cliente->nome }}</p>
                            <p class="text-xs text-gray-500">
                                @if ($fiado->itens->count())
                                    {{ $fiado->itens->pluck('descricao')->implode(', ') }}
                                @else
                                    {{ $fiado->descricao ?? 'Sem descrição' }}
                                @endif
                                · {{ \Carbon\Carbon::parse($fiado->data)->format('d/m/Y') }}
                                @if ($fiado->ordemServico)
                                    · OS #{{ $fiado->ordemServico->id }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Pago: <span class="text-green-700 font-medium">R$ {{ number_format($fiado->valorPago(), 2, ',', '.') }}</span>
                                &nbsp;·&nbsp;
                                Restante: <span class="text-red-600 font-medium">R$ {{ number_format($fiado->valorRestante(), 2, ',', '.') }}</span>
                            </p>
                        </a>
                        <div class="text-right flex items-center gap-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">R$ {{ number_format($fiado->valor_total, 2, ',', '.') }}</p>
                                @if ($fiado->status === 'pago')
                                    <span class="text-xs text-green-700 bg-green-50 px-2 py-0.5 rounded-full">Pago</span>
                                @elseif ($fiado->status === 'parcial')
                                    <span class="text-xs text-yellow-700 bg-yellow-50 px-2 py-0.5 rounded-full">Parcial</span>
                                @else
                                    <span class="text-xs text-red-600 bg-red-50 px-2 py-0.5 rounded-full">Pendente</span>
                                @endif
                            </div>
                            <form action="{{ route('fiado.destroy', $fiado) }}" method="POST" onsubmit="return confirm('Excluir este fiado? Essa ação não pode ser desfeita.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium px-2 py-1">Excluir</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-8 text-sm text-gray-500 text-center">Nenhum fiado registrado ainda.</p>
                @endforelse
            </div>
        </div>

    </div>

    <script>
        const produtosFiadoData = [
            @foreach ($produtos as $produto)
                { id: {{ $produto->id }}, codigo: "{{ $produto->codigo }}", nome: "{{ addslashes($produto->nome) }}", preco: {{ $produto->preco_venda }} },
            @endforeach
        ];

        const tabManualBtn = document.getElementById('tabManualBtn');
        const tabOsBtn = document.getElementById('tabOsBtn');
        const clienteWrapper = document.getElementById('clienteWrapper');
        const osWrapper = document.getElementById('osWrapper');
        const itensManualBox = document.getElementById('itensManualBox');
        const itensOsPreview = document.getElementById('itensOsPreview');
        const itensOsPreviewList = document.getElementById('itensOsPreviewList');
        const itensList = document.getElementById('itensList');
        const addItemBtn = document.getElementById('addItemBtn');
        const totalPreview = document.getElementById('totalPreview');
        const clienteSelect = document.getElementById('clienteSelect');
        const osSelect = document.getElementById('osSelect');
        const ordemServicoIdInput = document.getElementById('ordemServicoIdInput');

        let itemRowCount = 0;

        function formatBRL(v) {
            return 'R$ ' + Number(v).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function reindexarLinhas() {
            const linhas = itensList.querySelectorAll('[data-row]');
            linhas.forEach((row, idx) => {
                row.querySelector('.item-descricao').setAttribute('name', `itens[${idx}][descricao]`);
                row.querySelector('.item-valor').setAttribute('name', `itens[${idx}][valor]`);
            });
        }

        function addItemRow() {
            itemRowCount++;
            const idx = itensList.children.length;
            const row = document.createElement('div');
            row.className = 'flex gap-2 items-start mb-2';
            row.setAttribute('data-row', itemRowCount);
            row.innerHTML = `
                <div class="relative flex-1">
                    <input type="text" placeholder="Buscar produto por código ou nome..." autocomplete="off"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500 item-busca">
                    <input type="hidden" name="itens[${idx}][descricao]" class="item-descricao">
                    <div class="item-lista absolute z-10 bg-white border border-gray-200 rounded-lg shadow-md mt-1 w-full max-h-56 overflow-y-auto hidden"></div>
                </div>
                <input type="number" step="0.01" name="itens[${idx}][valor]" placeholder="Valor"
                       class="w-32 rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500 item-valor" autocomplete="off">
                <button type="button" class="text-red-600 text-sm px-2 removeItemBtn">✕</button>
            `;
            itensList.appendChild(row);

            const inputBusca = row.querySelector('.item-busca');
            const inputDescricao = row.querySelector('.item-descricao');
            const inputValor = row.querySelector('.item-valor');
            const listaDiv = row.querySelector('.item-lista');

            inputBusca.addEventListener('input', function () {
                const termo = this.value.toLowerCase();
                inputDescricao.value = '';

                if (termo.length === 0) {
                    listaDiv.classList.add('hidden');
                    return;
                }

                const filtrados = produtosFiadoData.filter(p =>
                    p.codigo.toLowerCase().includes(termo) || p.nome.toLowerCase().includes(termo)
                );

                listaDiv.innerHTML = filtrados.length === 0
                    ? '<div class="px-3 py-2 text-sm text-gray-400">Nenhum produto encontrado</div>'
                    : filtrados.map(p => `
                        <div class="px-3 py-2 text-sm hover:bg-gray-50 cursor-pointer"
                             data-codigo="${p.codigo}" data-nome="${p.nome.replace(/"/g, '&quot;')}" data-preco="${p.preco}">
                            <strong>${p.codigo}</strong> — ${p.nome} <span class="text-gray-400">(R$ ${Number(p.preco).toFixed(2).replace('.', ',')})</span>
                        </div>
                    `).join('');

                listaDiv.classList.remove('hidden');
            });

            listaDiv.addEventListener('click', function (e) {
                const alvo = e.target.closest('[data-nome]');
                if (!alvo) return;
                const codigo = alvo.getAttribute('data-codigo');
                const nome = alvo.getAttribute('data-nome');
                const preco = alvo.getAttribute('data-preco');

                inputDescricao.value = nome;
                inputBusca.value = codigo + ' — ' + nome;
                inputValor.value = preco;
                listaDiv.classList.add('hidden');
                recalcularTotalManual();
            });

            document.addEventListener('click', function (e) {
                if (!inputBusca.contains(e.target) && !listaDiv.contains(e.target)) {
                    listaDiv.classList.add('hidden');
                }
            });

            row.querySelector('.removeItemBtn').addEventListener('click', () => {
                row.remove();
                reindexarLinhas();
                recalcularTotalManual();
            });
            inputValor.addEventListener('input', recalcularTotalManual);
        }

        function recalcularTotalManual() {
            let total = 0;
            document.querySelectorAll('.item-valor').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            totalPreview.textContent = formatBRL(total);
        }

        addItemBtn.addEventListener('click', () => addItemRow());
        addItemRow(); // primeira linha vazia

        function setModoManual() {
            tabManualBtn.classList.add('bg-[#0c1f33]', 'text-white');
            tabManualBtn.classList.remove('bg-gray-100', 'text-gray-700');
            tabOsBtn.classList.remove('bg-[#0c1f33]', 'text-white');
            tabOsBtn.classList.add('bg-gray-100', 'text-gray-700');

            clienteWrapper.classList.remove('hidden');
            osWrapper.classList.add('hidden');
            itensManualBox.classList.remove('hidden');
            itensOsPreview.classList.add('hidden');

            clienteSelect.setAttribute('required', 'required');
            osSelect.value = '';
            ordemServicoIdInput.value = '';
            clienteSelect.disabled = false;

            document.querySelectorAll('#itensList input').forEach(input => {
                input.disabled = false;
            });

            recalcularTotalManual();
        }

        function setModoOs() {
            tabOsBtn.classList.add('bg-[#0c1f33]', 'text-white');
            tabOsBtn.classList.remove('bg-gray-100', 'text-gray-700');
            tabManualBtn.classList.remove('bg-[#0c1f33]', 'text-white');
            tabManualBtn.classList.add('bg-gray-100', 'text-gray-700');

            osWrapper.classList.remove('hidden');
            itensManualBox.classList.add('hidden');
            itensOsPreview.classList.remove('hidden');

            clienteSelect.removeAttribute('required');

            document.querySelectorAll('#itensList input').forEach(input => {
                input.disabled = true;
            });
        }

        tabManualBtn.addEventListener('click', setModoManual);
        tabOsBtn.addEventListener('click', setModoOs);

        osSelect.addEventListener('change', async function () {
            const osId = this.value;
            if (!osId) {
                ordemServicoIdInput.value = '';
                itensOsPreviewList.innerHTML = '';
                totalPreview.textContent = formatBRL(0);
                return;
            }
            ordemServicoIdInput.value = osId;

            const clienteId = this.selectedOptions[0].dataset.cliente;
            clienteSelect.value = clienteId;

            const resp = await fetch(`/fiado/os/${osId}/json`);
            const data = await resp.json();

            itensOsPreviewList.innerHTML = '';
            data.itens.forEach(item => {
                const li = document.createElement('li');
                li.textContent = `${item.descricao} — ${formatBRL(item.valor)}`;
                itensOsPreviewList.appendChild(li);
            });

            totalPreview.textContent = formatBRL(data.total);
        });

        document.getElementById('fiadoForm').addEventListener('submit', function (e) {
            if (!ordemServicoIdInput.value) {
                // modo manual: remove linhas vazias antes de enviar
                document.querySelectorAll('#itensList > div').forEach(row => {
                    const desc = row.querySelector('.item-descricao').value.trim();
                    const val = row.querySelector('.item-valor').value.trim();
                    if (!desc || !val) row.remove();
                });
                reindexarLinhas();
                if (itensList.children.length === 0) {
                    e.preventDefault();
                    alert('Adicione pelo menos um item com descrição e valor.');
                }
            }
        });

        setModoManual();
    </script>
</x-app-layout>
