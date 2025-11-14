<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyShift;
use App\Models\WardStat;
use App\Models\Service;
use App\Models\TocoStat;
use App\Models\QuirofanoStat;
use App\Models\OutpatientStat;
use App\Models\AutoclaveStat;
use App\Models\DefuncionStat;
use App\Models\HumanResourceStat;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('informes.index');
    }

    /**
     * Vista HTML del informe por periodo.
     */
    public function generate(Request $request)
    {
        // Unificamos toda la lógica en prepareReportData
        $data = $this->prepareReportData($request);

        return view('informes.resultados', $data);
    }

    /**
     * Descarga del PDF del informe por periodo.
     */
    public function pdf(Request $request)
    {
        $data = $this->prepareReportData($request);

        $fileName = 'informe_' . $data['from'] . '_' . $data['to'] . '_' . $data['shiftCode'] . '.pdf';

        $pdf = Pdf::loadView('informes.pdf', $data)->setPaper('letter', 'portrait');

        return $pdf->download($fileName);
    }

    /**
     * Arma todos los datos estadísticos para el informe
     * y los devuelve en un arreglo listo para pasar a la vista/PDF.
     *
     * Reglas clave:
     * - Turno: M, V o A (Ambos).
     * - Pacientes por servicio = PROMEDIO DIARIO en el periodo (no suma).
     * - Ocupación hospitalaria = Σ promedio_servicio / Σ capacidad_servicio.
     * - Actividades de productividad (tiras, caídas, toco, Qx, consulta, autoclaves, defunciones)
     *   sí se suman como acumulado.
     * - RRHH excluye PASANTES en los cálculos de asistencia.
     * - En RRHH se calculan media, mediana y moda por día/turno (el cómo mostrarlas se controla en la vista).
     */
    private function prepareReportData(Request $request): array
    {
        $validated = $request->validate([
            'from'  => 'required|date',
            'to'    => 'required|date',
            'shift' => 'required|string', // 'M', 'V' o 'A'
        ]);

        $from      = $validated['from'];
        $to        = $validated['to'];
        $shiftCode = $validated['shift'];
        $includeNotes = $request->boolean('include_notes');

        /**
         * 1) DailyShifts en el rango y según el turno:
         *    - M o V: solo ese turno.
         *    - A (Ambos): se toman todos los turnos del rango (M y V).
         */
        $dailyShiftsQuery = DailyShift::whereBetween('date', [$from, $to]);

        if ($shiftCode !== 'A') {
            $dailyShiftsQuery->whereHas('shift', function ($q) use ($shiftCode) {
                $q->where('code', $shiftCode);
            });
        }

        $dailyShifts   = $dailyShiftsQuery->get();
        $dailyShiftIds = $dailyShifts->pluck('id');

        // número de días distintos en el periodo para ese turno (M, V o A)
        $numDias = $dailyShifts->unique('date')->count() ?: 1;

        $shiftNotes = collect();
        if ($includeNotes && $dailyShiftIds->isNotEmpty()) {
            $shiftNotes = DailyShift::with('shift')
                ->whereIn('id', $dailyShiftIds)
                ->whereNotNull('note')
                ->orderBy('date')
                ->orderBy('shift_id')
                ->get();
        }

        if ($dailyShiftIds->isEmpty()) {
            return [
                'from'                      => $from,
                'to'                        => $to,
                'shiftCode'                 => $shiftCode,
                'includeNotes'              => $includeNotes,
                'shiftNotes'                => $shiftNotes,
                'serviceStats'              => [],
                'hospital_occupancy'        => null,
                'toco_totales'              => [],
                'qf_totales'                => [],
                'total_cirugias_realizadas' => 0,
                'out_totales'               => [],
                'auto_totales'              => [],
                'total_defunciones'         => 0,
                'asistencia'                => collect(),
                'incidencias'               => collect(),
                'estadistica_rrhh'          => [],
                'asistencia_total'          => 0,
                'incidencias_total'         => 0,
                'total_general_rrhh'        => 0,
                'porcentaje_asistencia'     => null,
                'porcentaje_inasistencia'   => null,
            ];
        }

        /**
         * 2) Productividad por servicio
         *
         * Pacientes = PROMEDIO DIARIO por servicio en el periodo (no suma directa).
         * Ocupación por servicio = promedio_diario / capacidad_instalada.
         * Ocupación hospitalaria = Σ promedio_servicio / Σ capacidad_servicio.
         * Además, se agrega una fila TOTAL al final.
         */
        $services     = Service::orderBy('id')->get();
        $serviceStats = [];

        // Acumuladores para el TOTAL HOSPITALARIO
        $sumCapacidad         = 0;
        $sumPromedioPacientes = 0;
        $sumCaidas            = 0;
        $sumTiras             = 0;
        $sumGraves            = 0;
        $sumTubos             = 0;

        foreach ($services as $service) {
            $stats = WardStat::where('service_id', $service->id)
                ->whereIn('daily_shift_id', $dailyShiftIds)
                ->get();

            // Totales en el periodo
            $totalPacientes     = $stats->sum('pacientes');
            $totalCaidas        = $stats->sum('caidas');
            $totalTiras         = $stats->sum('tiras');
            $totalGravesPeriodo = $stats->sum('graves');
            $totalTubosPeriodo  = $stats->sum('tubos');

            // Promedios diarios por servicio
            $promPacientes = $numDias > 0 ? $totalPacientes     / $numDias : 0;
            $promGraves    = $numDias > 0 ? $totalGravesPeriodo / $numDias : 0;
            $promTubos     = $numDias > 0 ? $totalTubosPeriodo  / $numDias : 0;

            // Ocupación basada en el promedio diario de pacientes
            $porcentajeOcupacion = null;
            if ($service->installed_capacity > 0) {
                $porcentajeOcupacion = round(($promPacientes / $service->installed_capacity) * 100, 1);
            }

            // Fila del servicio
            $serviceStats[] = [
                'service'              => $service,
                'total_pacientes'      => round($promPacientes, 1),   // PROMEDIO diario
                'total_caidas'         => $totalCaidas,               // ACUMULADO
                'total_tiras'          => $totalTiras,                // ACUMULADO
                'total_graves'         => round($promGraves, 1),      // PROMEDIO diario
                'total_tubos'          => round($promTubos, 1),       // PROMEDIO diario
                'porcentaje_ocupacion' => $porcentajeOcupacion,
                'is_total'             => false,
            ];

            // Acumuladores para TOTAL HOSPITALARIO
            $sumCapacidad         += $service->installed_capacity ?? 0;
            $sumPromedioPacientes += $promPacientes;
            $sumCaidas            += $totalCaidas;
            $sumTiras             += $totalTiras;
            $sumGraves            += $promGraves;
            $sumTubos             += $promTubos;
        }

        // Ocupación hospitalaria con base en los promedios por servicio
        $hospital_occupancy = $sumCapacidad > 0
            ? round(($sumPromedioPacientes / $sumCapacidad) * 100, 1)
            : null;

        // Fila TOTAL al final de la tabla
        $serviceStats[] = [
            'service'              => null,
            'is_total'             => true,
            'label'                => 'TOTAL HOSPITALARIO',
            // Suma de promedios de pacientes por servicio = "promedio hospitalario"
            'total_pacientes'      => round($sumPromedioPacientes, 1),
            // Sumas globales de eventos
            'total_caidas'         => $sumCaidas,
            'total_tiras'          => $sumTiras,
            // Suma de los promedios de graves/tubos por servicio
            'total_graves'         => round($sumGraves, 1),
            'total_tubos'          => round($sumTubos, 1),
            'porcentaje_ocupacion' => $hospital_occupancy,
        ];

        /**
         * 3) TOCO – acumulado en el periodo.
         */
        $toco = TocoStat::whereIn('daily_shift_id', $dailyShiftIds)->get();
        $toco_totales = [
            'partos'                => $toco->sum('partos'),
            'cesareas'              => $toco->sum('cesareas'),
            'rn_vivos'              => $toco->sum('rn_vivos'),
            'piel_a_piel'           => $toco->sum('piel_a_piel'),
            'obitos'                => $toco->sum('obitos'),
            'legrados'              => $toco->sum('legrados'),
            'otb'                   => $toco->sum('otb'),
            'rev_cavidad'           => $toco->sum('rev_cavidad'),
            'histerectomia'         => $toco->sum('histerectomia'),
            'plastias'              => $toco->sum('plastias'),
            'analgesias'            => $toco->sum('analgesias'),
            'emergencia_obstetrica' => $toco->sum('emergencia_obstetrica'),
            'consulta'              => $toco->sum('consulta'),
        ];

        /**
         * 4) Quirófanos – acumulados, salvo salas_trabajando (promedio).
         */
        $qf = QuirofanoStat::whereIn('daily_shift_id', $dailyShiftIds)->get();
        $qf_totales = [
            'programadas'      => $qf->sum('programadas'),
            'realizadas'       => $qf->sum('realizadas'),
            'suspendidas'      => $qf->sum('suspendidas'),
            'urgencias'        => $qf->sum('urgencias'),
            'pendientes'       => $qf->sum('pendientes'),
            'contaminadas'     => $qf->sum('contaminadas'),
            'salas_trabajando' => $qf->count() > 0 ? round($qf->avg('salas_trabajando'), 1) : 0,
        ];

        // Total general de cirugías realizadas = realizadas (programadas) + urgentes
        $total_cirugias_realizadas = $qf_totales['realizadas'] + $qf_totales['urgencias'];

        /**
         * 5) Consulta externa – acumulados.
         */
        $out = OutpatientStat::whereIn('daily_shift_id', $dailyShiftIds)->get();
        $out_totales = [
            'cat_medial'              => $out->sum('cat_medial'),
            'cat_picc'                => $out->sum('cat_picc'),
            'cat_umbilical'           => $out->sum('cat_umbilical'),
            'cat_asepsia'             => $out->sum('cat_asepsia'),
            'cat_periferico_corto'    => $out->sum('cat_periferico_corto'),
            'cat_cvc'                 => $out->sum('cat_cvc'),

            'her_curaciones'          => $out->sum('her_curaciones'),
            'her_interconsultas'      => $out->sum('her_interconsultas'),
            'her_valoraciones'        => $out->sum('her_valoraciones'),
            'her_cuidados_especiales' => $out->sum('her_cuidados_especiales'),
            'her_vac'                 => $out->sum('her_vac'),

            'lac_asesorias'           => $out->sum('lac_asesorias'),
            'lac_autoclaves'          => $out->sum('lac_autoclaves'),
            'lac_fracciones'          => $out->sum('lac_fracciones'),

            'end_endoscopias'         => $out->sum('end_endoscopias'),
            'end_colonoscopias'       => $out->sum('end_colonoscopias'),
            'end_biopsias'            => $out->sum('end_biopsias'),
            'end_cepres'              => $out->sum('end_cepres'),
        ];

        /**
         * 6) Autoclaves – acumulados.
         */
        $auto = AutoclaveStat::whereIn('daily_shift_id', $dailyShiftIds)->get();
        $auto_totales = [
            'ceye'    => $auto->sum('ceye'),
            'subceye' => $auto->sum('subceye'),
        ];

        /**
         * 7) Defunciones – acumulado.
         */
        $def = DefuncionStat::whereIn('daily_shift_id', $dailyShiftIds)->get();
        $total_defunciones = $def->sum('total_defunciones');

        /**
         * 8) RRHH – excluyendo PASANTES de asistencia.
         */
        $rrhh = HumanResourceStat::whereIn('daily_shift_id', $dailyShiftIds)->get();

        $asistencia_keys = [
            'subjefatura',
            'supervision',
            'jefes_servicio',
            'enfermeria_general',
            'enfermeria_auxiliar',
            // pasantes excluidos
        ];

        $incidencia_keys = [
            'descansos_obligatorios',
            'incapacidades',
            'faltas',
            'vacaciones',
            'becas',
            'permisos_sindicales',
            'permiso_tiempo',
        ];

        $asistencia = collect($asistencia_keys)->mapWithKeys(function ($key) use ($rrhh) {
            return [$key => round($rrhh->avg($key), 1)];
        });

        $incidencias = collect($incidencia_keys)->mapWithKeys(function ($key) use ($rrhh) {
            return [$key => $rrhh->sum($key)];
        });

        $asistencia_total   = $asistencia->sum();
        $incidencias_total  = $incidencias->sum();
        $total_general_rrhh = $asistencia_total + $incidencias_total;

        $porcentaje_asistencia = $total_general_rrhh > 0
            ? round(($asistencia_total / $total_general_rrhh) * 100, 1)
            : null;

        $porcentaje_inasistencia = $total_general_rrhh > 0
            ? round(($incidencias_total / $total_general_rrhh) * 100, 1)
            : null;

        $estadistica_rrhh = [];

        if ($rrhh->isNotEmpty()) {
            $totales_asistencia_por_fila = $rrhh->map(fn($row) =>
                array_sum($row->only($asistencia_keys))
            );

            $totales_incidencias_por_fila = $rrhh->map(fn($row) =>
                array_sum($row->only($incidencia_keys))
            );

            $estadistica_rrhh = [
                'asistencia_media'    => round($totales_asistencia_por_fila->avg(), 1),
                'asistencia_mediana'  => $totales_asistencia_por_fila->median(),
                'asistencia_moda'     => optional($totales_asistencia_por_fila->mode())->first(),

                'incidencias_media'   => round($totales_incidencias_por_fila->avg(), 1),
                'incidencias_mediana' => $totales_incidencias_por_fila->median(),
                'incidencias_moda'    => optional($totales_incidencias_por_fila->mode())->first(),
            ];
        }

        return [
            'from'                      => $from,
            'to'                        => $to,
            'shiftCode'                 => $shiftCode,
            'includeNotes'              => $includeNotes,
            'shiftNotes'                => $shiftNotes,
            'serviceStats'              => $serviceStats,
            'hospital_occupancy'        => $hospital_occupancy,
            'toco_totales'              => $toco_totales,
            'qf_totales'                => $qf_totales,
            'total_cirugias_realizadas' => $total_cirugias_realizadas,
            'out_totales'               => $out_totales,
            'auto_totales'              => $auto_totales,
            'total_defunciones'         => $total_defunciones,
            'asistencia'                => $asistencia,
            'incidencias'               => $incidencias,
            'asistencia_total'          => $asistencia_total,
            'incidencias_total'         => $incidencias_total,
            'total_general_rrhh'        => $total_general_rrhh,
            'porcentaje_asistencia'     => $porcentaje_asistencia,
            'porcentaje_inasistencia'   => $porcentaje_inasistencia,
            'estadistica_rrhh'          => $estadistica_rrhh,
        ];
    }
}
