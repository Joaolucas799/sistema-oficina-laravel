<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cascavel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex min-h-screen">

        {{-- Menu lateral azul --}}
        <aside class="w-64 bg-blue-900 text-white flex flex-col shrink-0">
            <div class="px-6 py-6">
                <div class="flex items-center gap-2">
                    <i class="ti ti-tool text-2xl"></i>
                    <span class="text-xl font-semibold">Cascavel</span>
                </div>
                <p class="text-xs text-blue-200 mt-1">Oficina e auto elétrica</p>
            </div>

            <nav class="flex-1 px-3 space-y-1">
                @php
                    $links = [
                        ['route' => 'dashboard', 'icon' => 'ti-layout-dashboard', 'label' => 'Dashboard'],
                        ['route' => 'clientes.index', 'icon' => 'ti-users', 'label' => 'Clientes'],
                        ['route' => 'produtos.index', 'icon' => 'ti-box', 'label' => 'Produtos'],
                        ['route' => 'os.index', 'icon' => 'ti-tools', 'label' => 'Ordens de serviço'],
                        ['route' => 'categorias.index', 'icon' => 'ti-category', 'label' => 'Categorias'],
                        ['route' => 'fornecedores.index', 'icon' => 'ti-building-warehouse', 'label' => 'Fornecedores'],
                        ['route' => 'servicos.index', 'icon' => 'ti-settings', 'label' => 'Serviços'],
                        ['route' => 'entradas.index', 'icon' => 'ti-arrow-down', 'label' => 'Entradas'],
                        ['route' => 'saidas.index', 'icon' => 'ti-arrow-up', 'label' => 'Saídas'],
                        ['route' => 'contas-pagar.index', 'icon' => 'ti-cash', 'label' => 'Contas a Pagar'],
                    ];

                @endphp
                

                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                              {{ request()->routeIs($link['route']) || request()->routeIs(str($link['route'])->before('.').'.*')
                                    ? 'bg-white text-blue-900 font-medium'
                                    : 'text-blue-100 hover:bg-blue-800' }}">
                        <i class="ti {{ $link['icon'] }} text-lg"></i>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="px-3 py-4 border-t border-blue-800">
                <div class="flex items-center justify-between px-3">
                    <span class="text-sm text-blue-100">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-blue-200 hover:text-white" title="Sair">
                            <i class="ti ti-logout"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Área de conteúdo --}}
        <div class="flex-1 flex flex-col min-w-0">
            @if (isset($header))
                <header class="bg-white border-b px-8 py-5">
                    {{ $header }}
                </header>
            @endif

            <main class="flex-1 p-8">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>
</html>