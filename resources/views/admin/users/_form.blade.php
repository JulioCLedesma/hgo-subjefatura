@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Nombre --}}
    <div>
        <x-input-label for="name" value="Nombre" />
        <x-text-input
            id="name"
            type="text"
            name="name"
            class="mt-1 block w-full"
            value="{{ old('name', $user->name ?? '') }}"
            required
        />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    {{-- Correo --}}
    <div>
        <x-input-label for="email" value="Correo electrónico" />
        <x-text-input
            id="email"
            type="email"
            name="email"
            class="mt-1 block w-full"
            value="{{ old('email', $user->email ?? '') }}"
            required
        />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>

    {{-- Password --}}
    <div>
        <x-input-label for="password" value="Contraseña" />
        <x-text-input
            id="password"
            type="password"
            name="password"
            class="mt-1 block w-full"
            @if(!isset($user)) required @endif
        />
        <p class="text-[11px] text-gray-400 mt-1">
            @isset($user)
                Deja en blanco si no deseas cambiar la contraseña.
            @else
                Mínimo 8 caracteres.
            @endisset
        </p>
        <x-input-error :messages="$errors->get('password')" class="mt-1" />
    </div>

    {{-- Rol admin --}}
    <div class="flex items-center mt-6">
        <input
            id="is_admin"
            type="checkbox"
            name="is_admin"
            value="1"
            class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
            @checked(old('is_admin', $user->is_admin ?? false))
        >
        <label for="is_admin" class="ml-2 text-sm text-gray-700">
            Usuario administrador
        </label>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-2">
    <a href="{{ route('admin.users.index') }}"
       class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-lg
              border border-gray-200 text-gray-700 hover:bg-gray-50">
        Cancelar
    </a>

    <x-primary-button>
        {{ $submitLabel }}
    </x-primary-button>
</div>
