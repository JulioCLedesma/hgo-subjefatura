<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Productividad diaria de enfermería
            </h2>
            <span class="text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($date)->isoFormat('DD [de] MMMM YYYY') }} · Turno {{ $shift->name }}
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Barra de filtros: fecha + turno --}}
            <div class="bg-white shadow-sm rounded-xl p-4 border border-gray-100">
                <form method="GET" action="{{ route('captura.index') }}" class="flex flex-wrap items-end gap-4">

                    <div>
                        <x-input-label for="date" value="Fecha" />
                        <x-text-input id="date" type="date" name="date"
                                      value="{{ $date }}"
                                      class="mt-1 block" />
                    </div>

                    <div>
                        <x-input-label for="shift" value="Turno" />
                        <select id="shift" name="shift"
                                class="mt-1 block rounded-lg border-gray-300 text-sm">
                            <option value="M" @selected($shift->code === 'M')>Matutino</option>
                            <option value="V" @selected($shift->code === 'V')>Vespertino</option>
                        </select>
                    </div>

                    <div class="ml-auto">
                        <x-primary-button class="mt-6">
                            Actualizar vista
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Mensaje de estado --}}
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{-- SECCIÓN: GESTIÓN DE RECURSO HUMANO --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">
                            Gestión de Recurso Humano
                        </h3>
                        <p class="text-xs text-gray-500">
                            Asistencia y registro de incidencias por turno.
                        </p>
                    </div>
                    <span class="text-[11px] uppercase tracking-wide text-gray-400">
                        Personal de enfermería
                    </span>
                </div>

                <form method="POST" action="{{ route('captura.rrhh.save') }}" class="p-4 space-y-8">
                    @csrf
                    <input type="hidden" name="daily_shift_id" value="{{ $dailyShift->id }}">

                    {{-- ASISTENCIA --}}
                    <div>
                        <h4 class="font-semibold text-sm text-gray-800 mb-2">Asistencia</h4>

                        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-6 gap-4 text-sm">

                            <div>
                                <x-input-label for="subjefatura" value="Subjefatura" />
                                <x-text-input id="subjefatura" type="number" min="0"
                                    name="subjefatura"
                                    value="{{ old('subjefatura', $rrhh->subjefatura) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                            <div>
                                <x-input-label for="supervision" value="Supervisión" />
                                <x-text-input id="supervision" type="number" min="0"
                                    name="supervision"
                                    value="{{ old('supervision', $rrhh->supervision) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                            <div>
                                <x-input-label for="jefes_servicio" value="Jefes de servicio" />
                                <x-text-input id="jefes_servicio" type="number" min="0"
                                    name="jefes_servicio"
                                    value="{{ old('jefes_servicio', $rrhh->jefes_servicio) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                            <div>
                                <x-input-label for="enfermeria_general" value="Enfermería general" />
                                <x-text-input id="enfermeria_general" type="number" min="0"
                                    name="enfermeria_general"
                                    value="{{ old('enfermeria_general', $rrhh->enfermeria_general) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                            <div>
                                <x-input-label for="enfermeria_auxiliar" value="Auxiliares" />
                                <x-text-input id="enfermeria_auxiliar" type="number" min="0"
                                    name="enfermeria_auxiliar"
                                    value="{{ old('enfermeria_auxiliar', $rrhh->enfermeria_auxiliar) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                            <div>
                                <x-input-label for="pasantes" value="Pasantes" />
                                <x-text-input id="pasantes" type="number" min="0"
                                    name="pasantes"
                                    value="{{ old('pasantes', $rrhh->pasantes) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                        </div>
                    </div>

                    {{-- INCIDENCIAS --}}
                    <div class="pt-2 border-t border-gray-100">
                        <h4 class="font-semibold text-sm text-gray-800 mb-2">Incidencias</h4>

                        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-7 gap-4 text-sm">

                            <div>
                                <x-input-label for="descansos_obligatorios" value="Descansos obligatorios" />
                                <x-text-input id="descansos_obligatorios" type="number" min="0"
                                    name="descansos_obligatorios"
                                    value="{{ old('descansos_obligatorios', $rrhh->descansos_obligatorios) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                            <div>
                                <x-input-label for="incapacidades" value="Incapacidades" />
                                <x-text-input id="incapacidades" type="number" min="0"
                                    name="incapacidades"
                                    value="{{ old('incapacidades', $rrhh->incapacidades) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                            <div>
                                <x-input-label for="faltas" value="Faltas" />
                                <x-text-input id="faltas" type="number" min="0"
                                    name="faltas"
                                    value="{{ old('faltas', $rrhh->faltas) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                            <div>
                                <x-input-label for="vacaciones" value="Vacaciones / C30" />
                                <x-text-input id="vacaciones" type="number" min="0"
                                    name="vacaciones"
                                    value="{{ old('vacaciones', $rrhh->vacaciones) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                            <div>
                                <x-input-label for="becas" value="Becas" />
                                <x-text-input id="becas" type="number" min="0"
                                    name="becas"
                                    value="{{ old('becas', $rrhh->becas) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                            <div>
                                <x-input-label for="permisos_sindicales" value="Permisos sindicales" />
                                <x-text-input id="permisos_sindicales" type="number" min="0"
                                    name="permisos_sindicales"
                                    value="{{ old('permisos_sindicales', $rrhh->permisos_sindicales) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                            <div>
                                <x-input-label for="permiso_tiempo" value="Permiso por tiempo" />
                                <x-text-input id="permiso_tiempo" type="number" min="0"
                                    name="permiso_tiempo"
                                    value="{{ old('permiso_tiempo', $rrhh->permiso_tiempo) }}"
                                    class="mt-1 w-full text-sm"/>
                            </div>

                        </div>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-gray-100">
                        <x-primary-button>
                            Guardar sección RRHH
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN: PRODUCTIVIDAD POR SERVICIO (hospitalización) --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">
                            Productividad por servicio
                        </h3>
                        <p class="text-xs text-gray-500">
                            Captura por día y turno: pacientes, caídas, tiras, graves y tubos.
                        </p>
                    </div>
                    <span class="text-[11px] uppercase tracking-wide text-gray-400">
                        Hospitalización y urgencias
                    </span>
                </div>

                <form method="POST" action="{{ route('captura.ward.save') }}" class="p-4 space-y-3">
                    @csrf
                    <input type="hidden" name="daily_shift_id" value="{{ $dailyShift->id }}">

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs border-collapse">
                            <thead>
                                <tr class="bg-gray-100/80 text-[11px] uppercase tracking-wide text-gray-500">
                                    <th class="border border-gray-200 px-2 py-2 text-left sticky left-0 bg-gray-100/80 z-10">
                                        Servicio
                                    </th>
                                    <th class="border border-gray-200 px-2 py-2 text-center">Pacientes</th>
                                    <th class="border border-gray-200 px-2 py-2 text-center">Caídas</th>
                                    <th class="border border-gray-200 px-2 py-2 text-center">Tiras</th>
                                    <th class="border border-gray-200 px-2 py-2 text-center">Graves</th>
                                    <th class="border border-gray-200 px-2 py-2 text-center">Tubos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($services as $service)
                                    @php
                                        $row = $wardStats[$service->id] ?? null;
                                    @endphp
                                    <tr class="hover:bg-gray-50/60">
                                        {{-- Columna fija de servicio --}}
                                        <td class="border border-gray-100 px-3 py-2 text-[13px] font-medium text-gray-800 sticky left-0 bg-white">
                                            <div class="flex flex-col">
                                                <span>{{ $service->name }}</span>
                                                @if (!is_null($service->installed_capacity))
                                                    <span class="text-[10px] text-gray-400">
                                                        Capacidad: {{ $service->installed_capacity }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        @foreach (['pacientes','caidas','tiras','graves','tubos'] as $field)
                                            <td class="border border-gray-100 px-1 py-1 text-center align-middle">
                                                <input
                                                    type="number" min="0"
                                                    name="services[{{ $service->id }}][{{ $field }}]"
                                                    value="{{ old("services.{$service->id}.{$field}", $row->$field ?? '') }}"
                                                    class="w-20 text-xs rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-center"
                                                />
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>

                            {{-- NUEVO: FILA TOTAL POR DÍA Y TURNO --}}
                            @php
                                $totalPacientes = 0;
                                $totalCaidas    = 0;
                                $totalTiras     = 0;
                                $totalGraves    = 0;
                                $totalTubos     = 0;

                                foreach ($services as $service) {
                                    $row = $wardStats[$service->id] ?? null;
                                    $totalPacientes += $row->pacientes ?? 0;
                                    $totalCaidas    += $row->caidas ?? 0;
                                    $totalTiras     += $row->tiras ?? 0;
                                    $totalGraves    += $row->graves ?? 0;
                                    $totalTubos     += $row->tubos ?? 0;
                                }
                            @endphp
                            <tfoot>
                                <tr class="bg-emerald-50 text-[11px] font-semibold text-gray-700">
                                    <td class="border border-emerald-100 px-3 py-2 sticky left-0 bg-emerald-50 z-10">
                                        TOTAL DÍA / TURNO
                                    </td>
                                    <td class="border border-emerald-100 px-2 py-2 text-center">
                                        {{ $totalPacientes }}
                                    </td>
                                    <td class="border border-emerald-100 px-2 py-2 text-center">
                                        {{ $totalCaidas }}
                                    </td>
                                    <td class="border border-emerald-100 px-2 py-2 text-center">
                                        {{ $totalTiras }}
                                    </td>
                                    <td class="border border-emerald-100 px-2 py-2 text-center">
                                        {{ $totalGraves }}
                                    </td>
                                    <td class="border border-emerald-100 px-2 py-2 text-center">
                                        {{ $totalTubos }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-gray-100">
                        <x-primary-button>
                            Guardar sección de productividad
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN: PRODUCTIVIDAD TOCO CIRUGÍA --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">
                            Productividad TOCO Cirugía
                        </h3>
                        <p class="text-xs text-gray-500">
                            Registro diario por turno: partos, cesáreas, RN vivos, piel a piel, óbitos, etc.
                        </p>
                    </div>
                    <span class="text-[11px] uppercase tracking-wide text-gray-400">
                        Sala de labor y parto
                    </span>
                </div>

                <form method="POST" action="{{ route('captura.toco.save') }}" class="p-4 space-y-4">
                    @csrf
                    <input type="hidden" name="daily_shift_id" value="{{ $dailyShift->id }}">

                    {{-- Usamos un grid 2 columnas en escritorio / 1 en móvil --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        {{-- Columna 1 --}}
                        <div class="space-y-2">
                            <div>
                                <x-input-label for="partos" value="Partos" />
                                <x-text-input id="partos" type="number" min="0"
                                    name="partos"
                                    value="{{ old('partos', $toco->partos) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>

                            <div>
                                <x-input-label for="cesareas" value="Cesáreas" />
                                <x-text-input id="cesareas" type="number" min="0"
                                    name="cesareas"
                                    value="{{ old('cesareas', $toco->cesareas) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>

                            <div>
                                <x-input-label for="rn_vivos" value="RN vivos" />
                                <x-text-input id="rn_vivos" type="number" min="0"
                                    name="rn_vivos"
                                    value="{{ old('rn_vivos', $toco->rn_vivos) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>

                            <div>
                                <x-input-label for="piel_a_piel" value="Piel a piel" />
                                <x-text-input id="piel_a_piel" type="number" min="0"
                                    name="piel_a_piel"
                                    value="{{ old('piel_a_piel', $toco->piel_a_piel) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                        </div>

                        {{-- Columna 2 --}}
                        <div class="space-y-2">
                            <div>
                                <x-input-label for="obitos" value="Óbitos" />
                                <x-text-input id="obitos" type="number" min="0"
                                    name="obitos"
                                    value="{{ old('obitos', $toco->obitos) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>

                            <div>
                                <x-input-label for="legrados" value="Legrados" />
                                <x-text-input id="legrados" type="number" min="0"
                                    name="legrados"
                                    value="{{ old('legrados', $toco->legrados) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>

                            <div>
                                <x-input-label for="otb" value="OTB" />
                                <x-text-input id="otb" type="number" min="0"
                                    name="otb"
                                    value="{{ old('otb', $toco->otb) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>

                            <div>
                                <x-input-label for="rev_cavidad" value="Revisión de cavidad" />
                                <x-text-input id="rev_cavidad" type="number" min="0"
                                    name="rev_cavidad"
                                    value="{{ old('rev_cavidad', $toco->rev_cavidad) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                        </div>

                        {{-- Columna 3 --}}
                        <div class="space-y-2">
                            <div>
                                <x-input-label for="histerectomia" value="Histerectomía" />
                                <x-text-input id="histerectomia" type="number" min="0"
                                    name="histerectomia"
                                    value="{{ old('histerectomia', $toco->histerectomia) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>

                            <div>
                                <x-input-label for="plastias" value="Plastias" />
                                <x-text-input id="plastias" type="number" min="0"
                                    name="plastias"
                                    value="{{ old('plastias', $toco->plastias) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>

                            <div>
                                <x-input-label for="analgesias" value="Analgesias" />
                                <x-text-input id="analgesias" type="number" min="0"
                                    name="analgesias"
                                    value="{{ old('analgesias', $toco->analgesias) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>

                            <div>
                                <x-input-label for="emergencia_obstetrica" value="Emergencia obstétrica" />
                                <x-text-input id="emergencia_obstetrica" type="number" min="0"
                                    name="emergencia_obstetrica"
                                    value="{{ old('emergencia_obstetrica', $toco->emergencia_obstetrica) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>

                            <div>
                                <x-input-label for="consulta" value="Consulta" />
                                <x-text-input id="consulta" type="number" min="0"
                                    name="consulta"
                                    value="{{ old('consulta', $toco->consulta) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-gray-100">
                        <x-primary-button>
                            Guardar sección TOCO Cirugía
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN: PRODUCTIVIDAD QUIRÓFANOS --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">
                            Productividad Quirófanos
                        </h3>
                        <p class="text-xs text-gray-500">
                            Programadas, realizadas, suspendidas, urgencias, pendientes, contaminadas y salas trabajando.
                        </p>
                    </div>
                    <span class="text-[11px] uppercase tracking-wide text-gray-400">
                        Área quirúrgica
                    </span>
                </div>

                <form method="POST" action="{{ route('captura.quirofano.save') }}" class="p-4 space-y-4">
                    @csrf
                    <input type="hidden" name="daily_shift_id" value="{{ $dailyShift->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <x-input-label for="programadas" value="Programadas" />
                            <x-text-input id="programadas" type="number" min="0"
                                name="programadas"
                                value="{{ old('programadas', $quirofano->programadas) }}"
                                class="mt-1 w-full text-sm" />
                        </div>

                        <div>
                            <x-input-label for="realizadas" value="Realizadas" />
                            <x-text-input id="realizadas" type="number" min="0"
                                name="realizadas"
                                value="{{ old('realizadas', $quirofano->realizadas) }}"
                                class="mt-1 w-full text-sm" />
                        </div>

                        <div>
                            <x-input-label for="suspendidas" value="Suspendidas" />
                            <x-text-input id="suspendidas" type="number" min="0"
                                name="suspendidas"
                                value="{{ old('suspendidas', $quirofano->suspendidas) }}"
                                class="mt-1 w-full text-sm" />
                        </div>

                        <div>
                            <x-input-label for="urgencias" value="Urgencias" />
                            <x-text-input id="urgencias" type="number" min="0"
                                name="urgencias"
                                value="{{ old('urgencias', $quirofano->urgencias) }}"
                                class="mt-1 w-full text-sm" />
                        </div>

                        <div>
                            <x-input-label for="pendientes" value="Pendientes" />
                            <x-text-input id="pendientes" type="number" min="0"
                                name="pendientes"
                                value="{{ old('pendientes', $quirofano->pendientes) }}"
                                class="mt-1 w-full text-sm" />
                        </div>

                        <div>
                            <x-input-label for="contaminadas" value="Contaminadas" />
                            <x-text-input id="contaminadas" type="number" min="0"
                                name="contaminadas"
                                value="{{ old('contaminadas', $quirofano->contaminadas) }}"
                                class="mt-1 w-full text-sm" />
                        </div>

                        <div>
                            <x-input-label for="salas_trabajando" value="Salas trabajando" />
                            <x-text-input id="salas_trabajando" type="number" min="0"
                                name="salas_trabajando"
                                value="{{ old('salas_trabajando', $quirofano->salas_trabajando) }}"
                                class="mt-1 w-full text-sm" />
                        </div>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-gray-100">
                        <x-primary-button>
                            Guardar sección Quirófanos
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN: PRODUCTIVIDAD CONSULTA EXTERNA --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">
                            Productividad Consulta Externa
                        </h3>
                        <p class="text-xs text-gray-500">
                            Clínica de catéteres, heridas, lactancia y endoscopías.
                        </p>
                    </div>
                    <span class="text-[11px] uppercase tracking-wide text-gray-400">
                        Consulta externa
                    </span>
                </div>

                <form method="POST" action="{{ route('captura.outpatient.save') }}" class="p-4 space-y-6">
                    @csrf
                    <input type="hidden" name="daily_shift_id" value="{{ $dailyShift->id }}">

                    {{-- Clínica de catéteres --}}
                    <div>
                        <h4 class="font-semibold text-sm text-gray-800 mb-2">
                            Clínica de catéteres
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-6 gap-3 text-sm">
                            <div>
                                <x-input-label for="cat_medial" value="Medial" />
                                <x-text-input id="cat_medial" type="number" min="0"
                                    name="cat_medial"
                                    value="{{ old('cat_medial', $outpatient->cat_medial) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="cat_picc" value="PICC" />
                                <x-text-input id="cat_picc" type="number" min="0"
                                    name="cat_picc"
                                    value="{{ old('cat_picc', $outpatient->cat_picc) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="cat_umbilical" value="Umbilical" />
                                <x-text-input id="cat_umbilical" type="number" min="0"
                                    name="cat_umbilical"
                                    value="{{ old('cat_umbilical', $outpatient->cat_umbilical) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="cat_asepsia" value="Asepsia" />
                                <x-text-input id="cat_asepsia" type="number" min="0"
                                    name="cat_asepsia"
                                    value="{{ old('cat_asepsia', $outpatient->cat_asepsia) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="cat_periferico_corto" value="Periférico corto" />
                                <x-text-input id="cat_periferico_corto" type="number" min="0"
                                    name="cat_periferico_corto"
                                    value="{{ old('cat_periferico_corto', $outpatient->cat_periferico_corto) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="cat_cvc" value="CVC" />
                                <x-text-input id="cat_cvc" type="number" min="0"
                                    name="cat_cvc"
                                    value="{{ old('cat_cvc', $outpatient->cat_cvc) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                        </div>
                    </div>

                    {{-- Heridas --}}
                    <div class="pt-2 border-t border-gray-100">
                        <h4 class="font-semibold text-sm text-gray-800 mb-2">
                            Heridas
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-5 gap-3 text-sm">
                            <div>
                                <x-input-label for="her_curaciones" value="Curaciones" />
                                <x-text-input id="her_curaciones" type="number" min="0"
                                    name="her_curaciones"
                                    value="{{ old('her_curaciones', $outpatient->her_curaciones) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="her_interconsultas" value="Interconsultas" />
                                <x-text-input id="her_interconsultas" type="number" min="0"
                                    name="her_interconsultas"
                                    value="{{ old('her_interconsultas', $outpatient->her_interconsultas) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="her_valoraciones" value="Valoraciones" />
                                <x-text-input id="her_valoraciones" type="number" min="0"
                                    name="her_valoraciones"
                                    value="{{ old('her_valoraciones', $outpatient->her_valoraciones) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="her_cuidados_especiales" value="Cuidados especiales" />
                                <x-text-input id="her_cuidados_especiales" type="number" min="0"
                                    name="her_cuidados_especiales"
                                    value="{{ old('her_cuidados_especiales', $outpatient->her_cuidados_especiales) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="her_vac" value="VAC" />
                                <x-text-input id="her_vac" type="number" min="0"
                                    name="her_vac"
                                    value="{{ old('her_vac', $outpatient->her_vac) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                        </div>
                    </div>

                    {{-- Lactancia --}}
                    <div class="pt-2 border-t border-gray-100">
                        <h4 class="font-semibold text-sm text-gray-800 mb-2">
                            Lactancia
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                            <div>
                                <x-input-label for="lac_asesorias" value="Asesorías" />
                                <x-text-input id="lac_asesorias" type="number" min="0"
                                    name="lac_asesorias"
                                    value="{{ old('lac_asesorias', $outpatient->lac_asesorias) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="lac_autoclaves" value="Autoclaves" />
                                <x-text-input id="lac_autoclaves" type="number" min="0"
                                    name="lac_autoclaves"
                                    value="{{ old('lac_autoclaves', $outpatient->lac_autoclaves) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="lac_fracciones" value="Fracciones" />
                                <x-text-input id="lac_fracciones" type="number" min="0"
                                    name="lac_fracciones"
                                    value="{{ old('lac_fracciones', $outpatient->lac_fracciones) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                        </div>
                    </div>

                    {{-- Endoscopías --}}
                    <div class="pt-2 border-t border-gray-100">
                        <h4 class="font-semibold text-sm text-gray-800 mb-2">
                            Endoscopías
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-sm">
                            <div>
                                <x-input-label for="end_endoscopias" value="Endoscopías" />
                                <x-text-input id="end_endoscopias" type="number" min="0"
                                    name="end_endoscopias"
                                    value="{{ old('end_endoscopias', $outpatient->end_endoscopias) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="end_colonoscopias" value="Colonoscopías" />
                                <x-text-input id="end_colonoscopias" type="number" min="0"
                                    name="end_colonoscopias"
                                    value="{{ old('end_colonoscopias', $outpatient->end_colonoscopias) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="end_biopsias" value="Biopsias" />
                                <x-text-input id="end_biopsias" type="number" min="0"
                                    name="end_biopsias"
                                    value="{{ old('end_biopsias', $outpatient->end_biopsias) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                            <div>
                                <x-input-label for="end_cepres" value="CEPRES" />
                                <x-text-input id="end_cepres" type="number" min="0"
                                    name="end_cepres"
                                    value="{{ old('end_cepres', $outpatient->end_cepres) }}"
                                    class="mt-1 w-full text-sm" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-gray-100">
                        <x-primary-button>
                            Guardar sección Consulta Externa
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN: PRODUCTIVIDAD AUTOCLAVES --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">
                            Productividad Autoclaves
                        </h3>
                        <p class="text-xs text-gray-500">
                            Total de cargas procesadas por turno en CEYE y SubCEYE.
                        </p>
                    </div>
                    <span class="text-[11px] uppercase tracking-wide text-gray-400">
                        Esterilización
                    </span>
                </div>

                <form method="POST" action="{{ route('captura.autoclaves.save') }}" class="p-4 space-y-4">
                    @csrf
                    <input type="hidden" name="daily_shift_id" value="{{ $dailyShift->id }}">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <x-input-label for="ceye" value="CEYE - total de cargas" />
                            <x-text-input id="ceye" type="number" min="0"
                                name="ceye"
                                value="{{ old('ceye', $autoclaves->ceye) }}"
                                class="mt-1 w-full text-sm" />
                        </div>

                        <div>
                            <x-input-label for="subceye" value="SubCEYE - total de cargas" />
                            <x-text-input id="subceye" type="number" min="0"
                                name="subceye"
                                value="{{ old('subceye', $autoclaves->subceye) }}"
                                class="mt-1 w-full text-sm" />
                        </div>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-gray-100">
                        <x-primary-button>
                            Guardar sección Autoclaves
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN: TOTAL DE DEFUNCIONES --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">
                            Total de defunciones
                        </h3>
                        <p class="text-xs text-gray-500">
                            Registro consolidado de defunciones por turno y fecha.
                        </p>
                    </div>
                    <span class="text-[11px] uppercase tracking-wide text-gray-400">
                        Indicador crítico
                    </span>
                </div>

                <form method="POST" action="{{ route('captura.defunciones.save') }}" class="p-4 space-y-4">
                    @csrf
                    <input type="hidden" name="daily_shift_id" value="{{ $dailyShift->id }}">

                    <div class="max-w-xs">
                        <x-input-label for="total_defunciones" value="Total de defunciones en el turno" />
                        <x-text-input id="total_defunciones" type="number" min="0"
                            name="total_defunciones"
                            value="{{ old('total_defunciones', $defunciones->total_defunciones) }}"
                            class="mt-1 w-full text-sm" />
                        <p class="text-[11px] text-gray-400 mt-1">
                            Considera todas las unidades del hospital en este turno.
                        </p>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-gray-100">
                        <x-primary-button>
                            Guardar total de defunciones
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN: NOTA / OBSERVACIONES DEL TURNO --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">
                            Nota del turno
                        </h3>
                        <p class="text-xs text-gray-500">
                            Observaciones relevantes del turno (eventos, acuerdos, incidencias cualitativas, etc.).
                        </p>
                    </div>
                    <span class="text-[11px] uppercase tracking-wide text-gray-400">
                        Uso interno de Subjefatura
                    </span>
                </div>

                <form method="POST" action="{{ route('captura.note.save') }}" class="p-4 space-y-3">
                    @csrf
                    <input type="hidden" name="daily_shift_id" value="{{ $dailyShift->id }}">

                    <div>
                        <textarea
                            name="note"
                            rows="4"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                            placeholder="Ej. Alta demanda en Urgencias, redistribución de personal a TOCO, comunicación con COCASEP, incidentes relevantes sin daño, etc.">{{ old('note', $dailyShift->note) }}</textarea>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-gray-100">
                        <x-primary-button>
                            Guardar nota del turno
                        </x-primary-button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
