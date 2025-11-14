{{-- resources/views/admin/users/create.blade.php --}}
<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 space-y-4">
        <h1 class="text-xl font-semibold text-gray-800">
            Nuevo usuario
        </h1>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @include('admin.users._form')
            </form>
        </div>
    </div>
</x-app-layout>
