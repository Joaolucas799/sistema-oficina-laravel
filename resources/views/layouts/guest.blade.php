<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cascavel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes floatUp {
            0%   { transform: translateY(0px); }
            50%  { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(133, 183, 235, 0.35); }
            50%      { box-shadow: 0 0 0 12px rgba(133, 183, 235, 0); }
        }
        .bg-animated {
            background: linear-gradient(120deg, #0c1f33 0%, #16324d 35%, #0c1f33 70%, #1d3a54 100%);
            background-size: 300% 300%;
            animation: gradientShift 14s ease infinite;
        }
        .float-icon { animation: floatUp 3.5s ease-in-out infinite; }
        .fade-in { opacity: 0; animation: fadeInUp 0.7s ease forwards; }
        .fade-in-1 { animation-delay: 0.05s; }
        .fade-in-2 { animation-delay: 0.15s; }
        .fade-in-3 { animation-delay: 0.25s; }
        .fade-in-4 { animation-delay: 0.35s; }
        .glow-pulse { animation: pulseGlow 2.5s ease-in-out infinite; }
        .grid-pattern {
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
        }
        .feature-row { transition: transform 0.2s ease, background 0.2s ease; }
        .feature-row:hover { transform: translateX(4px); background: rgba(255,255,255,0.05); }
        .form-side { animation: fadeInUp 0.6s ease forwards; }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">

        {{-- Lado esquerdo: marca --}}
        <div class="hidden lg:flex w-1/2 flex-col justify-between p-12 bg-animated grid-pattern relative overflow-hidden">

            <div class="flex items-center gap-3 fade-in fade-in-1">
                <div class="w-11 h-11 rounded-xl overflow-hidden float-icon glow-pulse bg-white p-1">
                    <img src="{{ asset('img/logo.png') }}" alt="Cascavel" class="w-full h-full object-contain rounded-lg">
                </div>
                <div>
                    <p class="text-base font-medium text-white">Cascavel</p>
                    <p class="text-xs" style="color:#7a93ab;">Oficina e auto elétrica</p>
                </div>
            </div>

            <div class="fade-in fade-in-2">
                <p class="text-2xl font-medium text-white leading-snug max-w-sm mb-2">
                    Gestão completa de estoque, clientes e ordens de serviço, tudo em um só lugar.
                </p>
                <p class="text-sm mb-8" style="color:#7a93ab;">
                    Controle peças, veículos e o financeiro da sua oficina sem complicação.
                </p>

                <div class="space-y-2 max-w-sm">
                    @php
                        $recursos = [
                            ['label' => 'Ordens de Serviço', 'desc' => 'Abertura, peças e serviços'],
                            ['label' => 'Estoque', 'desc' => 'Entradas, saídas e alertas'],
                            ['label' => 'Contas a Pagar', 'desc' => 'Fornecedores em dia'],
                            ['label' => 'Relatórios', 'desc' => 'Faturamento mês a mês'],
                        ];
                    @endphp

                    @foreach ($recursos as $r)
                        <div class="feature-row flex items-center gap-3 rounded-xl px-3 py-2.5"
                             style="border: 0.5px solid #234a6b;">
                            <div class="w-8 h-8 rounded-lg overflow-hidden shrink-0 bg-white p-0.5">
                                <img src="{{ asset('img/logo.png') }}" alt="Cascavel" class="w-full h-full object-contain rounded">
                            </div>
                            <div>
                                <p class="text-sm font-medium text-white leading-tight">{{ $r['label'] }}</p>
                                <p class="text-[11px] leading-tight" style="color:#7a93ab;">{{ $r['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="text-xs fade-in fade-in-4" style="color:#4a637a;">© {{ date('Y') }} Cascavel Oficina e Auto Elétrica</p>
        </div>

        {{-- Lado direito: formulário --}}
        <div class="flex-1 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-sm form-side">
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div class="w-11 h-11 rounded-xl overflow-hidden bg-white p-1" style="border:0.5px solid #e5e7eb;">
                        <img src="{{ asset('img/logo.png') }}" alt="Cascavel" class="w-full h-full object-contain rounded-lg">
                    </div>
                    <p class="text-base font-medium text-gray-900">Cascavel</p>
                </div>

                {{ $slot }}
            </div>
        </div>

    </div>
</body>
</html>