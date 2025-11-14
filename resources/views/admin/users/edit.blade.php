<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Editar usuario
                </h2>
                <p class="text-xs text-gray-500">
                    {{ $user->name }} · {{ $user->email }}
                </p>
            </div>

            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-lg
                      border border-gray-200 text-gray-700 hover:bg-gray-50">
                Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 text-lg mb-2">
                    Actualizar datos del usuario
                </h3>
                <p class="text-xs text-gray-500 mb-4">
                    Puedes actualizar nombre, correo, rol y contraseña (si es necesario).
                </p>

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                    @method('PUT')

                    @include('admin.users._form', [
                        'user' => $user,
                        'submitLabel' => 'Guardar cambios',
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
