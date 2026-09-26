<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google" content="notranslate">

    <title>{{ config('app.name', 'Cascavel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="flex min-h-screen">

        <aside class="w-56 flex flex-col shrink-0" style="background: #0c1f33;">
            <div class="px-4 py-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg overflow-hidden bg-white p-0.5">
                        <img src="{{ asset('img/logo.png') }}" alt="Cascavel" class="w-full h-full object-contain rounded">
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white leading-tight">Cascavel</p>
                        <p class="text-[10.5px] leading-tight" style="color: #7a93ab;">Oficina e auto elétrica</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-3 space-y-0.5">
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
                        ['route' => 'relatorios.faturamento', 'icon' => 'ti-chart-bar', 'label' => 'Relatórios'],
                        ['route' => 'fiado.index', 'icon' => 'ti-notebook', 'label' => 'Fiado'],
                    ];
                @endphp

                @foreach ($links as $link)
                    @php
                        $ativo = request()->routeIs($link['route']) || request()->routeIs(str($link['route'])->before('.').'.*');
                    @endphp
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13.5px] transition-colors"
                       style="{{ $ativo ? 'background:#16324d;color:#f1efe8;' : 'color:#9db3c6;' }}">
                        <i class="ti {{ $link['icon'] }} text-base" style="{{ $ativo ? 'color:#85B7EB;' : '' }}"></i>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="px-3 py-4" style="border-top: 0.5px solid #1d3a54;">
                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center gap-2">
                        <div class="w-6.5 h-6.5 rounded-full flex items-center justify-center text-[11px] font-medium"
                             style="background:#16324d; border:0.5px solid #234a6b; color:#85B7EB; width:26px; height:26px;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <span class="text-[12.5px]" style="color:#9db3c6;">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="color:#7a93ab;" class="hover:text-white" title="Sair">
                            <i class="ti ti-logout text-sm">sair</i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

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
