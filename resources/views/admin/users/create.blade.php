<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear nuevo usuario
            </h2>

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
                    Datos del usuario
                </h3>
                <p class="text-xs text-gray-500 mb-4">
                    Registra usuarios que podrán acceder al sistema. Marca la opción de administrador
                    solo para personal con funciones de coordinación o jefatura.
                </p>

                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                    @include('admin.users._form', [
                        'user' => null,
                        'submitLabel' => 'Crear usuario',
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
