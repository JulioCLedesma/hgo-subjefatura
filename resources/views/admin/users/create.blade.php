<x-app-layout>
    <div class="max-w-3xl mx-auto py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            Nuevo usuario
        </h1>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Nombre --}}
            <div>
                <x-input-label for="name" value="Nombre completo" />
                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ old('name') }}"
                    required
                    autofocus
                />
                <x-input-error for="name" class="mt-1" />
            </div>

            {{-- Correo electrónico --}}
            <div>
                <x-input-label for="email" value="Correo electrónico" />
                <x-text-input
                    id="email"
                    name="email"
                    type="email"
                    class="mt-1 block w-full"
                    value="{{ old('email') }}"
                    required
                />
                <x-input-error for="email" class="mt-1" />
            </div>

            {{-- Contraseña --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="password" value="Contraseña" />
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                    <x-input-error for="password" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Confirmar contraseña" />
                    <x-text-input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                </div>
            </div>

            {{-- Es administrador --}}
            <div>
                <label class="inline-flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="is_admin"
                        value="1"
                        class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                        {{ old('is_admin') ? 'checked' : '' }}
                    >
                    <span class="text-sm text-gray-700">
                        Conceder permisos de administrador
                    </span>
                </label>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.users.index') }}"
                   class="text-sm text-gray-600 hover:text-gray-800">
                    Cancelar
                </a>

                <x-primary-button>
                    Guardar usuario
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
