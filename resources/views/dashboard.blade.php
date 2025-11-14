{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <div class="min-h-screen bg-slate-50">

        {{-- Hero con imagen de fondo --}}
        <div class="relative overflow-hidden">
            {{-- Imagen de fondo --}}
            <div class="absolute inset-0">
                <img
                    src="{{ asset('images/zoquipan.jpg') }}"
                    alt="Hospital General de Occidente"
                    class="w-full h-full object-cover opacity-20"
                >
            </div>

            {{-- Capa de color para dar contraste --}}
            <div class="absolute inset-0 bg-gradient-to-r from-sky-900/70 via-sky-800/60 to-slate-900/60"></div>

            {{-- Contenido del hero --}}
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12 lg:py-14">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                    <div class="space-y-2 text-white">
                        <p class="text-sm uppercase tracking-[0.2em] text-sky-100/80">
                            Subjefatura de Enfermería · HGO
                        </p>

                        <h2 class="font-semibold text-2xl sm:text-3xl lg:text-4xl leading-tight">
                            Welcome{{ auth()->user() && Str::endsWith(auth()->user()->name, 'a') ? 'a' : '' }},
                            <span class="font-bold">
                                {{ auth()->user()->name ?? 'Usuario' }}
                            </span>
                        </h2>

                        <p class="text-sm sm:text-base text-sky-100/90 max-w-2xl">
                            Panel para la gestión diaria de productividad, ocupación hospitalaria
                            y recurso humano de enfermería. Desde aquí puedes capturar, analizar
                            y generar informes consolidados del HGO.
                        </p>
                    </div>

                    {{-- Tarjeta resumen lateral (placeholder para futuro) --}}
                    <div class="bg-white/90 backdrop-blur-sm border border-white/50 rounded-2xl p-4 sm:p-5 shadow-lg w-full max-w-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-500">
                                    Turno activo
                                </p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                    {{-- Aquí en un futuro puedes poner el turno detectado o seleccionado --}}
                                    Matutino / Vespertino
                                </p>
                            </div>
                            {{-- Ícono de estetoscopio / salud --}}
                            <div class="shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" class="h-7 w-7 text-sky-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                          d="M8 3v6a4 4 0 1 1-8 0V3m8 0h3m-3 0H5m3 0a3 3 0 1 0 6 0m0 0h3m-3 0h-3m9 9a3 3 0 0 1-3 3h-1a4 4 0 0 1-4-4v-2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                          d="M19 14a3 3 0 1 1 3 3h-1.5M19 20v-3"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-slate-500">
                            Utiliza los accesos rápidos para registrar la productividad del día
                            y generar informes por servicio, turno y periodo.
                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- Contenido principal --}}
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                {{-- Encabezado secundario --}}
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-lg text-slate-800">
                            Módulos principales
                        </h3>
                        <p class="text-sm text-slate-500">
                            Selecciona un módulo para iniciar la captura o consultar información consolidada.
                        </p>
                    </div>
                </div>

                {{-- Grid de accesos rápidos --}}
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                    {{-- Tarjeta: Captura diaria --}}
                    <a href="{{ route('captura.index') }}"
                       class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col justify-between hover:border-sky-300 hover:shadow-md hover:-translate-y-0.5 transition transform">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0 mt-1">
                                {{-- Icono calendario / clipboard --}}
                                <div class="h-10 w-10 rounded-xl bg-sky-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" class="h-6 w-6 text-sky-700">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                              d="M8 7h8m-8 4h5m-8 8h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1.5l-.6-1.2A1 1 0 0 0 13.9 3h-3.8a1 1 0 0 0-.9.8L8.6 5H7A2 2 0 0 0 5 7v10a2 2 0 0 0 2 2Z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800 text-lg mb-1">
                                    Captura diaria
                                </h4>
                                <p class="text-sm text-slate-500">
                                    Registrar productividad por servicio de hospitalización, TOCO,
                                    quirófanos, consulta externa, autoclaves, defunciones y recurso humano
                                    por fecha y turno.
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 text-sm font-semibold text-sky-700 flex items-center gap-1">
                            Ir a captura
                            <span aria-hidden="true">→</span>
                        </div>
                    </a>

                    {{-- Tarjeta: Informes y dashboard --}}
                    <a href="{{ route('informes.index') }}"
                       class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col justify-between hover:border-emerald-300 hover:shadow-md hover:-translate-y-0.5 transition transform">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0 mt-1">
                                {{-- Icono estadísticas / gráfico --}}
                                <div class="h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" class="h-6 w-6 text-emerald-700">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                              d="M4 19V5m0 14h16M8 15l2.5-3.5L13 14l3.5-6L20 11"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800 text-lg mb-1">
                                    Informes y estadísticas
                                </h4>
                                <p class="text-sm text-slate-500">
                                    Generar informes por rango de fechas y turno, revisar ocupación por
                                    servicio, productividad y estadísticas de recurso humano. Exportar a PDF
                                    con formato institucional.
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 text-sm font-semibold text-emerald-700 flex items-center gap-1">
                            Generar informes
                            <span aria-hidden="true">→</span>
                        </div>
                    </a>

                    {{-- Tarjeta: Gestión de usuarios (solo admin) --}}
                    @if(auth()->user() && auth()->user()->is_admin)
                        <a href="{{ route('admin.users.index') }}"
                           class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col justify-between hover:border-indigo-300 hover:shadow-md hover:-translate-y-0.5 transition transform">
                            <div class="flex items-start gap-3">
                                <div class="shrink-0 mt-1">
                                    {{-- Icono usuarios --}}
                                    <div class="h-10 w-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" class="h-6 w-6 text-indigo-700">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                  d="M15 19a3 3 0 0 0-3-3H6a3 3 0 0 0-3 3"/>
                                            <circle cx="9" cy="9" r="3" stroke-width="1.7"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                  d="M19 11a2 2 0 1 0-2-2"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                  d="M21 19a3 3 0 0 0-2.4-2.9"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-800 text-lg mb-1">
                                        Gestión de usuarios
                                    </h4>
                                    <p class="text-sm text-slate-500">
                                        Administrar cuentas de acceso al sistema: crear y editar usuarios,
                                        restablecer contraseñas y gestionar permisos de administración.
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4 text-sm font-semibold text-indigo-700 flex items-center gap-1">
                                Abrir gestión de usuarios
                                <span aria-hidden="true">→</span>
                            </div>
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
