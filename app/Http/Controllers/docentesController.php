<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Laravel\Facades\Image;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\asistencias;
use App\Models\alumno;
use App\Models\clase_asignada;
use App\Models\clase_horarios;
use App\Models\centro;
use App\Models\docente;
use App\Models\docentes_para_subir;
use App\Models\expedientes_docente;
use App\Models\taller;
use App\Models\obs_observaciones_docentes;
use App\Models\Reportes_Observaciones;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;

class docentesController extends Controller
{
    private function resolverContextoRegistroClases(): array
    {
        $docenteId = session('docente');
        $centroId = session('centro');
        $claseAsignadaId = session('clase_asignada');

        $docenteRegistro = null;
        $centroRegistro = null;
        $tallerRegistro = null;
        $claseAsignada = null;
        $clasesAsignadasDocente = collect();
        $centrosDisponibles = collect();
        $clasesData = [];

        if (!empty($docenteId)) {
            $docenteRegistro = docente::find($docenteId);
        }

        if (!empty($docenteId)) {
            $clasesAsignadasDocente = clase_asignada::where('docente', $docenteId)
                ->where('deshabilitado', 0)
                ->orderBy('id')
                ->get();

            $idsCentro = $clasesAsignadasDocente->pluck('centro')->filter()->unique()->values();
            $idsTaller = $clasesAsignadasDocente->pluck('taller')->filter()->unique()->values();
            $idsClase = $clasesAsignadasDocente->pluck('id')->values();

            $centrosMap = centro::whereIn('id', $idsCentro)->get()->keyBy('id');
            $talleresMap = taller::whereIn('id', $idsTaller)->get()->keyBy('id');
            $horariosMap = clase_horarios::whereIn('clase_asignada', $idsClase)
                ->orderBy('dia')
                ->orderBy('hora_inicio')
                ->get()
                ->groupBy('clase_asignada');

            foreach ($clasesAsignadasDocente as $claseItem) {
                $centroItem = $centrosMap->get($claseItem->centro);
                $tallerItem = $talleresMap->get($claseItem->taller);
                $horariosClase = $horariosMap->get($claseItem->id, collect())->map(function ($h) {
                    return [
                        'id' => $h->id,
                        'dia' => (string) $h->dia,
                        'hora_inicio' => (string) $h->hora_inicio,
                        'hora_fin' => (string) $h->hora_fin,
                        'etiqueta' => trim((string) $h->dia).' '.substr((string) $h->hora_inicio, 0, 5).' - '.substr((string) $h->hora_fin, 0, 5),
                    ];
                })->values()->all();

                $clasesData[] = [
                    'id' => $claseItem->id,
                    'centro_id' => $claseItem->centro,
                    'centro_nombre' => $centroItem?->centro ?? ('Centro #'.$claseItem->centro),
                    'taller_id' => $claseItem->taller,
                    'taller_nombre' => $tallerItem?->nombre ?? ('Taller #'.$claseItem->taller),
                    'horarios' => $horariosClase,
                ];
            }

            $centrosDisponibles = collect($clasesData)
                ->map(fn ($item) => ['id' => $item['centro_id'], 'nombre' => $item['centro_nombre']])
                ->unique('id')
                ->values();
        }

        if (empty($claseAsignadaId) && $clasesAsignadasDocente->isNotEmpty()) {
            $claseAsignada = $clasesAsignadasDocente->first();

            if (!empty($claseAsignada)) {
                $claseAsignadaId = $claseAsignada->id;
                $centroId = $claseAsignada->centro;
                session([
                    'clase_asignada' => $claseAsignadaId,
                    'centro' => $centroId,
                ]);
            }
        }

        if (!empty($claseAsignadaId)) {
            $claseAsignada = $claseAsignada ?? clase_asignada::find($claseAsignadaId);
        }

        if (!empty($claseAsignada)) {
            $docenteRegistro = $docenteRegistro ?? docente::find($claseAsignada->docente);
            $centroRegistro = centro::find($claseAsignada->centro);
            $tallerRegistro = taller::find($claseAsignada->taller);
        }

        return compact(
            'docenteId',
            'centroId',
            'claseAsignadaId',
            'docenteRegistro',
            'centroRegistro',
            'tallerRegistro',
            'claseAsignada',
            'clasesAsignadasDocente',
            'centrosDisponibles',
            'clasesData'
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         if(Auth::check()) {
            $docentes = docente::where('deshabilitado', 0)->get();
            $talleres = taller::where('deshabilitado', 0)->get();
            return view('docentes.index', compact('docentes', 'talleres'));
        }else{
            return redirect()->route('home');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        if(Auth::check()) {
            $request->validate([
                'nombre' => 'required',
                'paterno' => 'required',
                'materno' => 'required', 
            ]);
            $nameFile='default.png';
        if($request->hasFile('image')){
        $upload = $request->file('image');
        $extension = $upload->getClientOriginalExtension();
        $nameFile = $request['nombre'].'-'.$request['paterno'].'-'.$request['materno'].date('YmdHsi').'-IMG.'.$extension;
        $image = Image::decodeSplFileInfo($upload)
            ->scale(width:800)
            ->encodeUsingFileExtension($extension, quality: 70);
        Storage::disk('public')->put('docentes/'.$nameFile, (string) $image);

        }
            
    
            $docente = new docente();
            $docente->foto = 'docentes/'.$nameFile;
            $docente->nombre = $request->input('nombre');
            $docente->paterno = $request->input('paterno');
            $docente->materno = $request->input('materno');
            $docente->correo = $request->input('correo');
            $docente->telefono = $request->input('telefono');
            $docente->rfc = $request->input('rfc');
            $docente->regimen = $request->input('regimen');
            $docente->curp = $request->input('curp');
            $docente->estatus = 1;
            $docente->deshabilitado = 0;
            $docente->clabe = $request->input('clabe');
            $docente->banco = $request->input('banco');
            $docente->direccion_fiscal = $request->input('direccion_fiscal');
            $docente->folio_ine = $request->input('folio_ine');
            $docente->taller = $request->input('taller');
            $docente->inicio_c = $request->input('inicio_c');
            $docente->termino_c = $request->input('termino_c');
            $docente->horas_t = $request->input('horas_t');
            $docente->genero = $request->input('genero');
            $docente->dias_asueto = $request->input('dias_asueto');
            $docente->save();

            $docente->folio = 'DE'.str_pad($docente->id, 3, '0', STR_PAD_LEFT);
            $docente->clave = md5('DE'.str_pad($docente->id, 3, '0', STR_PAD_LEFT).$request->input('nombre').$request->input('paterno').$request->input('materno').date('YmdHsi'));
            $docente->save();

            session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Docente Registrado!',
            'text' => 'Creado Correctamente'
                ]);
    
            return redirect()->route('docentes.index');
        } else {
            return redirect()->route('home');
        }   

    }

