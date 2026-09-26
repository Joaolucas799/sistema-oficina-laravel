<x-app-layout>
    <x-slot name="header">
        <p class="text-[11px] tracking-wide text-gray-400 uppercase mb-1">Clientes</p>
        <h1 class="text-xl font-medium text-gray-900">{{ $cliente->nome }}</h1>
    </x-slot>

    <div class="max-w-3xl space-y-6">

        @if (session('sucesso'))
            <div class="p-4 bg-green-50 text-green-700 rounded-lg text-sm">{{ session('sucesso') }}</div>
        @endif

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <p class="text-sm font-medium text-gray-700 mb-3">Dados do cliente</p>
            <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                <p>Telefone: <span class="text-gray-900">{{ $cliente->telefone ?? '—' }}</span></p>
                <p>E-mail: <span class="text-gray-900">{{ $cliente->email ?? '—' }}</span></p>
                <p>CPF/CNPJ: <span class="text-gray-900">{{ $cliente->cpf_cnpj ?? '—' }}</span></p>
            </div>
            <a href="{{ route('clientes.edit', $cliente) }}" class="text-sm mt-3 inline-block" style="color:#185FA5;">Editar dados</a>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <p class="text-sm font-medium text-gray-700 mb-4">Veículos</p>

            <div class="border border-gray-200 rounded-xl overflow-hidden mb-5">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-2.5 text-[11px] uppercase tracking-wide text-gray-400 font-normal">Placa</th>
                            <th class="px-4 py-2.5 text-[11px] uppercase tracking-wide text-gray-400 font-normal">Marca</th>
                            <th class="px-4 py-2.5 text-[11px] uppercase tracking-wide text-gray-400 font-normal">Modelo</th>
                            <th class="px-4 py-2.5 text-[11px] uppercase tracking-wide text-gray-400 font-normal">Ano</th>
                            <th class="px-4 py-2.5 text-[11px] uppercase tracking-wide text-gray-400 font-normal">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cliente->veiculos as $veiculo)
                            <tr class="border-t border-gray-100">
                                <td class="px-4 py-3">{{ $veiculo->placa }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $veiculo->marca }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $veiculo->modelo }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $veiculo->ano ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('veiculos.destroy', $veiculo) }}" method="POST"
                                          onsubmit="return confirm('Remover este veículo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm" style="color:#A32D2D;">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($cliente->veiculos->isEmpty())
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Nenhum veículo cadastrado.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <p class="text-sm font-medium text-gray-700 mb-3">Adicionar veículo</p>
            <form action="{{ route('veiculos.store', $cliente) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <input type="text" name="placa" placeholder="Placa" value="{{ old('placa') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('placa') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <input type="text" name="marca" placeholder="Marca" value="{{ old('marca') }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                <input type="text" name="modelo" placeholder="Modelo" value="{{ old('modelo') }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                <input type="number" name="ano" placeholder="Ano" value="{{ old('ano') }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">

                <button type="submit"
                        class="w-full text-white text-sm font-medium px-4 py-2.5 rounded-lg" style="background:#3B6D11;">
                    + Adicionar veículo
                </button>
            </form>
        </div>

    </div>
</x-app-layout>