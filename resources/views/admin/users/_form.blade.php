@csrf

<div class="space-y-4">

    <div>
        <x-input-label for="name" value="Nombre" />
        <x-text-input id="name" type="text" name="name"
            class="mt-1 block w-full"
            value="{{ old('name', $user->name ?? '') }}" required />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="email" value="Correo electrónico" />
        <x-text-input id="email" type="email" name="email"
            class="mt-1 block w-full"
            value="{{ old('email', $user->email ?? '') }}" required />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" type="password" name="password"
                class="mt-1 block w-full"
                @if(!isset($user)) required @endif />
            @if(isset($user))
                <p class="text-xs text-gray-400 mt-1">
                    Deja en blanco para mantener la contraseña actual.
                </p>
            @endif
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>
        <div>
            <x-input-label for="password_confirmation" value="Confirmar contraseña" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                class="mt-1 block w-full"
                @if(!isset($user)) required @endif />
        </div>
    </div>

    <div class="flex items-center gap-2">
        <input id="is_admin" type="checkbox" name="is_admin" value="1"
               class="rounded border-gray-300 text-emerald-600"
               @checked(old('is_admin', $user->is_admin ?? false))>
        <label for="is_admin" class="text-sm text-gray-700">
            Usuario administrador
        </label>
    </div>

</div>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ route('admin.users.index') }}"
       class="inline-flex items-center px-3 py-1.5 rounded-md text-sm border border-gray-300 text-gray-700 hover:bg-gray-50">
        Cancelar
    </a>

    <x-primary-button>
        Guardar
    </x-primary-button>
</div>