    public function importarDocentes()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $importados = 0;
        $omitidos = [];

        foreach (docentes_para_subir::orderBy('id')->get() as $registro) {
            $apellidos = preg_split('/\s+/', trim((string) $registro->apellidos), 2);
            $datos = [
                'nombre' => trim((string) $registro->nombre),
                'paterno' => $apellidos[0] ?? '',
                'materno' => $apellidos[1] ?? '',
                'correo' => trim((string) $registro->correo),
                'telefono' => trim((string) $registro->telefono),
                'rfc' => strtoupper(trim((string) $registro->rfc)),
                'regimen' => trim((string) $registro->regimen),
                'curp' => strtoupper(trim((string) $registro->curp)),
            ];

            $validator = Validator::make($datos, [
                'nombre' => 'required|string|max:255',
                'paterno' => 'required|string|max:255',
                'materno' => 'nullable|string|max:255',
                'correo' => 'nullable|email|max:255',
                'telefono' => 'nullable|string|max:255',
                'rfc' => 'nullable|string|max:255',
                'regimen' => 'nullable|string|max:255',
                'curp' => 'nullable|string|max:255',
            ]);

            $nombreRegistro = trim($datos['nombre'].' '.$datos['paterno'].' '.$datos['materno']);
            if ($validator->fails()) {
                $omitidos[] = $nombreRegistro ?: 'Registro #'.$registro->id;
                continue;
            }

            $duplicado = docente::query()
                ->when($datos['curp'] !== '', fn ($query) => $query->orWhere('curp', $datos['curp']))
                ->when($datos['rfc'] !== '', fn ($query) => $query->orWhere('rfc', $datos['rfc']))
                ->when($datos['correo'] !== '', fn ($query) => $query->orWhere('correo', $datos['correo']))
                ->orWhere(function ($query) use ($datos) {
                    $query->where('nombre', $datos['nombre'])
                        ->where('paterno', $datos['paterno'])
                        ->where('materno', $datos['materno']);
                })
                ->exists();

            if ($duplicado) {
                $omitidos[] = $nombreRegistro.' (duplicado)';
                continue;
            }

            $docente = docente::create([
                ...$datos,
                'foto' => 'docentes/default.png',
                'estatus' => 1,
                'deshabilitado' => 0,
            ]);
            $docente->folio = 'DE'.str_pad($docente->id, 3, '0', STR_PAD_LEFT);
            $docente->clave = md5($docente->folio.$docente->nombre.$docente->paterno.$docente->materno.now()->format('YmdHis'));
            $docente->save();
            $importados++;
        }

