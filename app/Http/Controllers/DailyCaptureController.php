<?php

namespace App\Http\Controllers;

use App\Models\DailyShift;
use App\Models\Shift;
use App\Models\Service;
use App\Models\WardStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TocoStat;
use App\Models\QuirofanoStat;
use App\Models\OutpatientStat;
use App\Models\AutoclaveStat;
use App\Models\DefuncionStat;
use App\Models\HumanResourceStat;




class DailyCaptureController extends Controller
{
    public function index(Request $request)
    {
        
        $date = $request->input('date', now()->toDateString());
        $shiftCode = $request->input('shift', 'M');
        

        $shift = Shift::firstOrCreate(
            ['code' => $shiftCode],
            ['name' => $shiftCode === 'M' ? 'Matutino' : 'Vespertino']
        );

        $dailyShift = DailyShift::firstOrCreate(
            ['date' => $date, 'shift_id' => $shift->id],
            ['user_id' => Auth::id() ?? 1] // si no hay auth, puedes poner un id fijo temporal
        );

        $serviceOrder = [
            'UCIN',
            'CIRUGÍA GENERAL',
            'GINECOLOGIA-OBSTETRICIA',
            'UCINEX',
            'ESCOLARES',
            'LACTANTES',
            'MEDICINA INTERNA',
            'INFECTOLOGIA',
            'URGENCIAS PEDIATRIA',
            'HIDRATACION ORAL',
            'URGENCIAS ADULTOS',
            'TOCO CIRUGIA',
            'HABITACION CONJUNTA',
            'UTIP',
            'UTI',
            'UCI',
            'RECUPERACION QX GERIATRIA',
            'RECUPERACION QX 5TO PISO',
        ];

        // Traer solo esos servicios
        $services = Service::whereIn('name', $serviceOrder)->get();

        // Ordenarlos en memoria según el arreglo anterior
        $services = $services->sortBy(function ($service) use ($serviceOrder) {
            return array_search($service->name, $serviceOrder);
        })->values();

        $wardStats = WardStat::where('daily_shift_id', $dailyShift->id)
            ->get()
            ->keyBy('service_id');

        $toco = TocoStat::firstOrNew([
            'daily_shift_id' => $dailyShift->id,
        ]);

        $quirofano = QuirofanoStat::firstOrNew([
            'daily_shift_id' => $dailyShift->id,
        ]);

        $outpatient = OutpatientStat::firstOrNew([
            'daily_shift_id' => $dailyShift->id,
        ]);

        $autoclaves = AutoclaveStat::firstOrNew([
            'daily_shift_id' => $dailyShift->id,
        ]);

        $defunciones = DefuncionStat::firstOrNew([
            'daily_shift_id' => $dailyShift->id,
        ]);

        $rrhh = HumanResourceStat::firstOrNew([
            'daily_shift_id' => $dailyShift->id,
        ]);

        return view('captura.index', [
            'date'       => $date,
            'shift'      => $shift,
            'dailyShift' => $dailyShift,
            'services'   => $services,
            'wardStats'  => $wardStats,
            'toco'       => $toco,
            'quirofano'  => $quirofano,
            'outpatient' => $outpatient,
            'autoclaves' => $autoclaves,
            'defunciones'  => $defunciones,
            'rrhh' => $rrhh,
        ]);
    }

    public function saveWard(Request $request)
    {
        $request->validate([
            'daily_shift_id' => 'required|exists:daily_shifts,id',
            'services'       => 'array',
        ]);

        $dailyShiftId = $request->input('daily_shift_id');

        foreach ($request->input('services', []) as $serviceId => $data) {
            WardStat::updateOrCreate(
                [
                    'daily_shift_id' => $dailyShiftId,
                    'service_id'     => $serviceId,
                ],
                [
                    'pacientes' => $data['pacientes'] ?? null,
                    'caidas'    => $data['caidas'] ?? null,
                    'tiras'     => $data['tiras'] ?? null,
                    'graves'    => $data['graves'] ?? null,
                    'tubos'     => $data['tubos'] ?? null,
                ]
            );
        }

        return back()->with('status', 'Productividad por servicio guardada correctamente.');
    }

    public function saveToco(Request $request)
    {
        $data = $request->validate([
            'daily_shift_id'        => 'required|exists:daily_shifts,id',
            'partos'                => 'nullable|integer|min:0',
            'cesareas'              => 'nullable|integer|min:0',
            'rn_vivos'              => 'nullable|integer|min:0',
            'piel_a_piel'           => 'nullable|integer|min:0',
            'obitos'                => 'nullable|integer|min:0',
            'legrados'              => 'nullable|integer|min:0',
            'otb'                   => 'nullable|integer|min:0',
            'rev_cavidad'           => 'nullable|integer|min:0',
            'histerectomia'         => 'nullable|integer|min:0',
            'plastias'              => 'nullable|integer|min:0',
            'analgesias'            => 'nullable|integer|min:0',
            'emergencia_obstetrica' => 'nullable|integer|min:0',
            'consulta'              => 'nullable|integer|min:0',
        ]);

        TocoStat::updateOrCreate(
            ['daily_shift_id' => $data['daily_shift_id']],
            $data
        );

        return back()->with('status', 'Productividad de TOCO Cirugía guardada correctamente.');
    }

