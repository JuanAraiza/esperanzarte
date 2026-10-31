<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ((int) $request->user()->tipo === 3) {
            return redirect()->route('entregasmateriales.index');
        }

        $hoy = Carbon::today();
        $fechaInicio = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : $hoy->copy()->subDays(29)->startOfDay();
        $fechaFin = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : $hoy->copy()->endOfDay();

        if ($fechaInicio->gt($fechaFin)) {
            [$fechaInicio, $fechaFin] = [$fechaFin->copy()->startOfDay(), $fechaInicio->copy()->endOfDay()];
        }

        $asistenciasPorDia = DB::table('asistencias')
            ->selectRaw('dia, COUNT(*) as total')
            ->whereBetween('dia', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
            ->groupBy('dia')
            ->orderBy('dia')
            ->pluck('total', 'dia');

        $periodo = CarbonPeriod::create($fechaInicio->copy()->startOfDay(), $fechaFin->copy()->startOfDay());
        $lineLabels = [];
        $lineValues = [];
        foreach ($periodo as $fecha) {
            $llave = $fecha->format('Y-m-d');
            $lineLabels[] = $fecha->format('d/m');
            $lineValues[] = (int) ($asistenciasPorDia[$llave] ?? 0);
        }

        $topTalleres = DB::table('asistencias as a')
            ->join('clase_asignadas as ca', 'ca.id', '=', 'a.clase_asig_id')
            ->leftJoin('tallers as t', 't.id', '=', 'ca.taller')
            ->selectRaw("COALESCE(t.nombre, CONCAT('Taller #', ca.taller)) as nombre, COUNT(*) as total")
            ->whereBetween('a.dia', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
            ->groupBy('ca.taller', 't.nombre')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $topCentros = DB::table('asistencias as a')
            ->join('clase_asignadas as ca', 'ca.id', '=', 'a.clase_asig_id')
            ->leftJoin('centros as c', 'c.id', '=', 'ca.centro')
            ->selectRaw("COALESCE(c.centro, CONCAT('Centro #', ca.centro)) as nombre, COUNT(*) as total")
            ->whereBetween('a.dia', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
            ->groupBy('ca.centro', 'c.centro')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $topEdades = DB::table('asistencias as a')
            ->join('alumnos as al', 'al.id', '=', 'a.alumno_id')
            ->selectRaw("al.edad as edad, COUNT(*) as total")
            ->whereBetween('a.dia', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
            ->whereNotNull('al.edad')
            ->groupBy('al.edad')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $generoAsistencias = DB::table('asistencias as a')
            ->join('alumnos as al', 'al.id', '=', 'a.alumno_id')
            ->selectRaw("al.sexo as sexo, COUNT(*) as total")
            ->whereBetween('a.dia', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
            ->whereNotNull('al.sexo')
            ->groupBy('al.sexo')
            ->get();

        $mapaCentros = DB::table('centros')
            ->select('id', 'centro', 'latitud', 'longitud', 'direccion', 'telefono')
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->whereRaw("TRIM(latitud) <> ''")
            ->whereRaw("TRIM(longitud) <> ''")
            ->where('deshabilitado', '!=', 1)
            ->get()
            ->filter(fn ($centro) => is_numeric($centro->latitud) && is_numeric($centro->longitud))
            ->map(fn ($centro) => [
                'id' => $centro->id,
                'latitud' => (float) $centro->latitud,
                'longitud' => (float) $centro->longitud,
                'centro' => $centro->centro ?: 'Centro sin nombre',
                'direccion' => $centro->direccion ?: 'Dirección no disponible',
                'telefono' => $centro->telefono ?: 'Teléfono no disponible',
            ])
            ->values();
            

        $totalAsistencias = array_sum($lineValues);

        return view('home', [
            'startDate' => $fechaInicio->toDateString(),
            'endDate' => $fechaFin->toDateString(),
            'lineLabels' => $lineLabels,
            'lineValues' => $lineValues,
            'topTalleresLabels' => $topTalleres->pluck('nombre')->values(),
            'topTalleresValues' => $topTalleres->pluck('total')->map(fn ($v) => (int) $v)->values(),
            'topCentrosLabels' => $topCentros->pluck('nombre')->values(),
            'topCentrosValues' => $topCentros->pluck('total')->map(fn ($v) => (int) $v)->values(),
            'topEdadesLabels' => $topEdades->pluck('edad')->map(fn ($e) => "Edad: {$e}")->values(),
            'topEdadesValues' => $topEdades->pluck('total')->map(fn ($v) => (int) $v)->values(),
            'generoLabels' => $generoAsistencias->pluck('sexo')->values(),
            'generoValues' => $generoAsistencias->pluck('total')->map(fn ($v) => (int) $v)->values(),
            'mapaCentros' => $mapaCentros,
            'totalAsistencias' => $totalAsistencias,
        ]);
    }
}
