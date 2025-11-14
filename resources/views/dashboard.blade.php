{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Encabezado --}}
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Panel de Subjefatura de Enfermería
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Desde aquí puedes acceder a la captura diaria de productividad y a los informes consolidados.
                </p>
            </div>

            {{-- Grid de accesos rápidos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                {{-- Tarjeta: Captura diaria --}}
                <a href="{{ route('captura.index') }}"
                   class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 flex flex-col justify-between hover:border-indigo-300 hover:shadow-md transition">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg mb-1">
                            Captura diaria
                        </h3>
                        <p class="text-sm text-gray-500">
                            Registrar productividad por servicio, TOCO, quirófanos, consulta externa,
                            autoclaves, defunciones y recurso humano por fecha y turno.
                        </p>
                    </div>
                    <div class="mt-4 text-sm font-semibold text-indigo-600">
                        Ir a captura →
                    </div>
                </a>

                {{-- Tarjeta: Informes --}}
                <a href="{{ route('informes.index') }}"
                   class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 flex flex-col justify-between hover:border-indigo-300 hover:shadow-md transition">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg mb-1">
                            Informes y dashboard
                        </h3>
                        <p class="text-sm text-gray-500">
                            Generar informes por rango de fechas y turno, revisar ocupación por servicio,
                            productividad y estadísticas de recurso humano. Exportar a PDF.
                        </p>
                    </div>
                    <div class="mt-4 text-sm font-semibold text-indigo-600">
                        Ver informes →
                    </div>
                </a>

                {{-- Tarjeta opcional: futuro módulo --}}
                <div class="bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-5 flex flex-col justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-700 text-lg mb-1">
                            Próximamente
                        </h3>
                        <p class="text-sm text-gray-500">
                            Aquí puedes agregar después módulos como indicadores mensuales, comparativos por unidad
                            o reportes especiales para COCASEP.
                        </p>
                    </div>
                    <div class="mt-4 text-sm text-gray-400">
                        En desarrollo…
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
