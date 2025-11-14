<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de usuarios
            </h2>

            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-lg
                      bg-emerald-600 text-white hover:bg-emerald-700">
                + Nuevo usuario
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-4">

            {{-- Mensajes de estado --}}
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-2 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">
                            Usuarios del sistema
                        </h3>
                        <p class="text-xs text-gray-500">
                            Solo visibles para administradores. Desde aquí puedes crear, editar o desactivar usuarios.
                        </p>
                    </div>
                </div>

                <div class="p-4 overflow-x-auto">
                    @if($users->isEmpty())
                        <p class="text-sm text-gray-500">
                            No hay usuarios registrados.
                        </p>
                    @else
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs text-gray-500 uppercase tracking-wide border-b">
                                    <th class="py-2 pr-4">Nombre</th>
                                    <th class="py-2 pr-4">Correo</th>
                                    <th class="py-2 pr-4 text-center">Rol</th>
                                    <th class="py-2 pr-4 hidden md:table-cell">Creado</th>
                                    <th class="py-2 pr-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-50/60">
                                        <td class="py-2 pr-4">
                                            <div class="flex flex-col">
                                                <span class="font-medium text-gray-800">
                                                    {{ $user->name }}
                                                </span>
                                                <span class="text-[11px] text-gray-400 md:hidden">
                                                    {{ $user->email }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-2 pr-4 text-gray-700 hidden md:table-cell">
                                            {{ $user->email }}
                                        </td>
                                        <td class="py-2 pr-4 text-center">
                                            @if($user->is_admin)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                                    Admin
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                                    Usuario
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4 text-gray-500 text-xs hidden md:table-cell">
                                            {{ optional($user->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="py-2 pr-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.users.edit', $user) }}"
                                                   class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md
                                                          border border-gray-200 text-gray-700 hover:bg-gray-50">
                                                    Editar
                                                </a>

                                                {{-- Botón eliminar --}}
                                                @if(auth()->id() !== $user->id)
                                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('¿Deseas eliminar este usuario? Esta acción no se puede deshacer.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md
                                                                   border border-rose-200 text-rose-700 hover:bg-rose-50">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-[10px] text-gray-400 italic">
                                                        No puedes eliminar tu propio usuario
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
