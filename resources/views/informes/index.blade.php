<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 space-y-6">

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h2 class="font-bold text-xl mb-4">Generar Informe</h2>

            <form action="{{ route('informes.generate') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <x-input-label value="Desde" />
                        <input type="date" name="from" class="w-full border-gray-300 rounded-md" required>
                    </div>

                    <div>
                        <x-input-label value="Hasta" />
                        <input type="date" name="to" class="w-full border-gray-300 rounded-md" required>
                    </div>

                    <div>
                        <x-input-label value="Turno" />
                        <select name="shift" class="w-full border-gray-300 rounded-md" required>
                            <option value="M">Matutino</option>
                            <option value="V">Vespertino</option>
                            {{-- NUEVO: turno Ambos --}}
                            <option value="A">Ambos (Matutino + Vespertino)</option>
                        </select>
                        <p class="mt-1 text-[11px] text-gray-400">
                            Nota: en el informe, los pacientes se muestran como <strong>promedio diario por servicio</strong>.
                        </p>
                    </div>
                </div>
    <div class="mt-2">
        <label class="inline-flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="include_notes" value="1"
                   class="rounded border-gray-300 text-emerald-600"
                   {{ old('include_notes', true) ? 'checked' : '' }}>
            <span>Incluir notas de turno como anexo en el informe y PDF</span>
        </label>
    </div>
                <x-primary-button class="mt-4">
                    Generar informe
                </x-primary-button>
            </form>
        </div>

    </div>
</x-app-layout>
