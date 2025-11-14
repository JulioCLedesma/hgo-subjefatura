@php
    use Illuminate\Support\Str;

    $statsCollection   = collect($serviceStats);
    $totalRow          = $statsCollection->firstWhere('is_total', true);
    $rowsServicios     = $statsCollection->where('is_total', false);
    $promedioHospital  = $totalRow['total_pacientes'] ?? 0;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de productividad y RRHH</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }
        h1, h2, h3 {
            margin: 0;
            padding: 0;
        }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .text-muted { color: #6b7280; }
        .text-small { font-size: 10px; }
        .chip {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 9px;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            margin-right: 4px;
        }
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 10px;
            margin-bottom: 8px;
        }
        .card-title {
            font-size: 9px;
            text-transform: uppercase;
            color: #6b7280;
        }
        .card-value {
            font-size: 18px;
            font-weight: bold;
        }
        .section {
            margin-top: 16px;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 3px 4px;
        }
        th {
            background-color: #f3f4f6;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .bar-container {
            width: 100%;
            background-color: #e5e7eb;
            height: 6px;
            border-radius: 999px;
            overflow: hidden;
        }
        .bar-fill {
            height: 6px;
            background-color: #10b981;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .header-table td {
            border: none;
            vertical-align: middle;
        }
        .logo-left {
            text-align: left;
        }
        .logo-right {
            text-align: right;
        }
        .logo-img {
            height: 50px;
        }
        .header-title-cell {
            text-align: center;
        }
        .header-title-main {
            font-size: 13px;
            font-weight: bold;
        }
        .header-title-sub {
            font-size: 10px;
            color: #4b5563;
        }
        .no-border {
            border: none !important;
        }
    </style>
