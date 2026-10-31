<?php

use App\Http\Controllers\alumnosController;
use App\Http\Controllers\talleresController;
use App\Http\Controllers\docentesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\centrosController;
use App\Http\Controllers\claseAsigController;
use App\Http\Controllers\enlacesController;
use App\Http\Controllers\materialesController;
use App\Http\Controllers\usuariosController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\entregasMaterialesController;
use App\Http\Controllers\gestorController;
use App\Http\Controllers\supervisorController;

Route::get('/', function () {
    if (request()->filled('entrem')) {
        $clave = request('entrem');
        $usuario = App\Models\usuarios::where('clave', $clave)
            ->where('deshabilitado', 0)
            ->first();

        if ($usuario) {
            if ((int) $usuario->tipo === 3) {
                session(['usuario_entregas' => $usuario->id]);
                return redirect()->route('entregasmateriales.index');
            }

            return redirect()->route('alumnos.registroalumnosria');
        }
    }

    if(request()->filled('esperanzaDE')) {
        $clave = request('esperanzaDE');
        $usuario = App\Models\docente::where('clave', $clave)->first();

        if ($usuario) {
            $claseAsignada = App\Models\clase_asignada::where('docente', $usuario->id)
                ->where('deshabilitado', 0)
                ->latest()
                ->first();

            session([
                'docente' => $usuario->id,
                'centro' => $claseAsignada?->centro,
                'clase_asignada' => $claseAsignada?->id,
            ]);

            return redirect()->route('registroclases.index');
        }
    }

    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/talleres', talleresController::class)->names('talleres');

Route::resource('/centros', centrosController::class)->names('centros');
Route::post('/centros/importar', [centrosController::class, 'importarCentros'])->name('centros.importar');
Route::get('/centros/{centro}/reporte', [centrosController::class, 'reporteCentro'])->name('centros.reporte');

Route::resource('/docentes', docentesController::class)->names('docentes');
Route::post('/docentes/importar', [docentesController::class, 'importarDocentes'])->name('docentes.importar');
Route::post('/docentes/importar-asignaciones', [docentesController::class, 'importarAsignacionesDocentes'])->name('docentes.importar-asignaciones');
Route::get('/docentes/{docente}/credencialqr', [docentesController::class, 'credencialqrDocente'])->name('docentes.credencialqr');
Route::get('/docentes/{docente}/reporte', [docentesController::class, 'reporteDocente'])->name('docentes.reporte');
Route::get('/docentes/{docente}/reporte/talleres', [docentesController::class, 'talleresReporte'])->name('docentes.reporte.talleres');
Route::get('/docentes/{docente}/reporte/observaciones', [docentesController::class, 'observacionesReporteForm'])->name('docentes.reporte.observaciones.form');
Route::post('/docentes/{docente}/reporte/observaciones', [docentesController::class, 'guardarObservacionesReporte'])->name('docentes.reporte.observaciones');
Route::get('/reportes/docentes', [ReportesController::class, 'docentes'])->name('reportes.docentes');
Route::get('/reportes/docentes/pdf', [ReportesController::class, 'docentesPdf'])->name('reportes.docentes.pdf');
Route::resource('/asignar-taller', claseAsigController::class)->names('asignar-taller');
Route::resource('/alumnos', alumnosController::class)->names('alumnos');
Route::resource('/gestores', gestorController::class)->names('gestores');
Route::resource('/supervisores', supervisorController::class)->names('supervisores');

Route::get('/alumnos/{alumno}/credencialqr', [alumnosController::class, 'credencialqrAlumno'])->name('alumno.credencialqr');
Route::resource('/usuarios', usuariosController::class)->names('usuarios');
Route::get('/usuarios/{usuario}/credencialqr', [usuariosController::class, 'credencialqrUsuario'])->name('usuarios.credencialqr');


Route::get('registroalumnosria', [usuariosController::class, 'registroalumnosria'])->name('alumnos.registroalumnosria');
Route::post('registroalumnosria', [alumnosController::class, 'storeRia'])->name('alumnos.registroalumnosria.store');
Route::get('registroclases', [docentesController::class, 'registroclases'])->name('registroclases.index');
Route::post('registroclases', [docentesController::class, 'storeRegistroClase'])->name('registroclases.store');
Route::post('registroalumnosria/stream', [alumnosController::class, 'storeRiaStream'])->name('alumnos.registroalumnosria.stream');

Route::post('/docentes/guardar-expediente', [docentesController::class, 'guardarExpediente'])->name('docentes.guardarExpediente');
Route::delete('/docentes/{expediente}/deshabilitar-expediente', [docentesController::class, 'deshabilitarExpediente'])->name('docentes.destroyExpediente');

Route::resource('/materiales', materialesController::class)->names('materiales')->middleware('entregas');

Route::get('/listadoentregas', [entregasMaterialesController::class, 'listadoEntregas'])->name('listadoentregas.index')->middleware('entregas');
Route::get('/listadoentregas/{entrega}/pdf', [entregasMaterialesController::class, 'pdfEntrega'])->name('listadoentregas.pdf')->middleware('entregas');
Route::resource('/enlaces', enlacesController::class)->names('enlaces');


Route::resource('/entregasmateriales', entregasMaterialesController::class)
    ->names('entregasmateriales')
    ->only(['index', 'store'])
    ->middleware('entregas');

Route::get('/docentes/{docente}/descargar-contrato', [docentesController::class, 'descargarContratoWord'])->name('docentes.descargarContratoWord');
Route::post('/materiales/actualizar-stock', [materialesController::class, 'actualizarStock'])->name('materiales.actualizarStock');

// API Route for getting attendance locations
Route::get('/api/asistencias-ubicaciones/{claseId}/{fecha}', function($claseId, $fecha) {
    $asistencias = \DB::table('asistencias as a')
        ->leftJoin('alumnos as al', 'al.id', '=', 'a.alumno_id')
        ->select('a.latitud', 'a.longitud', 'a.hora', 'al.nombre', 'al.paterno', 'al.materno')
        ->where('a.clase_asig_id', $claseId)
        ->where('a.dia', $fecha)
        ->whereNotNull('a.latitud')
        ->whereNotNull('a.longitud')
        ->whereRaw("TRIM(a.latitud) <> ''")
        ->whereRaw("TRIM(a.longitud) <> ''")
        ->get()
        ->filter(fn ($a) => is_numeric($a->latitud) && is_numeric($a->longitud))
        ->map(fn ($a) => [
            'latitud' => (float) $a->latitud,
            'longitud' => (float) $a->longitud,
            'hora' => $a->hora ?: 'Sin hora',
            'alumno' => trim(implode(' ', array_filter([
                $a->nombre,
                $a->paterno,
                $a->materno,
            ]))) ?: 'Alumno sin nombre',
        ])
        ->values();

    return response()->json(['asistencias' => $asistencias]);
});
