<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 space-y-6">

        {{-- ENCABEZADO DEL INFORME --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Informe de productividad y recurso humano
                </h1>
                <p class="text-sm text-gray-500">
                    Del {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }}
                    al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}
                    · Turno:
                    <span class="font-semibold">
                        @switch($shiftCode)
                            @case('M')
                                Matutino
                                @break
                            @case('V')
                                Vespertino
                                @break
                            @case('A')
                                Ambos (Matutino + Vespertino)
                                @break
                            @default
                                {{ $shiftCode }}
                        @endswitch
                    </span>
                </p>
            </div>

            <div class="flex flex-col items-end gap-2">
                <div class="flex flex-wrap gap-2 justify-end">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                        {{ $total_general_rrhh ?? 0 }} personas (asistencias + incidencias, sin pasantes)
                    </span>
                    @if(!is_null($porcentaje_asistencia))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                            Asistencia: {{ $porcentaje_asistencia }}%
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                            Inasistencia: {{ $porcentaje_inasistencia }}%
                        </span>
                    @endif
                </div>

                <form action="{{ route('informes.pdf') }}" method="POST">
                    @csrf
                    {{-- reenviamos los mismos filtros --}}
                    <input type="hidden" name="from" value="{{ $from }}">
                    <input type="hidden" name="to" value="{{ $to }}">
                    <input type="hidden" name="shift" value="{{ $shiftCode }}">
                    <input type="hidden" name="include_notes" value="{{ $includeNotes ? 1 : 0 }}">

                    <button type="submit"
                            class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold
                                   bg-indigo-600 text-white hover:bg-indigo-700">
                        Exportar a PDF
                    </button>
                </form>
            </div>
        </div>

        @php
            $serviceStatsCollection = collect($serviceStats);
            $totalRow = $serviceStatsCollection->firstWhere('is_total', true);
            $rowsServicios = $serviceStatsCollection->where('is_total', false);

            // Pacientes = suma de promedios diarios por servicio (lo que llamamos TOTAL HOSPITALARIO)
            $promedioHospitalario = $totalRow['total_pacientes'] ?? 0;
        @endphp

        {{-- CARDS RESUMEN SUPERIOR --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card promedio hospitalario --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col gap-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Pacientes (promedio diario hospitalario)
                </p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $promedioHospitalario }}
                </p>
                <p class="text-[11px] text-gray-400">
                    Suma de los promedios diarios de pacientes por servicio en el periodo.
                </p>
            </div>

            {{-- Card defunciones --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col gap-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Defunciones
                </p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $total_defunciones }}
                </p>
                <p class="text-[11px] text-gray-400">
                    Total de defunciones registradas en el periodo y turno.
                </p>
            </div>

            {{-- Card asistencia --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col gap-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Asistencia total (sin pasantes)
                </p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $asistencia_total ?? 0 }}
                </p>
                <p class="text-[11px] text-gray-400">
                    Personal adscrito que se presentó a laborar (subjefatura, supervisión, jefes, generales y auxiliares).
                </p>
            </div>

            {{-- Card incidencias --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col gap-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Incidencias
                </p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $incidencias_total ?? 0 }}
                </p>
                <p class="text-[11px] text-gray-400">
                    Descansos, incapacidades, faltas, vacaciones, becas y otros permisos.
                </p>
            </div>
        </div>

        {{-- OCUPACIÓN POR SERVICIO --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80 space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-800 text-lg">
                            Ocupación por servicio
                        </h2>
                        <p class="text-xs text-gray-500">
                            Pacientes (promedio diario por servicio), eventos adversos y porcentaje de ocupación según capacidad instalada.
                        </p>
                    </div>
                </div>

                {{-- Barra de ocupación hospitalaria global --}}
                @if(isset($hospital_occupancy) && !is_null($hospital_occupancy))
                    @php
                        $occGlobalBar = min(max($hospital_occupancy, 0), 130);
                    @endphp
                    <div class="mt-3">
                        <p class="text-[11px] text-gray-500 mb-1">
                            Ocupación hospitalaria global (promedio): <span class="font-semibold">{{ $hospital_occupancy }}%</span>
                        </p>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full"
                                 style="width: {{ $occGlobalBar }}%; background: linear-gradient(to right, #0ea5e9, #0369a1);">
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="p-4 overflow-x-auto">
                @if($serviceStatsCollection->isEmpty())
                    <p class="text-sm text-gray-500">
                        No hay registros en el rango de fechas y turno seleccionados.
                    </p>
                @else
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-gray-500 uppercase tracking-wide border-b">
                                <th class="pb-2">Servicio</th>
                                <th class="pb-2 text-right">Pacientes (P/D)</th>
                                <th class="pb-2 text-right">Caídas</th>
                                <th class="pb-2 text-right">Tiras</th>
                                <th class="pb-2 text-right">Graves (P/D)</th>
                                <th class="pb-2 text-right">Tubos (P/D)</th>
                                <th class="pb-2">Ocupación</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {{-- Filas por servicio --}}
                            @foreach($rowsServicios as $row)
                                @php
                                    $s   = $row['service'];
                                    $occ = $row['porcentaje_ocupacion'];
                                    $occBar = $occ ? min(max($occ, 0), 130) : 0;
                                @endphp
                                <tr>
                                    <td class="py-2 pr-4">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-800 text-xs">
                                                {{ $s->name }}
                                            </span>
                                            @if(!is_null($s->installed_capacity))
                                                <span class="text-[11px] text-gray-400">
                                                    Capacidad: {{ $s->installed_capacity }} camas
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-2 text-right tabular-nums text-gray-700">
                                        {{ $row['total_pacientes'] }}
                                    </td>
                                    <td class="py-2 text-right tabular-nums text-gray-700">
                                        {{ $row['total_caidas'] }}
                                    </td>
                                    <td class="py-2 text-right tabular-nums text-gray-700">
                                        {{ $row['total_tiras'] }}
                                    </td>
                                    <td class="py-2 text-right tabular-nums text-gray-700">
                                        {{ $row['total_graves'] }}
                                    </td>
                                    <td class="py-2 text-right tabular-nums text-gray-700">
                                        {{ $row['total_tubos'] }}
                                    </td>
                                    <td class="py-2">
                                        @if(!is_null($occ))
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                                                    <div class="h-2 rounded-full"
                                                         style="width: {{ $occBar }}%; background: linear-gradient(to right, #10b981, #047857);">
                                                    </div>
                                                </div>
                                                <span class="text-xs font-semibold text-gray-700 w-14 text-right tabular-nums">
                                                    {{ $occ }}%
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-[11px] text-gray-400 italic">
                                                Sin capacidad definida
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        {{-- Fila TOTAL HOSPITALARIO --}}
                        @if($totalRow)
                            @php
                                $occTotal = $totalRow['porcentaje_ocupacion'] ?? null;
                                $occTotalBar = $occTotal ? min(max($occTotal, 0), 130) : 0;
                            @endphp
                            <tfoot>
                                <tr class="bg-emerald-50 font-semibold text-gray-800">
                                    <td class="py-2 pr-4">
                                        TOTAL HOSPITALARIO
                                    </td>
                                    <td class="py-2 text-right tabular-nums">
                                        {{ $totalRow['total_pacientes'] }}
                                    </td>
                                    <td class="py-2 text-right tabular-nums">
                                        {{ $totalRow['total_caidas'] }}
                                    </td>
                                    <td class="py-2 text-right tabular-nums">
                                        {{ $totalRow['total_tiras'] }}
                                    </td>
                                    <td class="py-2 text-right tabular-nums">
                                        {{ $totalRow['total_graves'] }}
                                    </td>
                                    <td class="py-2 text-right tabular-nums">
                                        {{ $totalRow['total_tubos'] }}
                                    </td>
                                    <td class="py-2">
                                        @if(!is_null($occTotal))
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 bg-emerald-100 rounded-full h-2 overflow-hidden">
                                                    <div class="h-2 rounded-full"
                                                         style="width: {{ $occTotalBar }}%; background: linear-gradient(to right, #22c55e, #16a34a);">
                                                    </div>
                                                </div>
                                                <span class="text-xs font-semibold text-gray-800 w-14 text-right tabular-nums">
                                                    {{ $occTotal }}%
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-[11px] text-gray-500 italic">
                                                Sin capacidad global calculada
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                @endif
            </div>
        </div>

        {{-- GRID: TOCO Y QUIRÓFANOS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            {{-- TOCO --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                    <h2 class="font-semibold text-gray-800 text-lg">
                        TOCO Cirugía
                    </h2>
                    <p class="text-xs text-gray-500">
                        Resumen de productividad obstétrica en el periodo (acumulado).
                    </p>
                </div>
                <div class="p-4 grid grid-cols-2 gap-3 text-sm">
                    @foreach($toco_totales as $label => $value)
                        <div class="flex flex-col">
                            <span class="text-[11px] text-gray-500 uppercase tracking-wide">
                                {{ \Illuminate\Support\Str::of($label)->replace('_',' ')->upper() }}
                            </span>
                            <span class="text-lg font-semibold text-gray-800 tabular-nums">
                                {{ $value }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- QUIRÓFANOS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                    <h2 class="font-semibold text-gray-800 text-lg">
                        Quirófanos
                    </h2>
                    <p class="text-xs text-gray-500">
                        Cirugías programadas, realizadas, urgentes y salas trabajando (promedio diario).
                    </p>
                </div>
                <div class="p-4 grid grid-cols-2 gap-3 text-sm">
                    @foreach($qf_totales as $label => $value)
                        <div class="flex flex-col">
                            <span class="text-[11px] text-gray-500 uppercase tracking-wide">
                                {{ \Illuminate\Support\Str::of($label)->replace('_',' ')->upper() }}
                                @if($label === 'salas_trabajando')
                                    (PROM.)
                                @endif
                            </span>
                            <span class="text-lg font-semibold text-gray-800 tabular-nums">
                                {{ $value }}
                            </span>
                        </div>
                    @endforeach
                    {{-- TOTAL GENERAL DE CIRUGÍAS REALIZADAS --}}
                    <div class="flex flex-col col-span-2 border-t border-gray-100 pt-2 mt-1">
                        <span class="text-[11px] text-gray-500 uppercase tracking-wide">
                            TOTAL CIRUGÍAS REALIZADAS (PROGRAMADAS + URGENCIAS)
                        </span>
                        <span class="text-xl font-bold text-gray-900 tabular-nums">
                            {{ $total_cirugias_realizadas ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CONSULTA EXTERNA + AUTOCLAVES / DEFUNCIONES --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
            {{-- Consulta externa (ocupa 2/3) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden xl:col-span-2">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                    <h2 class="font-semibold text-gray-800 text-lg">
                        Consulta externa
                    </h2>
                    <p class="text-xs text-gray-500">
                        Clínica de catéteres, heridas, lactancia y endoscopías (acumulado en el periodo).
                    </p>
                </div>

                <div class="p-4 space-y-4 text-sm">
                    {{-- Catéteres --}}
                    <div>
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                            Clínica de catéteres
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                            @foreach([
                                'cat_medial' => 'Medial',
                                'cat_picc' => 'PICC',
                                'cat_umbilical' => 'Umbilical',
                                'cat_asepsia' => 'Asepsia',
                                'cat_periferico_corto' => 'Periférico corto',
                                'cat_cvc' => 'CVC',
                            ] as $key => $label)
                                <div>
                                    <p class="text-[11px] text-gray-500 uppercase tracking-wide">{{ $label }}</p>
                                    <p class="text-lg font-semibold text-gray-800 tabular-nums">
                                        {{ $out_totales[$key] ?? 0 }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Heridas --}}
                    <div class="pt-2 border-t border-gray-100">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                            Heridas
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                            @foreach([
                                'her_curaciones'          => 'Curaciones',
                                'her_interconsultas'      => 'Interconsultas',
                                'her_valoraciones'        => 'Valoraciones',
                                'her_cuidados_especiales' => 'Cuidados especiales',
                                'her_vac'                 => 'VAC',
                            ] as $key => $label)
                                <div>
                                    <p class="text-[11px] text-gray-500 uppercase tracking-wide">{{ $label }}</p>
                                    <p class="text-lg font-semibold text-gray-800 tabular-nums">
                                        {{ $out_totales[$key] ?? 0 }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Lactancia --}}
                    <div class="pt-2 border-t border-gray-100">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                            Lactancia
                        </h3>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach([
                                'lac_asesorias'  => 'Asesorías',
                                'lac_autoclaves' => 'Autoclaves',
                                'lac_fracciones' => 'Fracciones',
                            ] as $key => $label)
                                <div>
                                    <p class="text-[11px] text-gray-500 uppercase tracking-wide">{{ $label }}</p>
                                    <p class="text-lg font-semibold text-gray-800 tabular-nums">
                                        {{ $out_totales[$key] ?? 0 }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Endoscopías --}}
                    <div class="pt-2 border-t border-gray-100">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                            Endoscopías
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach([
                                'end_endoscopias'   => 'Endoscopías',
                                'end_colonoscopias' => 'Colonoscopías',
                                'end_biopsias'      => 'Biopsias',
                                'end_cepres'        => 'CEPRES',
                            ] as $key => $label)
                                <div>
                                    <p class="text-[11px] text-gray-500 uppercase tracking-wide">{{ $label }}</p>
                                    <p class="text-lg font-semibold text-gray-800 tabular-nums">
                                        {{ $out_totales[$key] ?? 0 }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Autoclaves + defunciones --}}
            <div class="space-y-4">
                {{-- Autoclaves --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                        <h2 class="font-semibold text-gray-800 text-lg">
                            Autoclaves
                        </h2>
                        <p class="text-xs text-gray-500">
                            Total de cargas procesadas por CEYE y SubCEYE (periodo).
                        </p>
                    </div>
                    <div class="p-4 grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-[11px] text-gray-500 uppercase tracking-wide">CEYE</p>
                            <p class="text-lg font-semibold text-gray-800 tabular-nums">
                                {{ $auto_totales['ceye'] ?? 0 }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-500 uppercase tracking-wide">SubCEYE</p>
                            <p class="text-lg font-semibold text-gray-800 tabular-nums">
                                {{ $auto_totales['subceye'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Defunciones detalle --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                        <h2 class="font-semibold text-gray-800 text-lg">
                            Defunciones
                        </h2>
                        <p class="text-xs text-gray-500">
                            Total acumulado del turno en todos los servicios.
                        </p>
                    </div>
                    <div class="p-4">
                        <p class="text-3xl font-bold text-gray-900 tabular-nums">
                            {{ $total_defunciones }}
                        </p>
                        <p class="text-[11px] text-gray-400 mt-1">
                            Este valor puede utilizarse como indicador crítico para comités de calidad y seguridad del paciente.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- RRHH: ASISTENCIA E INCIDENCIAS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            {{-- Asistencia por categoría --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                    <h2 class="font-semibold text-gray-800 text-lg">
                        Asistencia por categoría (sin pasantes)
                    </h2>
                    <p class="text-xs text-gray-500">
                        Distribución del personal presente en el turno.
                    </p>
                </div>
                <div class="p-4 space-y-2 text-sm">
                    @foreach($asistencia ?? [] as $key => $value)
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">
                                {{ \Illuminate\Support\Str::of($key)->replace('_',' ')->title() }}
                            </span>
                            <span class="font-semibold text-gray-800 tabular-nums">
                                {{ $value }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Incidencias por categoría --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                    <h2 class="font-semibold text-gray-800 text-lg">
                        Incidencias
                    </h2>
                    <p class="text-xs text-gray-500">
                        Ausentismo por tipo de incidencia en el periodo.
                    </p>
                </div>
                <div class="p-4 space-y-2 text-sm">
                    @foreach($incidencias ?? [] as $key => $value)
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">
                                {{ \Illuminate\Support\Str::of($key)->replace('_',' ')->title() }}
                            </span>
                            <span class="font-semibold text-gray-800 tabular-nums">
                                {{ $value }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ESTADÍSTICA RRHH: MEDIA / MEDIANA / MODA --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-gray-800 text-lg">
                        Estadística de recurso humano
                    </h2>
                    <p class="text-xs text-gray-500">
                        Media, mediana y moda del personal asistente y de las incidencias por día-turno.
                    </p>
                </div>
            </div>

            <div class="p-4 overflow-x-auto text-sm">
                @if(!empty($estadistica_rrhh))
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-left text-xs text-gray-500 uppercase tracking-wide border-b">
                                <th class="pb-2">Indicador</th>
                                <th class="pb-2 text-right">Media</th>
                                <th class="pb-2 text-right">Mediana</th>
                                <th class="pb-2 text-right">Moda</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-2 text-gray-700 font-medium">
                                    Asistencia total por día
                                </td>
                                <td class="py-2 text-right tabular-nums">
                                    {{ $estadistica_rrhh['asistencia_media'] ?? '-' }}
                                </td>
                                <td class="py-2 text-right tabular-nums">
                                    {{ $estadistica_rrhh['asistencia_mediana'] ?? '-' }}
                                </td>
                                <td class="py-2 text-right tabular-nums">
                                    {{ $estadistica_rrhh['asistencia_moda'] ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2 text-gray-700 font-medium">
                                    Incidencias totales por día
                                </td>
                                <td class="py-2 text-right tabular-nums">
                                    {{ $estadistica_rrhh['incidencias_media'] ?? '-' }}
                                </td>
                                <td class="py-2 text-right tabular-nums">
                                    {{ $estadistica_rrhh['incidencias_mediana'] ?? '-' }}
                                </td>
                                <td class="py-2 text-right tabular-nums">
                                    {{ $estadistica_rrhh['incidencias_moda'] ?? '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <p class="text-sm text-gray-500">
                        No hay suficientes registros para generar estadística descriptiva.
                    </p>
                @endif
            </div>
        </div>

        {{-- ANEXO: NOTAS DE TURNO (OPCIONAL) --}}
        @if($includeNotes && $shiftNotes->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80 flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-800 text-lg">
                            Anexo: Notas de turno
                        </h2>
                        <p class="text-xs text-gray-500">
                            Observaciones registradas por jefes de servicio y subjefatura durante el periodo seleccionado.
                        </p>
                    </div>
                    <span class="text-[11px] uppercase tracking-wide text-gray-400">
                        Uso interno · Narrativo
                    </span>
                </div>

                <div class="p-4 space-y-4 text-sm max-h-[600px] overflow-y-auto">
                    @foreach($shiftNotes as $noteShift)
                        <div class="border border-gray-100 rounded-xl p-3 bg-gray-50/60">
                            <p class="text-[11px] text-gray-500 mb-1">
                                <span class="font-semibold text-gray-700">
                                    {{ \Carbon\Carbon::parse($noteShift->date)->format('d/m/Y') }}
                                    · Turno {{ $noteShift->shift->name ?? $noteShift->shift->code }}
                                </span>
                            </p>
                            <p class="text-xs text-gray-800 whitespace-pre-line leading-relaxed">
                                {{ $noteShift->note }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