</head>
<body>

    {{-- ENCABEZADO CON LOGOS --}}
    <table class="header-table">
        <tr>
            <td class="logo-left">
                <img src="{{ public_path('images/logo-salud.jpg') }}" class="logo-img">
            </td>
            <td class="header-title-cell">
                <div class="header-title-main">
                    HOSPITAL GENERAL DE OCCIDENTE “ZOQUIPAN”
                </div>
                <div class="header-title-sub">
                    Jefatura de Enfermería · Subjefatura de Enfermería<br>
                    Informe de productividad y recurso humano
                </div>
            </td>
            <td class="logo-right">
                <img src="{{ public_path('images/logo-zoquipan.jpg') }}" class="logo-img">
            </td>
        </tr>
    </table>

    {{-- DATOS GENERALES DEL INFORME --}}
    <p class="text-small text-muted mb-2">
        Periodo:
        {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }}
        al
        {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}
        · Turno:
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
    </p>

    <div class="mb-3">
        <span class="chip">
            Total RRHH (asistencia + incidencias, sin pasantes): {{ $total_general_rrhh ?? 0 }}
        </span>
        @if(!is_null($porcentaje_asistencia))
            <span class="chip">
                Asistencia: {{ $porcentaje_asistencia }}%
            </span>
            <span class="chip">
                Inasistencia: {{ $porcentaje_inasistencia }}%
            </span>
        @endif
        @if(isset($hospital_occupancy) && !is_null($hospital_occupancy))
            <span class="chip">
                Ocupación hospitalaria (promedio): {{ $hospital_occupancy }}%
            </span>
        @endif
    </div>

    {{-- CARDS RESUMEN --}}
    <table class="mb-4">
        <tr>
            {{-- Pacientes (promedio diario hospitalario) --}}
            <td width="25%">
                <div class="card">
                    <div class="card-title">Pacientes (promedio diario hospitalario)</div>
                    <div class="card-value">{{ $promedioHospital }}</div>
                    <div class="text-small text-muted">
                        Suma de los promedios diarios de pacientes por servicio en el periodo.
                    </div>
                </div>
            </td>

            {{-- Defunciones --}}
            <td width="25%">
                <div class="card">
                    <div class="card-title">Defunciones</div>
                    <div class="card-value">{{ $total_defunciones }}</div>
                    <div class="text-small text-muted">
                        Total registrado en el periodo y turno.
                    </div>
                </div>
            </td>

            {{-- Asistencia total (sin pasantes) --}}
            <td width="25%">
                <div class="card">
                    <div class="card-title">Asistencia total (sin pasantes)</div>
                    <div class="card-value">{{ $asistencia_total ?? 0 }}</div>
                    <div class="text-small text-muted">
                        Personal adscrito que se presentó a laborar.
                    </div>
                </div>
            </td>

            {{-- Incidencias --}}
            <td width="25%">
                <div class="card">
                    <div class="card-title">Incidencias</div>
                    <div class="card-value">{{ $incidencias_total ?? 0 }}</div>
                    <div class="text-small text-muted">
                        Descansos, incapacidades, faltas, vacaciones, becas y permisos.
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- OCUPACIÓN POR SERVICIO --}}
    <div class="section">
        <h2 class="mb-1">Ocupación por servicio</h2>
        <p class="text-small text-muted mb-2">
            Pacientes (promedio diario por servicio), eventos adversos y porcentaje de ocupación contra capacidad instalada.
        </p>

        {{-- Barra de ocupación hospitalaria global --}}
        @if(isset($hospital_occupancy) && !is_null($hospital_occupancy))
            @php
                $occGlobalBar = min(max($hospital_occupancy, 0), 130);
            @endphp
            <table class="mb-2 no-border">
                <tr class="no-border">
                    <td class="no-border">
                        <span class="text-small text-muted">
                            Ocupación hospitalaria global: <strong>{{ $hospital_occupancy }}%</strong>
                        </span>
                        <div class="bar-container" style="margin-top: 2px;">
                            <div class="bar-fill" style="width: {{ $occGlobalBar }}%;"></div>
                        </div>
                    </td>
                </tr>
            </table>
        @endif

        @if($statsCollection->isEmpty())
            <p class="text-small text-muted">Sin registros en el periodo y turno seleccionados.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th class="text-right">Pacientes (promedio diario)</th>
                        <th class="text-right">Caídas</th>
                        <th class="text-right">Tiras</th>
                        <th class="text-right">Graves (PROM)</th>
                        <th class="text-right">Tubos (PROM)</th>
                        <th>Ocupación</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Filas por servicio --}}
                    @foreach($rowsServicios as $row)
                        @php
                            $s   = $row['service'];
                            $occ = $row['porcentaje_ocupacion'];
                            $occBar = $occ ? min(max($occ, 0), 130) : 0;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $s->name }}</strong><br>
                                @if(!is_null($s->installed_capacity))
                                    <span class="text-small text-muted">
                                        Capacidad: {{ $s->installed_capacity }} camas
                                    </span>
                                @endif
                            </td>
                            <td class="text-right">{{ $row['total_pacientes'] }}</td>
                            <td class="text-right">{{ $row['total_caidas'] }}</td>
                            <td class="text-right">{{ $row['total_tiras'] }}</td>
                            <td class="text-right">{{ $row['total_graves'] }}</td>
                            <td class="text-right">{{ $row['total_tubos'] }}</td>
                            <td>
                                @if(!is_null($occ))
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: {{ $occBar }}%;"></div>
                                    </div>
                                    <div class="text-small">
                                        {{ $occ }}%
                                    </div>
                                @else
                                    <span class="text-small text-muted">Sin capacidad definida</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                {{-- Fila TOTAL HOSPITALARIO --}}
                @if($totalRow)
                    @php
                        $occTotal    = $totalRow['porcentaje_ocupacion'] ?? null;
                        $occTotalBar = $occTotal ? min(max($occTotal, 0), 130) : 0;
                    @endphp
                    <tfoot>
                        <tr style="background-color:#ecfdf5; font-weight:bold;">
                            <td>TOTAL HOSPITALARIO</td>
                            <td class="text-right">{{ $totalRow['total_pacientes'] }}</td>
                            <td class="text-right">{{ $totalRow['total_caidas'] }}</td>
                            <td class="text-right">{{ $totalRow['total_tiras'] }}</td>
                            <td class="text-right">{{ $totalRow['total_graves'] }}</td>
                            <td class="text-right">{{ $totalRow['total_tubos'] }}</td>
                            <td>
                                @if(!is_null($occTotal))
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: {{ $occTotalBar }}%; background-color:#22c55e;"></div>
                                    </div>
                                    <div class="text-small">
                                        {{ $occTotal }}%
                                    </div>
                                @else
                                    <span class="text-small text-muted">Sin capacidad global</span>
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        @endif
    </div>

    {{-- TOCO Y QUIRÓFANOS --}}
    <div class="section">
        <h2 class="mb-1">TOCO cirugía y quirófanos</h2>
        <table>
            <tr>
                {{-- TOCO --}}
                <td width="50%" valign="top">
                    <h3 class="mb-1">TOCO cirugía (acumulado)</h3>
                    <table>
                        @foreach($toco_totales as $label => $value)
                            <tr>
                                <td>
                                    <span class="text-small text-muted">
                                        {{ Str::of($label)->replace('_', ' ')->title() }}
                                    </span>
                                </td>
                                <td class="text-right">{{ $value }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>

                {{-- QUIRÓFANOS --}}
                <td width="50%" valign="top">
                    <h3 class="mb-1">Quirófanos (acumulado)</h3>
                    <table>
                        @foreach($qf_totales as $label => $value)
                            <tr>
                                <td>
                                    <span class="text-small text-muted">
                                        {{ Str::of($label)->replace('_', ' ')->title() }}
                                        @if($label === 'salas_trabajando')
                                            (promedio diario)
                                        @endif
                                    </span>
                                </td>
                                <td class="text-right">{{ $value }}</td>
                            </tr>
                        @endforeach

                        {{-- TOTAL GENERAL DE CIRUGÍAS REALIZADAS --}}
                        <tr>
                            <td>
                                <strong class="text-small">
                                    TOTAL CIRUGÍAS (Realizadas "prog" + urgencias)
                                </strong>
                            </td>
                            <td class="text-right">
                                <strong>{{ $total_cirugias_realizadas ?? 0 }}</strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    {{-- CONSULTA EXTERNA, AUTOCLAVES, DEFUNCIONES --}}
    <div class="section">
        <h2 class="mb-1">Consulta externa, autoclaves y defunciones</h2>

        <h3 class="mt-2 mb-1">Clínica de catéteres (acumulado)</h3>
        <table class="mb-2">
            @foreach([
                'cat_medial' => 'Medial',
                'cat_picc' => 'PICC',
                'cat_umbilical' => 'Umbilical',
                'cat_asepsia' => 'Asepsia',
                'cat_periferico_corto' => 'Periférico corto',
                'cat_cvc' => 'CVC',
            ] as $key => $label)
                <tr>
                    <td><span class="text-small text-muted">{{ $label }}</span></td>
                    <td class="text-right">{{ $out_totales[$key] ?? 0 }}</td>
                </tr>
            @endforeach
        </table>

        <h3 class="mt-2 mb-1">Heridas (acumulado)</h3>
        <table class="mb-2">
            @foreach([
                'her_curaciones'          => 'Curaciones',
                'her_interconsultas'      => 'Interconsultas',
                'her_valoraciones'        => 'Valoraciones',
                'her_cuidados_especiales' => 'Cuidados especiales',
                'her_vac'                 => 'VAC',
            ] as $key => $label)
                <tr>
                    <td><span class="text-small text-muted">{{ $label }}</span></td>
                    <td class="text-right">{{ $out_totales[$key] ?? 0 }}</td>
                </tr>
            @endforeach
        </table>

        <h3 class="mt-2 mb-1">Lactancia (acumulado)</h3>
        <table class="mb-2">
            @foreach([
                'lac_asesorias'  => 'Asesorías',
                'lac_autoclaves' => 'Autoclaves',
                'lac_fracciones' => 'Fracciones',
            ] as $key => $label)
                <tr>
                    <td><span class="text-small text-muted">{{ $label }}</span></td>
                    <td class="text-right">{{ $out_totales[$key] ?? 0 }}</td>
                </tr>
            @endforeach
        </table>

        <h3 class="mt-2 mb-1">Endoscopías (acumulado)</h3>
        <table class="mb-2">
            @foreach([
                'end_endoscopias'   => 'Endoscopías',
                'end_colonoscopias' => 'Colonoscopías',
                'end_biopsias'      => 'Biopsias',
                'end_cepres'        => 'CEPRES',
            ] as $key => $label)
                <tr>
                    <td><span class="text-small text-muted">{{ $label }}</span></td>
                    <td class="text-right">{{ $out_totales[$key] ?? 0 }}</td>
                </tr>
            @endforeach
        </table>

        <h3 class="mt-2 mb-1">Autoclaves (acumulado)</h3>
        <table class="mb-2">
            <tr>
                <td><span class="text-small text-muted">CEYE</span></td>
                <td class="text-right">{{ $auto_totales['ceye'] ?? 0 }}</td>
            </tr>
            <tr>
                <td><span class="text-small text-muted">SubCEYE</span></td>
                <td class="text-right">{{ $auto_totales['subceye'] ?? 0 }}</td>
            </tr>
        </table>

        <h3 class="mt-2 mb-1">Defunciones (acumulado)</h3>
        <p class="card-value">{{ $total_defunciones }}</p>
        <p class="text-small text-muted">
            Valor consolidado para el turno y periodo. Relevante para COCASEP, AESP y comités de calidad.
        </p>
    </div>

    {{-- RRHH --}}
    <div class="section">
        <h2 class="mb-1">Recurso humano</h2>

        <h3 class="mt-2 mb-1">Asistencia por categoría (sin pasantes)</h3>
        <table class="mb-2">
            @foreach($asistencia ?? [] as $key => $value)
                <tr>
                    <td><span class="text-small text-muted">{{ Str::of($key)->replace('_',' ')->title() }}</span></td>
                    <td class="text-right">{{ $value }}</td>
                </tr>
            @endforeach
        </table>

        <h3 class="mt-2 mb-1">Incidencias</h3>
        <table class="mb-2">
            @foreach($incidencias ?? [] as $key => $value)
                <tr>
                    <td><span class="text-small text-muted">{{ Str::of($key)->replace('_',' ')->title() }}</span></td>
                    <td class="text-right">{{ $value }}</td>
                </tr>
            @endforeach
        </table>

        <h3 class="mt-2 mb-1">Estadística descriptiva RRHH</h3>
        @if(!empty($estadistica_rrhh))
            <table>
                <thead>
                    <tr>
                        <th>Indicador</th>
                        <th class="text-right">Media</th>
                        <th class="text-right">Mediana</th>
                        <th class="text-right">Moda</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Asistencia total por día</td>
                        <td class="text-right">{{ $estadistica_rrhh['asistencia_media'] ?? '-' }}</td>
                        <td class="text-right">{{ $estadistica_rrhh['asistencia_mediana'] ?? '-' }}</td>
                        <td class="text-right">{{ $estadistica_rrhh['asistencia_moda'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Incidencias totales por día</td>
                        <td class="text-right">{{ $estadistica_rrhh['incidencias_media'] ?? '-' }}</td>
                        <td class="text-right">{{ $estadistica_rrhh['incidencias_mediana'] ?? '-' }}</td>
                        <td class="text-right">{{ $estadistica_rrhh['incidencias_moda'] ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p class="text-small text-muted">
                No hay registros suficientes para calcular media, mediana y moda.
            </p>
        @endif
    </div>

    {{-- ANEXO: NOTAS DE TURNO (OPCIONAL) --}}
    @if($includeNotes && $shiftNotes->isNotEmpty())
        <div class="section" style="page-break-before: always;">
            <h2 class="mb-2">Anexo: Notas de turno</h2>
            <p class="text-small text-muted mb-2">
                Observaciones narrativas registradas por las jefaturas de servicio y subjefatura durante el periodo del informe.
            </p>

            <table>
                <thead>
                    <tr>
                        <th style="width: 16%;">Fecha</th>
                        <th style="width: 10%;">Turno</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shiftNotes as $noteShift)
                        <tr>
                            <td class="text-small">
                                {{ \Carbon\Carbon::parse($noteShift->date)->format('d/m/Y') }}
                            </td>
                            <td class="text-small">
                                {{ $noteShift->shift->name ?? $noteShift->shift->code }}
                            </td>
                            <td class="text-small" style="white-space: pre-line;">
                                {{ $noteShift->note }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</body>
</html>
