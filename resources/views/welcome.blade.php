<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Cascavel') }} — Oficina e Auto Elétrica</title>

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
        .feature-card { transition: transform 0.25s ease, background 0.25s ease; }
        .feature-card:hover { transform: translateY(-4px); background: rgba(255,255,255,0.06); }
        .cta-btn { transition: transform 0.15s ease, background 0.2s ease; }
        .cta-btn:hover { transform: translateY(-2px); }
        .grid-pattern {
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="bg-animated grid-pattern min-h-screen flex flex-col items-center justify-center px-6 py-12 relative overflow-hidden">

        <div class="w-full max-w-4xl">

            {{-- Logo + título --}}
            <div class="flex flex-col items-center text-center mb-10 fade-in fade-in-1">
                <div class="w-20 h-20 rounded-2xl overflow-hidden mb-5 float-icon glow-pulse bg-white p-1">
                    <img src="{{ asset('img/logo.png') }}" alt="Cascavel" class="w-full h-full object-contain rounded-xl">
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">Cascavel</h1>
                <p class="text-sm mt-2" style="color:#85B7EB;">Oficina e Auto Elétrica</p>
            </div>

            <div class="grid md:grid-cols-5 gap-6 items-start">

                {{-- Card de login --}}
                <div class="md:col-span-2 fade-in fade-in-2">
                    <div class="bg-white rounded-2xl p-7 shadow-2xl">
                        <p class="text-[11px] uppercase tracking-wide text-gray-400 mb-1">Acesso ao sistema</p>
                        <h2 class="text-lg font-semibold text-gray-900 mb-5">Bem-vindo de volta</h2>

                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="cta-btn w-full flex items-center justify-center gap-2 text-white text-sm font-medium px-4 py-3 rounded-lg"
                               style="background:#0c1f33;" onmouseover="this.style.background='#16324d'" onmouseout="this.style.background='#0c1f33'">
                                <i class="ti ti-layout-dashboard text-base"></i>
                                Ir para o painel
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="cta-btn w-full flex items-center justify-center gap-2 text-white text-sm font-medium px-4 py-3 rounded-lg"
                               style="background:#0c1f33;" onmouseover="this.style.background='#16324d'" onmouseout="this.style.background='#0c1f33'">
                                <i class="ti ti-login text-base"></i>
                                Entrar
                            </a>
                        @endauth

                        <p class="text-[11px] text-gray-400 text-center mt-5">
                            Acesso restrito à equipe Cascavel
                        </p>
                    </div>
                </div>

                {{-- Grid de recursos --}}
                <div class="md:col-span-3 grid grid-cols-2 gap-3">
                    @php
                        $recursos = [
                            ['label' => 'Ordens de Serviço', 'desc' => 'Abertura, peças e serviços'],
                            ['label' => 'Estoque', 'desc' => 'Entradas, saídas e alertas'],
                            ['label' => 'Contas a Pagar', 'desc' => 'Fornecedores em dia'],
                            ['label' => 'Relatórios', 'desc' => 'Faturamento mês a mês'],
                        ];
                    @endphp

                    @foreach ($recursos as $i => $r)
                        <div class="feature-card fade-in fade-in-{{ $i + 1 }} rounded-xl p-4"
                             style="background: rgba(255,255,255,0.03); border: 0.5px solid #234a6b;">
                            <div class="w-8 h-8 rounded-lg overflow-hidden mb-3 bg-white p-0.5">
                                <img src="{{ asset('img/logo.png') }}" alt="Cascavel" class="w-full h-full object-contain rounded">
                            </div>
                            <p class="text-sm font-medium text-white">{{ $r['label'] }}</p>
                            <p class="text-[11px] mt-0.5" style="color:#7a93ab;">{{ $r['desc'] }}</p>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        <p class="text-[11px] mt-10 fade-in fade-in-4" style="color:#4a6178;">
            Sistema de gestão &middot; v{{ Illuminate\Foundation\Application::VERSION }}
        </p>
    </div>
</body>
</html>