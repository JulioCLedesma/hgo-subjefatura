<x-guest-layout>

    {{-- Fondo de pantalla completo --}}
    <style>
        body {
            background: url('{{ asset('images/zoquipan.jpg') }}') no-repeat center center fixed;
            background-size: cover;
        }

        .glass-card {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
    </style>

    <div class="min-h-screen flex flex-col justify-center items-center px-4">

        {{-- Tarjeta translúcida --}}
        <div class="w-full max-w-md glass-card rounded-2xl shadow-xl p-8 text-white">

            {{-- Logo opcional --}}
            <div class="text-center mb-6">
                <h1 class="text-xl font-bold drop-shadow">
                    Hospital General de Occidente – Subjefatura
                </h1>
                <p class="mt-1 text-sm opacity-80">
                    Acceso al sistema de productividad
                </p>
            </div>

            {{-- Formulario de login --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div>
                    <x-input-label class="text-white" for="email" value="Correo institucional" />
                    <x-text-input id="email" class="block mt-1 w-full bg-white/60 text-gray-900"
                                  type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error for="email" class="mt-2 text-red-200" />
                </div>

                {{-- Password --}}
                <div class="mt-4">
                    <x-input-label class="text-white" for="password" value="Contraseña" />
                    <x-text-input id="password" class="block mt-1 w-full bg-white/60 text-gray-900"
                                  type="password" name="password" required />
                    <x-input-error for="password" class="mt-2 text-red-200" />
                </div>

                {{-- Remember --}}
                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center text-white">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                               name="remember">
                        <span class="ml-2 text-sm">Recordarme</span>
                    </label>
                </div>

                {{-- Botón --}}
                <div class="flex items-center justify-end mt-6">
                    <x-primary-button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700">
                        Ingresar
                    </x-primary-button>
                </div>
            </form>
        </div>

        {{-- Pie --}}
        <p class="mt-6 text-white text-xs opacity-70 drop-shadow">
            © {{ date('Y') }} Subjefatura de Enfermería – HGO · Uso exclusivo para personal autorizado
        </p>

    </div>

</x-guest-layout>
