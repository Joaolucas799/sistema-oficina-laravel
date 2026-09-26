<x-guest-layout>
    <h2 class="text-2xl font-medium text-gray-900 mb-1">Entrar</h2>
    <p class="text-sm text-gray-500 mb-8">Acesse o painel da sua oficina.</p>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   autocomplete="username"
                   placeholder="seu@email.com"
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
            <input id="password" type="password" name="password" required
                   autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-900 focus:ring-blue-500">
                <span class="text-sm text-gray-600">Lembrar de mim</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full py-2.5 rounded-lg text-sm font-medium text-white transition-colors"
                style="background:#0c1f33;" onmouseover="this.style.background='#16324d'" onmouseout="this.style.background='#0c1f33'">
            Entrar
        </button>
    </form>
</x-guest-layout>