<?php

namespace App\Http\Controllers;

use App\Models\docente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function docentes(Request $request)
    {
        $docentes = $this->obtenerDocentesActivos();
        $filtros = $this->validarFiltros($request);
        $puedeConsultar = $this->puedeConsultar($filtros);
        $reporte = $puedeConsultar ? $this->consultarReporteDocentes($filtros) : collect();
        $resumen = $this->construirResumen($reporte);
        $asistentesPorSesion = $puedeConsultar ? $this->consultarAsistentesPorSesion($filtros) : collect();

        return view('reportes.docentes', [
            'docentes' => $docentes,
            'filtros' => $filtros,
            'reporte' => $reporte,
            'resumen' => $resumen,
            'asistentesPorSesion' => $asistentesPorSesion,
            'puedeConsultar' => $puedeConsultar,
        ]);
    }

    public function docentesPdf(Request $request)
    {
        $filtros = $this->validarFiltros($request);
        if (!$this->puedeConsultar($filtros)) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Filtros incompletos',
                'text' => 'Selecciona docente y rango de fechas para generar el oficio en PDF.',
            ]);

            return redirect()->route('reportes.docentes');
        }

        $reporte = $this->consultarReporteDocentes($filtros);
        $resumen = $this->construirResumen($reporte);
        $docenteSeleccionado = docente::find($filtros['docente_id']);
        $logoRelativo = config('adminlte.logo_img', 'assets/img/palomita.png');
        $logoInstitucional = public_path($logoRelativo);
        if (!file_exists($logoInstitucional)) {
            $logoInstitucional = public_path('assets/img/palomita.png');
        }

        $pdf = Pdf::loadView('reportes.docentes_pdf', [
            'filtros' => $filtros,
            'reporte' => $reporte,
            'resumen' => $resumen,
            'docenteSeleccionado' => $docenteSeleccionado,
            'fechaEmision' => now(),
            'logoInstitucional' => $logoInstitucional,
            'nombreInstitucion' => 'Esperanzarte',
        ])->setPaper('letter', 'portrait');

        return $pdf->stream('oficio-comprobacion-docente.pdf');
    }

    private function obtenerDocentesActivos()
    {
        return docente::where('deshabilitado', 0)
            ->orderBy('nombre')
            ->orderBy('paterno')
            ->orderBy('materno')
            ->get();
    }

    private function validarFiltros(Request $request): array
    {
        return $request->validate([
            'docente_id' => 'nullable|integer|exists:docentes,id',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);
    }

    private function puedeConsultar(array $filtros): bool
    {
        return !empty($filtros['docente_id'])
            && !empty($filtros['fecha_inicio'])
            && !empty($filtros['fecha_fin']);
    }

    private function consultarReporteDocentes(array $filtros)
    {
        return DB::table('asistencias as a')
            ->join('docentes as d', 'd.id', '=', 'a.docente_id')
            ->join('clase_asignadas as ca', 'ca.id', '=', 'a.clase_asig_id')
            ->leftJoin('tallers as t', 't.id', '=', 'ca.taller')
            ->leftJoin('centros as c', 'c.id', '=', 'ca.centro')
            ->select(
                'a.dia',
                'ca.id as clase_asignada_id',
                'ca.taller as taller_id',
                DB::raw("TRIM(CONCAT(COALESCE(d.nombre, ''), ' ', COALESCE(d.paterno, ''), ' ', COALESCE(d.materno, ''))) as docente_nombre"),
                DB::raw("COALESCE(t.nombre, CONCAT('Taller #', ca.taller)) as taller_nombre"),
                DB::raw("COALESCE(c.centro, CONCAT('Centro #', ca.centro)) as centro_nombre"),
                DB::raw('COUNT(DISTINCT a.alumno_id) as total_alumnos')
            )
            ->where('a.docente_id', $filtros['docente_id'])
            ->whereBetween('a.dia', [$filtros['fecha_inicio'], $filtros['fecha_fin']])
            ->groupBy(
                'a.dia',
                'ca.id',
                'ca.taller',
                'd.nombre',
                'd.paterno',
                'd.materno',
                't.nombre',
                'c.centro',
                'ca.centro'
            )
            ->orderBy('a.dia')
            ->orderBy('taller_nombre')
            ->get();
    }

    private function construirResumen($reporte): array
    {
        return [
            'sesiones' => $reporte->count(),
            'alumnos_asistentes' => (int) $reporte->sum('total_alumnos'),
            'talleres_distintos' => $reporte->pluck('taller_id')->filter()->unique()->count(),
        ];
    }

    private function consultarAsistentesPorSesion(array $filtros)
    {
        $detalle = DB::table('asistencias as a')
            ->join('alumnos as al', 'al.id', '=', 'a.alumno_id')
            ->select(
                'a.dia',
                'a.clase_asig_id',
                DB::raw("COALESCE(al.folio, 'SIN FOLIO') as folio"),
                DB::raw("TRIM(CONCAT(COALESCE(al.nombre, ''), ' ', COALESCE(al.paterno, ''), ' ', COALESCE(al.materno, ''))) as nombre_completo"),
                DB::raw("TRIM(CONCAT(
                    COALESCE(al.domicilio, ''),
                    CASE WHEN al.num_ext IS NOT NULL AND al.num_ext <> '' THEN CONCAT(' #', al.num_ext) ELSE '' END,
                    CASE WHEN al.colonia IS NOT NULL AND al.colonia <> '' THEN CONCAT(', Col. ', al.colonia) ELSE '' END,
                    CASE WHEN al.municipio IS NOT NULL AND al.municipio <> '' THEN CONCAT(', ', al.municipio) ELSE '' END,
                    CASE WHEN al.estado IS NOT NULL AND al.estado <> '' THEN CONCAT(', ', al.estado) ELSE '' END,
                    CASE WHEN al.cod_postal IS NOT NULL AND al.cod_postal <> '' THEN CONCAT(', C.P. ', al.cod_postal) ELSE '' END
                )) as direccion")
            )
            ->where('a.docente_id', $filtros['docente_id'])
            ->whereBetween('a.dia', [$filtros['fecha_inicio'], $filtros['fecha_fin']])
            ->orderBy('a.dia')
            ->orderBy('nombre_completo')
            ->get();

        return $detalle->groupBy(function ($item) {
            return $item->dia.'|'.$item->clase_asig_id;
        });
    }
}
