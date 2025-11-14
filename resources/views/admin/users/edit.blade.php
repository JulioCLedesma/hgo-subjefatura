{{-- resources/views/admin/users/edit.blade.php --}}
<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 space-y-4">
        <h1 class="text-xl font-semibold text-gray-800">
            Editar usuario
        </h1>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @method('PUT')
                @include('admin.users._form', ['user' => $user])
            </form>
        </div>
    </div>
</x-app-layout>