        session()->flash('swal', [
            'icon' => $omitidos ? 'warning' : 'success',
            'title' => 'Importación terminada',
            'text' => 'Importados: '.$importados.'. Omitidos: '.count($omitidos).($omitidos ? '. '.implode(', ', array_slice($omitidos, 0, 5)) : ''),
        ]);

        return redirect()->route('docentes.index');
    }

    public function importarAsignacionesDocentes()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $asignaciones = 0;
        $horariosCreados = 0;
        $omitidos = [];
        $diasDisponibles = [
            'lunes' => 'Lunes', 'martes' => 'Martes', 'miercoles' => 'Miércoles',
            'jueves' => 'Jueves', 'viernes' => 'Viernes', 'sabado' => 'Sábado', 'domingo' => 'Domingo',
            'lun' => 'Lunes', 'mar' => 'Martes', 'mie' => 'Miércoles', 'jue' => 'Jueves',
            'vie' => 'Viernes', 'sab' => 'Sábado', 'dom' => 'Domingo',
        ];
        $normalizarCentro = function ($nombre) {
            $nombre = mb_strtolower(trim((string) $nombre), 'UTF-8');
            $nombre = strtr($nombre, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u']);
            $nombre = preg_replace('/\besperanzarte\b/u', '', $nombre);
            $nombre = preg_replace('/\bfracc\.?\b/u', 'fraccion', $nombre);
            $nombre = preg_replace('/[^a-z0-9]+/u', ' ', $nombre);
            return trim(preg_replace('/\s+/', ' ', $nombre));
        };
        $centrosDisponibles = centro::where('deshabilitado', 0)->get();

        foreach (docentes_para_subir::orderBy('id')->get() as $registro) {
            $nombreCompleto = trim((string) $registro->nombre.' '.(string) $registro->apellidos);
            $docente = docente::query()
                ->when(trim((string) $registro->curp) !== '', fn ($query) => $query->orWhere('curp', strtoupper(trim((string) $registro->curp))))
                ->when(trim((string) $registro->rfc) !== '', fn ($query) => $query->orWhere('rfc', strtoupper(trim((string) $registro->rfc))))
                ->when(trim((string) $registro->correo) !== '', fn ($query) => $query->orWhere('correo', trim((string) $registro->correo)))
                ->first();

            if (!$docente) {
                $omitidos[] = $nombreCompleto.': docente no encontrado';
                continue;
            }

            $nombreTaller = trim((string) $registro->taller);
            $nombreCentro = trim((string) $registro->centro);
            if ($nombreTaller === '' || $nombreCentro === '') {
                $omitidos[] = $nombreCompleto.': falta taller o centro';
                continue;
            }

            $centroNormalizado = $normalizarCentro($nombreCentro);
            $centro = $centrosDisponibles->first(function ($centroDisponible) use ($centroNormalizado, $normalizarCentro) {
                return $normalizarCentro($centroDisponible->centro) === $centroNormalizado;
            });

            if (!$centro && $centroNormalizado !== '') {
                $centro = $centrosDisponibles->first(function ($centroDisponible) use ($centroNormalizado, $normalizarCentro) {
                    $nombreDisponible = $normalizarCentro($centroDisponible->centro);
                    return str_contains($nombreDisponible, $centroNormalizado)
                        || str_contains($centroNormalizado, $nombreDisponible);
                });
            }
            if (!$centro) {
                $omitidos[] = $nombreCompleto.': centro "'.$nombreCentro.'" no encontrado';
                continue;
            }

            $taller = taller::firstOrCreate(
                ['nombre' => $nombreTaller],
                ['deshabilitado' => 0]
            );
            if ((int) $taller->deshabilitado === 1) {
                $taller->deshabilitado = 0;
                $taller->save();
            }

            $textoHorario = mb_strtolower(trim((string) $registro->horario), 'UTF-8');
            $textoHorario = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $textoHorario);
            preg_match_all('/\b(lunes|martes|miercoles|jueves|viernes|sabado|domingo|lun|mar|mie|jue|vie|sab|dom)\b/u', $textoHorario, $coincidenciasDias);
            preg_match('/(\d{1,2})(?::(\d{2}))?\s*(a\.?m\.?|p\.?m\.?)?\s*(?:a|al|hasta|-|\/)\s*(\d{1,2})(?::(\d{2}))?\s*(a\.?m\.?|p\.?m\.?)?/iu', $textoHorario, $coincidenciaHoras);

            if (empty($coincidenciasDias[1]) || empty($coincidenciaHoras)) {
                $omitidos[] = $nombreCompleto.': horario no reconocido';
                continue;
            }

            $convertirHora = function ($hora, $minuto, $periodo) {
                $hora = (int) $hora;
                $minuto = (int) ($minuto ?: 0);
                $periodo = strtolower(str_replace('.', '', (string) $periodo));
                if ($periodo === 'pm' && $hora < 12) $hora += 12;
                if ($periodo === 'am' && $hora === 12) $hora = 0;
                return sprintf('%02d:%02d:00', $hora, $minuto);
            };
            $inicio = $convertirHora($coincidenciaHoras[1], $coincidenciaHoras[2] ?? '', $coincidenciaHoras[3] ?? '');
            $fin = $convertirHora($coincidenciaHoras[4], $coincidenciaHoras[5] ?? '', $coincidenciaHoras[6] ?? '');
            $duracion = (strtotime($fin) - strtotime($inicio)) / 3600;
            if ($duracion <= 0) {
                $omitidos[] = $nombreCompleto.': duración de horario inválida';
                continue;
            }

            $clase = clase_asignada::firstOrCreate([
                'docente' => $docente->id,
                'centro' => $centro->id,
                'taller' => $taller->id,
            ], [
                'costo' => null,
                'deshabilitado' => 0,
            ]);
            if ($clase->wasRecentlyCreated) $asignaciones++;

            foreach (array_unique($coincidenciasDias[1]) as $diaFuente) {
                $dia = $diasDisponibles[$diaFuente];
                $existeHorario = clase_horarios::where('clase_asignada', $clase->id)
                    ->where('dia', $dia)->where('hora_inicio', $inicio)->where('hora_fin', $fin)->exists();
                if (!$existeHorario) {
                    clase_horarios::create([
                        'clase_asignada' => $clase->id,
                        'dia' => $dia,
                        'hora_inicio' => $inicio,
                        'hora_fin' => $fin,
                        'total_horas' => $duracion,
                    ]);
                    $horariosCreados++;
                }
            }
        }

        session()->flash('swal', [
            'icon' => $omitidos ? 'warning' : 'success',
            'title' => 'Asignaciones importadas',
            'text' => 'Asignaciones: '.$asignaciones.'. Horarios: '.$horariosCreados.'. Omitidos: '.count($omitidos).($omitidos ? '. '.implode(', ', array_slice($omitidos, 0, 4)) : ''),
        ]);

        return redirect()->route('docentes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        if(Auth::check()) {
            $docente = docente::findOrFail($id);
            $docente->nombre = $request->input('nombre');
            $docente->paterno = $request->input('paterno');
            $docente->materno = $request->input('materno');
            $docente->correo = $request->input('correo');
            $docente->telefono = $request->input('telefono');
            $docente->rfc = $request->input('rfc');
            $docente->regimen = $request->input('regimen');
            $docente->curp = $request->input('curp');
            $docente->clabe = $request->input('clabe');
            $docente->banco = $request->input('banco');
            $docente->direccion_fiscal = $request->input('direccion_fiscal');
            $docente->folio_ine = $request->input('folio_ine');
            $docente->taller = $request->input('taller');
            $docente->inicio_c = $request->input('inicio_c');
            $docente->termino_c = $request->input('termino_c');
            $docente->horas_t = $request->input('horas_t');
            $docente->genero = $request->input('genero');
            $docente->dias_asueto = $request->input('dias_asueto');
            $docente->save();

            if($request->hasFile('image')){
    if($docente->foto){
        Storage::disk('public')->delete($docente->foto);
    }

    $upload = $request->file('image');
    $extension = $upload->getClientOriginalExtension();
    $nameFile = $docente->folio.date('YmdHsi').'-IMG.'.$extension;

        $image = Image::decodeSplFileInfo($upload)
                ->scale(width:800)
                ->encodeUsingFileExtension($extension, quality: 70);
        Storage::disk('public')->put('docentes/'.$nameFile, (string) $image);
    $docente->foto='docentes/'.$nameFile;
    $docente->save();
}
            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Docente Actualizado!',
                'text' => 'Actualizado Correctamente'
                    ]);
        
                return redirect()->route('docentes.index');
        } else {
            return redirect()->route('home');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function credencialqrDocente(string $docenteId)
    {
        if (Auth::check()) {
       $docentes = docente::find($docenteId);
       // return($tipovs);
       
       $customPaper = array(0,0,472.00,300.00);
       $pdf = Pdf::loadView('docentes.credencialqr', compact('docentes'))->setPaper($customPaper, 'landscape');
       return $pdf->stream('qrDocente.pdf');
        //return view('unidad.imvale', compact('unidades','areas','responsables','tipos','operadores','vales'));
    }else{
             return redirect()->route('login');
        }
    
    }

    public function reporteDocente(Request $request, string $docenteId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $docente = docente::findOrFail($docenteId);
        $expedientes = expedientes_docente::where('docente', $docenteId)
            ->where('deshabilitado', 0)
            ->orderBy('titulo')
            ->orderBy('created_at')
            ->get();

        $fechaFin = $request->query('fecha_fin') ?: now()->format('Y-m-d');
        $fechaInicio = $request->query('fecha_inicio') ?: now()->subDays(30)->format('Y-m-d');
        $reporteId = $request->query('reporte');

        // Asistencias registradas por los alumnos de este docente dentro del rango de fechas
        $asistenciasBase = asistencias::where('docente_id', $docenteId)
            ->whereBetween('dia', [$fechaInicio, $fechaFin]);
       // return $asistenciasBase;
        // Talleres (clases asignadas) en los que realmente hubo asistencias registradas
        $claseIds = (clone $asistenciasBase)->whereNotNull('clase_asig_id')
            ->distinct()
            ->pluck('clase_asig_id');

        $reporteData = collect();
        $totalHorasGenerales = 0;
        
        foreach ($claseIds as $claseId) {
            $clase = clase_asignada::find($claseId);
            if (!$clase) {
                continue;
            }

            $taller = taller::find($clase->taller);
            $centroObj = centro::find($clase->centro);
            $horarios = clase_horarios::where('clase_asignada', $clase->id)->get();

            // Alumnos distintos que registraron asistencia en este taller dentro del rango
            $asistentes = (clone $asistenciasBase)
                ->where('clase_asig_id', $claseId)
                ->distinct('alumno_id')
                ->count('alumno_id');

            // Días distintos en que se registró asistencia (sesiones realmente impartidas)
            $diasImpartidos = (clone $asistenciasBase)
                ->where('clase_asig_id', $claseId)
                ->distinct('dia')
                ->count('dia');

            // Cada asistencia identifica el horario impartido. Se cuenta una sola sesión
            // por día y horario, aunque hayan asistido varios alumnos.
            $horasTaller = 0;
            foreach ($horarios as $horario) {
                $diasHorario = (clone $asistenciasBase)
                    ->where('clase_asig_id', $claseId)
                    ->where('horario', $horario->id)
                    ->distinct('dia')
                    ->count('dia');

                $horasTaller += $diasHorario * (float) ($horario->total_horas ?? 0);
              
            }
            

            $reporteData->push([
                'taller_id' => $clase->taller,
                'centro_id' => $clase->centro,
                'taller_nombre' => $taller->nombre ?? 'Sin taller',
                'centro_nombre' => $centroObj->centro ?? 'Sin centro',
                'horarios' => $horarios,
                'asistentes' => $asistentes,
                'dias_impartidos' => $diasImpartidos,
                'horas' => $horasTaller,
            ]);

            $totalHorasGenerales += $horasTaller;
        }

        $observaciones = collect();
        if ($reporteId) {
            $observaciones = obs_observaciones_docentes::where('docente_id', $docenteId)
                ->where('reporte', $reporteId)
                ->pluck('observaciones', 'taller');
        }

        

        $pdf = Pdf::loadView('docentes.reporteDocente', [
            'docente' => $docente,
            'expedientes' => $expedientes,
            'reporteData' => $reporteData,
            'totalHoras' => $totalHorasGenerales,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'observaciones' => $observaciones,
        ]);

        $reporteBase = tempnam(sys_get_temp_dir(), 'reporte_docente_');
        file_put_contents($reporteBase, $pdf->output());

        $pdfFinal = new Fpdi();
        $paginasBase = $pdfFinal->setSourceFile($reporteBase);
        for ($pagina = 1; $pagina <= $paginasBase; $pagina++) {
            $plantilla = $pdfFinal->importPage($pagina);
            $tamano = $pdfFinal->getTemplateSize($plantilla);
            $pdfFinal->AddPage($tamano['orientation'], [$tamano['width'], $tamano['height']]);
            $pdfFinal->useTemplate($plantilla);
        }

        foreach ($expedientes as $expediente) {
            $rutaExpediente = Storage::disk('public')->path($expediente->archivo);
            $esPdf = strtolower(pathinfo($rutaExpediente, PATHINFO_EXTENSION)) === 'pdf'
                && is_file($rutaExpediente)
                && mime_content_type($rutaExpediente) === 'application/pdf';

            if (!$esPdf) {
                continue;
            }

            $paginasExpediente = $pdfFinal->setSourceFile($rutaExpediente);
            for ($pagina = 1; $pagina <= $paginasExpediente; $pagina++) {
                $plantilla = $pdfFinal->importPage($pagina);
                $tamano = $pdfFinal->getTemplateSize($plantilla);
                $pdfFinal->AddPage($tamano['orientation'], [$tamano['width'], $tamano['height']]);
                $pdfFinal->useTemplate($plantilla);
            }
        }

        $contenidoPdf = $pdfFinal->Output('S');
        unlink($reporteBase);

        return response($contenidoPdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="reporte_docente_' . $docente->folio . '.pdf"',
        ]);
    }

    public function talleresReporte(Request $request, string $docenteId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'No autorizado.'], 401);
        }

        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');
        if (!$fechaInicio || !$fechaFin || $fechaInicio > $fechaFin) {
            return response()->json(['message' => 'El rango de fechas no es válido.'], 422);
        }

        $asistenciasBase = asistencias::where('docente_id', $docenteId)
            ->whereBetween('dia', [$fechaInicio, $fechaFin]);
        $claseIds = (clone $asistenciasBase)->whereNotNull('clase_asig_id')->distinct()->pluck('clase_asig_id');
        $talleres = collect();

        foreach ($claseIds as $claseId) {
            $clase = clase_asignada::find($claseId);
            if (!$clase) {
                continue;
            }

            $horasTaller = 0;
            $horarios = clase_horarios::where('clase_asignada', $clase->id)->get();
            foreach ($horarios as $horario) {
                $diasHorario = (clone $asistenciasBase)
                    ->where('clase_asig_id', $claseId)
                    ->where('horario', $horario->id)
                    ->distinct('dia')
                    ->count('dia');
                $horasTaller += $diasHorario * (float) ($horario->total_horas ?? 0);
            }

            $talleres->push([
                'taller' => $clase->taller,
                'taller_nombre' => taller::find($clase->taller)?->nombre ?? 'Sin taller',
                'centro' => $clase->centro,
                'centro_nombre' => centro::find($clase->centro)?->centro ?? 'Sin centro',
                'total_horas' => $horasTaller,
            ]);
        }

        return response()->json(['talleres' => $talleres->values()]);
    }

    public function observacionesReporteForm(Request $request, string $docenteId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $datos = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        $docente = docente::findOrFail($docenteId);
        
        
        
        $asistenciasBase = asistencias::where('docente_id', $docenteId)
            ->whereBetween('dia', [$datos['fecha_inicio'], $datos['fecha_fin']]);
        $claseIds = (clone $asistenciasBase)->whereNotNull('clase_asig_id')->distinct()->pluck('clase_asig_id');
        $talleres = collect();

        foreach ($claseIds as $claseId) {
            $clase = clase_asignada::find($claseId);
            if (!$clase) {
                continue;
            }
            $horasTaller = 0;
            foreach (clase_horarios::where('clase_asignada', $clase->id)->get() as $horario) {
                $diasHorario = (clone $asistenciasBase)->where('clase_asig_id', $claseId)
                    ->where('horario', $horario->id)->distinct('dia')->count('dia');
                $horasTaller += $diasHorario * (float) ($horario->total_horas ?? 0);
            }
            $talleres->push([
                'taller' => $clase->taller,
                'taller_nombre' => taller::find($clase->taller)?->nombre ?? 'Sin taller',
                'centro' => $clase->centro,
                'centro_nombre' => centro::find($clase->centro)?->centro ?? 'Sin centro',
                'total_horas' => $horasTaller,
            ]);
        }

        return view('docentes.observacionesReporte', [
            'docente' => $docente,
            'talleres' => $talleres,
            'fechaInicio' => $datos['fecha_inicio'],
            'fechaFin' => $datos['fecha_fin'],
        ]);
    }

    public function guardarObservacionesReporte(Request $request, string $docenteId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'No autorizado.'], 401);
        }

        $datos = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'talleres' => 'required|array|min:1',
            'talleres.*.taller' => 'required|integer',
            'talleres.*.centro' => 'required|integer',
            'talleres.*.total_horas' => 'required|numeric|min:0',
            'talleres.*.observaciones' => 'nullable|string|max:5000',
        ]);
        $reporteObservaciones = new Reportes_Observaciones();
        $reporteObservaciones->docente = $docenteId;
        $reporteObservaciones->fecha = now()->toDateString();
        $reporteObservaciones->hora = now()->toTimeString();
        $reporteObservaciones->fecha_ini = $datos['fecha_inicio'];
        $reporteObservaciones->fecha_fin = $datos['fecha_fin'];
        $reporteObservaciones->save();

        $reporteId = $reporteObservaciones->id;
        foreach ($datos['talleres'] as $registro) {
            obs_observaciones_docentes::create([
                'docente_id' => $docenteId,
                'reporte' => $reporteId,
                'taller' => $registro['taller'],
                'centro' => $registro['centro'],
                'total_horas' => $registro['total_horas'],
                'observaciones' => $registro['observaciones'] ?? null,
                'fecha_ini' => $datos['fecha_inicio'],
                'fecha_fin' => $datos['fecha_fin'],
            ]);
        }

        if (!$request->expectsJson()) {
            return redirect()->route('docentes.reporte', [
                'docente' => $docenteId,
                'fecha_inicio' => $datos['fecha_inicio'],
                'fecha_fin' => $datos['fecha_fin'],
                'reporte' => $reporteId,
            ]);
        }

        return response()->json(['reporte' => $reporteId]);
    }

    public function registroclases()
    {
        $contexto = $this->resolverContextoRegistroClases();

        if (empty($contexto['docenteId'])) {
            return redirect()->route('home');
        }

        return view('registroclases.index', $contexto);
    }

    public function guardarExpediente(Request $request)
    {

        if(!Auth::check()) {
            return redirect()->route('home');
        }


        $request->validate([
            'docente' => 'required|exists:docentes,id',
            'titulo' => 'required|string|max:255',
            'archivo' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        
if($request->hasFile('archivo')){



        $archivo = $request->file('archivo');
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        $rutaArchivo = $archivo->storeAs('expedientes_docentes', $nombreArchivo, 'public');

        $expediente = new expedientes_docente();
        $expediente->docente = $request->input('docente');
        $expediente->titulo = $request->input('titulo');
        $expediente->archivo = $rutaArchivo;
        $expediente->deshabilitado = 0;
        $expediente->save();


}


session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Expediente Subido!',
                'text' => 'Documento subido correctamente'
                    ]);

        return redirect()->route('docentes.index'); 
    }   



    public function deshabilitarExpediente(Request $request, $expedienteId)
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $archicvoExp =  expedientes_docente::findOrFail($expedienteId);
        if ($archicvoExp->archivo) {
            Storage::disk('public')->delete($archicvoExp->archivo);
        }

        $expediente = expedientes_docente::findOrFail($expedienteId);

        $expediente->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Expediente Deshabilitado!',
            'text' => 'Documento deshabilitado correctamente'
        ]);

        return redirect()->route('docentes.index');
    }


    public function descargarContratoWord(string $id)
        {
           if (auth()->check()) {
           // return(public_path());
         $archwod = public_path().'/formatos/Contrato-Esperanzarte.docx';
           $template = new \PhpOffice\PhpWord\TemplateProcessor($archwod);

           $docente = docente::find($id);
           $template->setValue('ndocente', $docente->nombre.' '.$docente->paterno.' '.$docente->materno);
           if($docente->genero == 'HOMBRE'){
               $template->setValue('el-la', 'el');
               $template->setValue('prestador', 'EL PRESTADOR');
           }elseif($docente->genero == 'MUJER'){
               $template->setValue('el-la', 'la');
               $template->setValue('prestador', 'LA PRESTADORA');
           }else{
               $template->setValue('el-la', '');
               $template->setValue('prestador', '');
           }
           $template->setValue('direccion_fiscal', $docente->direccion_fiscal);
           $template->setValue('folio_ine', $docente->folio_ine);
           $template->setValue('curp', $docente->curp); 
           $template->setValue('rfc', $docente->rfc);
           $template->setValue('taller', $docente->taller);
           $template->setValue('banco', $docente->banco);
           $template->setValue('clabe', $docente->clabe);
           $template->setValue('dias_asueto', $docente->dias_asueto);
           $template->setValue('horas_t', $docente->horas_t);
           if($docente->inicio_c){
            $fecha_ic = Carbon::createFromFormat('Y-m-d', $docente->inicio_c);
            $fechaF_ic = $fecha_ic->format('d de F de Y');
           }else{
            $fechaF_ic = '';
           }
           $template->setValue('inicio_c', $fechaF_ic);
           if($docente->termino_c){
            $fecha_tc = Carbon::createFromFormat('Y-m-d', $docente->termino_c);
            $fechaF_tc = $fecha_tc->format('d de F de Y');
           }else{
            $fechaF_tc = '';
           }
           $template->setValue('termino_c', $fechaF_tc);

        
$talleresAsignados = clase_asignada::where('docente', $docente->id)->get();
//return $talleresAsignados;

// Datos de ejemplo que queremos incrustar
$horarios = [];
$rowNumber = 1;
//$template->cloneRow('centro', count($talleresAsignados));
$rowh=0;
foreach ($talleresAsignados as $taller) {

    $centroasignado = centro::find($taller->centro);
    $horariostaller = clase_horarios::where('clase_asignada', $taller->id)->get();
    $tallerNombre = taller::find($taller->taller); 

    //return $tallerNombre->nombre;
    //return $horariostaller[$rowh]->dia . ' ' . $horariostaller[$rowh]->hora_inicio . ' - ' . $horariostaller[$rowh]->hora_fin;
    $horarioTexto = '';
   foreach ($horariostaller as $horario) {
       if($horario->dia){
           $horarioTexto = $horario->dia . ' ' . substr($horario->hora_inicio, 0, 5) . ' - ' . substr($horario->hora_fin, 0, 5);
       }else{
           $horarioTexto = '';
       }
   }
//return $horarioTexto;
    $horarios[] = [
        'centro' => $centroasignado->centro,
        'taller' => $tallerNombre->nombre,
        'domicilio' => $centroasignado->direccion,
        'horarios' => $horarioTexto,
    ];
    $rowh++;
}

// 1. Clonamos la fila el número de veces que necesitemos (basado en la primera variable)
$template->cloneRow('tascentro', count($horarios));
//return $horarios;
// 2. Llenamos los datos correspondientes usando el índice (comienza en 1)
foreach ($horarios as $index => $horario) {
    $rowNumber = $index + 1;
    $template->setValue('tascentro#' . $rowNumber, $horario['centro']);
    $template->setValue('tastaller#' . $rowNumber, $horario['taller']);
    $template->setValue('tasdomicilio#' . $rowNumber, $horario['domicilio']);
    $template->setValue('tashorario#' . $rowNumber, $horario['horarios']);
    
}



           $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
           $template->saveAs($tempFile);

           $headers =[
                "Content-Type: application/octet-stream",
           ];
           //return($tempFile);
           return response()->download($tempFile, 'Contrato-Esperanzarte-'.$docente->nombre.'-'.$docente->paterno.'-'.$docente->materno.'.docx')->deleteFileAfterSend(true);

           }else{
             return redirect()->route('login');
             }
        }

    public function storeRegistroClase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alumno_clave' => 'required|string',
            'latitud' => 'required|string|max:80',
            'longitud' => 'required|string|max:80',
            'clase_asignada_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Faltan datos para registrar la asistencia.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $contexto = $this->resolverContextoRegistroClases();
        $docenteId = $contexto['docenteId'] ?? null;
        $claseAsignadaId = (int) $request->input('clase_asignada_id');

        if (empty($docenteId)) {
            return response()->json([
                'ok' => false,
                'message' => 'No se pudo identificar al docente.',
            ], 403);
        }

        if (empty($claseAsignadaId)) {
            return response()->json([
                'ok' => false,
                'message' => 'No existe una clase asignada activa para este docente.',
            ], 422);
        }

        $claseAsignada = clase_asignada::where('id', $claseAsignadaId)
            ->where('docente', $docenteId)
            ->where('deshabilitado', 0)
            ->first();

        if (empty($claseAsignada)) {
            return response()->json([
                'ok' => false,
                'message' => 'La clase seleccionada no pertenece al docente actual.',
            ], 422);
        }

        $horarioId = (int) $request->input('horario');
        $horarioSeleccionado = clase_horarios::where('id', $horarioId)
            ->where('clase_asignada', $claseAsignadaId)
            ->first();

        if (empty($horarioSeleccionado)) {
            return response()->json([
                'ok' => false,
                'message' => 'Selecciona un horario válido para la clase actual.',
            ], 422);
        }

        $claveAlumno = trim((string) $request->input('alumno_clave'));
        $alumnoRegistro = alumno::where('clave', $claveAlumno)->where('deshabilitado', 0)->first();

        if (empty($alumnoRegistro)) {
            return response()->json([
                'ok' => false,
                'message' => 'El QR del alumno no corresponde a un registro valido.',
            ], 404);
        }

        $diaActual = now()->toDateString();
        $horaActual = now()->format('H:i:s');

        $existeAsistencia = asistencias::where('alumno_id', $alumnoRegistro->id)
            ->where('docente_id', $docenteId)
            ->where('clase_asig_id', $claseAsignadaId)
            ->where('dia', $diaActual)
            ->first();

        if (!empty($existeAsistencia)) {
            return response()->json([
                'ok' => true,
                'duplicate' => true,
                'message' => 'La asistencia ya fue registrada hoy para este alumno.',
            ]);
        }

        asistencias::create([
            'alumno_id' => $alumnoRegistro->id,
            'docente_id' => $docenteId,
            'clase_asig_id' => $claseAsignadaId,
            'horario' => $horarioSeleccionado->id,
            'dia' => $diaActual,
            'hora' => $horaActual,
            'latitud' => $request->input('latitud'),
            'longitud' => $request->input('longitud'),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Asistencia registrada correctamente.',
            'alumno' => [
                'id' => $alumnoRegistro->id,
                'clave' => $alumnoRegistro->clave,
            ],
        ]);
    }

}