    public function saveQuirofano(Request $request)
    {
        $data = $request->validate([
            'daily_shift_id'   => 'required|exists:daily_shifts,id',
            'programadas'      => 'nullable|integer|min:0',
            'realizadas'       => 'nullable|integer|min:0',
            'suspendidas'      => 'nullable|integer|min:0',
            'urgencias'        => 'nullable|integer|min:0',
            'pendientes'       => 'nullable|integer|min:0',
            'contaminadas'     => 'nullable|integer|min:0',
            'salas_trabajando' => 'nullable|integer|min:0',
        ]);

        QuirofanoStat::updateOrCreate(
            ['daily_shift_id' => $data['daily_shift_id']],
            $data
        );

        return back()->with('status', 'Productividad de quirófanos guardada correctamente.');
    }

    public function saveOutpatient(Request $request)
    {
        $data = $request->validate([
            'daily_shift_id'          => 'required|exists:daily_shifts,id',

            'cat_medial'              => 'nullable|integer|min:0',
            'cat_picc'                => 'nullable|integer|min:0',
            'cat_umbilical'           => 'nullable|integer|min:0',
            'cat_asepsia'             => 'nullable|integer|min:0',
            'cat_periferico_corto'    => 'nullable|integer|min:0',
            'cat_cvc'                 => 'nullable|integer|min:0',

            'her_curaciones'          => 'nullable|integer|min:0',
            'her_interconsultas'      => 'nullable|integer|min:0',
            'her_valoraciones'        => 'nullable|integer|min:0',
            'her_cuidados_especiales' => 'nullable|integer|min:0',
            'her_vac'                 => 'nullable|integer|min:0',

            'lac_asesorias'           => 'nullable|integer|min:0',
            'lac_autoclaves'          => 'nullable|integer|min:0',
            'lac_fracciones'          => 'nullable|integer|min:0',

            'end_endoscopias'         => 'nullable|integer|min:0',
            'end_colonoscopias'       => 'nullable|integer|min:0',
            'end_biopsias'            => 'nullable|integer|min:0',
            'end_cepres'              => 'nullable|integer|min:0',
        ]);

        OutpatientStat::updateOrCreate(
            ['daily_shift_id' => $data['daily_shift_id']],
            $data
        );

        return back()->with('status', 'Productividad de consulta externa guardada correctamente.');
    }

    public function saveAutoclaves(Request $request)
    {
        $data = $request->validate([
            'daily_shift_id' => 'required|exists:daily_shifts,id',
            'ceye'           => 'nullable|integer|min:0',
            'subceye'        => 'nullable|integer|min:0',
        ]);

        AutoclaveStat::updateOrCreate(
            ['daily_shift_id' => $data['daily_shift_id']],
            $data
        );

        return back()->with('status', 'Productividad de autoclaves guardada correctamente.');
    }

    public function saveDefunciones(Request $request)
    {
        $data = $request->validate([
            'daily_shift_id'    => 'required|exists:daily_shifts,id',
            'total_defunciones' => 'nullable|integer|min:0',
        ]);

        DefuncionStat::updateOrCreate(
            ['daily_shift_id' => $data['daily_shift_id']],
            $data
        );

        return back()->with('status', 'Total de defunciones guardado correctamente.');
    }
    
    public function saveHumanResources(Request $request)
    {
        $data = $request->validate([
            'daily_shift_id'        => 'required|exists:daily_shifts,id',

            // ASISTENCIA
            'subjefatura'           => 'nullable|integer|min:0',
            'supervision'           => 'nullable|integer|min:0',
            'jefes_servicio'        => 'nullable|integer|min:0',
            'enfermeria_general'    => 'nullable|integer|min:0',
            'enfermeria_auxiliar'   => 'nullable|integer|min:0',
            'pasantes'              => 'nullable|integer|min:0',

            // INCIDENCIAS
            'descansos_obligatorios'=> 'nullable|integer|min:0',
            'incapacidades'         => 'nullable|integer|min:0',
            'faltas'                => 'nullable|integer|min:0',
            'vacaciones'            => 'nullable|integer|min:0',
            'becas'                 => 'nullable|integer|min:0',
            'permisos_sindicales'   => 'nullable|integer|min:0',
            'permiso_tiempo'        => 'nullable|integer|min:0',
        ]);

        HumanResourceStat::updateOrCreate(
            ['daily_shift_id' => $data['daily_shift_id']],
            $data
        );

        return back()->with('status', 'Gestión de recurso humano guardada correctamente.');
    }
    
    public function saveNote(Request $request)
    {
        $data = $request->validate([
            'daily_shift_id' => 'required|exists:daily_shifts,id',
            'note'           => 'nullable|string|max:5000',
        ]);

        $dailyShift = DailyShift::findOrFail($data['daily_shift_id']);
        $dailyShift->note = $data['note'];
        $dailyShift->save();

        return back()->with('status', 'Nota del turno actualizada correctamente.');
    }
}
