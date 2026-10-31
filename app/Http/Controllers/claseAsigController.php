<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\docente;
use App\Models\centro;
use App\Models\clase_asignada;
use App\Models\taller;
use App\Models\clase_horarios;

class claseAsigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         if(auth()->check()) {

         $docentes = docente::where('deshabilitado', 0)->get();
         $centros = centro::where('deshabilitado', 0)->get();
         $talleres = taller::where('deshabilitado', 0)->get();
         $clasesAsignadas = clase_asignada::where('deshabilitado', 0)->get();
         $clasesHorarios = clase_horarios::all();

         return view('asignar.index', compact('docentes', 'centros', 'talleres', 'clasesAsignadas', 'clasesHorarios'));
        } else {
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
        if(auth()->check()) {

            $request->validate([
                'docente_id' => 'required|exists:docentes,id',
                'centro_id' => 'required|exists:centros,id',
                'taller_id' => 'required|exists:tallers,id',
            ]);

            $claseAsig = clase_asignada::create([
                'docente' => $request->input('docente_id'),
                'centro' => $request->input('centro_id'),
                'costo' => $request->input('costo'),
                'taller' => $request->input('taller_id'),
            ]);

            if($request->input('dia1')!=null){
                $total_horas = 0;
                if($request->input('hora_inicio1') && $request->input('hora_termino1')) {
                    $hora_inicio = strtotime($request->input('hora_inicio1'));
                    $hora_fin = strtotime($request->input('hora_termino1'));
                    $total_horas = ($hora_fin - $hora_inicio) / 3600;
                }
                clase_horarios::create([
                    'clase_asignada' => $claseAsig->id,
                    'dia' => $request->input('dia1'),
                    'hora_inicio' => $request->input('hora_inicio1'),
                    'hora_fin' => $request->input('hora_termino1'),
                    'total_horas' => $total_horas,
                ]);
            }

            if($request->input('dia2')!=null){
                $total_horas = 0;
                if($request->input('hora_inicio2') && $request->input('hora_termino2')) {
                    $hora_inicio = strtotime($request->input('hora_inicio2'));
                    $hora_fin = strtotime($request->input('hora_termino2'));
                    $total_horas = ($hora_fin - $hora_inicio) / 3600;
                }
                clase_horarios::create([
                    'clase_asignada' => $claseAsig->id,
                    'dia' => $request->input('dia2'),
                    'hora_inicio' => $request->input('hora_inicio2'),
                    'hora_fin' => $request->input('hora_termino2'),
                    'total_horas' => $total_horas,
                ]);
            }

            if($request->input('dia3')!=null){
                $total_horas = 0;
                if($request->input('hora_inicio3') && $request->input('hora_termino3')) {
                    $hora_inicio = strtotime($request->input('hora_inicio3'));
                    $hora_fin = strtotime($request->input('hora_termino3'));
                    $total_horas = ($hora_fin - $hora_inicio) / 3600;
                }
                clase_horarios::create([
                    'clase_asignada' => $claseAsig->id,
                    'dia' => $request->input('dia3'),
                    'hora_inicio' => $request->input('hora_inicio3'),
                    'hora_fin' => $request->input('hora_termino3'),
                    'total_horas' => $total_horas,
                ]);
            }

            if($request->input('dia4')!=null){
                $total_horas = 0;
                if($request->input('hora_inicio4') && $request->input('hora_termino4')) {
                    $hora_inicio = strtotime($request->input('hora_inicio4'));
                    $hora_fin = strtotime($request->input('hora_termino4'));
                    $total_horas = ($hora_fin - $hora_inicio) / 3600;
                }
                clase_horarios::create([
                    'clase_asignada' => $claseAsig->id,
                    'dia' => $request->input('dia4'),
                    'hora_inicio' => $request->input('hora_inicio4'),
                    'hora_fin' => $request->input('hora_termino4'),
                    'total_horas' => $total_horas,
                ]);
            }
            if($request->input('dia5')!=null){
                $total_horas = 0;
                if($request->input('hora_inicio5') && $request->input('hora_termino5')) {
                    $hora_inicio = strtotime($request->input('hora_inicio5'));
                    $hora_fin = strtotime($request->input('hora_termino5'));
                    $total_horas = ($hora_fin - $hora_inicio) / 3600;
                }
                clase_horarios::create([
                    'clase_asignada' => $claseAsig->id,
                    'dia' => $request->input('dia5'),
                    'hora_inicio' => $request->input('hora_inicio5'),
                    'hora_fin' => $request->input('hora_termino5'),
                    'total_horas' => $total_horas,
                ]);
            }


            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Taller Asignado!',
                'text' => 'Asignado Correctamente'
            ]);

            return redirect()->route('asignar-taller.index');
         

        } else {
            return redirect()->route('home');
         }
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
        if(auth()->check()) {

            $claseAsig = clase_asignada::findOrFail($id);

            $request->validate([
                'docente_id' => 'required|exists:docentes,id',
                'centro_id' => 'required|exists:centros,id',
                'taller_id' => 'required|exists:tallers,id',
            ]);

            $claseAsig->docente = $request->input('docente_id');
            $claseAsig->centro = $request->input('centro_id');
            $claseAsig->costo = $request->input('costo');
            $claseAsig->taller = $request->input('taller_id');

            $claseAsig->save();

            clase_horarios::where('clase_asignada', $claseAsig->id)->delete();

            if($request->input('dia1')!=null){
                $total_horas = 0;
                if($request->input('hora_inicio1') && $request->input('hora_termino1')) {
                    $hora_inicio = strtotime($request->input('hora_inicio1'));
                    $hora_fin = strtotime($request->input('hora_termino1'));
                    $total_horas = ($hora_fin - $hora_inicio) / 3600;
                }
                clase_horarios::create([
                    'clase_asignada' => $claseAsig->id,
                    'dia' => $request->input('dia1'),
                    'hora_inicio' => $request->input('hora_inicio1'),
                    'hora_fin' => $request->input('hora_termino1'),
                    'total_horas' => $total_horas,
                ]);
            }

            if($request->input('dia2')!=null){
                $total_horas = 0;
                if($request->input('hora_inicio2') && $request->input('hora_termino2')) {
                    $hora_inicio = strtotime($request->input('hora_inicio2'));
                    $hora_fin = strtotime($request->input('hora_termino2'));
                    $total_horas = ($hora_fin - $hora_inicio) / 3600;
                }
                clase_horarios::create([
                    'clase_asignada' => $claseAsig->id,
                    'dia' => $request->input('dia2'),
                    'hora_inicio' => $request->input('hora_inicio2'),
                    'hora_fin' => $request->input('hora_termino2'),
                    'total_horas' => $total_horas,
                ]);
            }

            if($request->input('dia3')!=null){
                $total_horas = 0;
                if($request->input('hora_inicio3') && $request->input('hora_termino3')) {
                    $hora_inicio = strtotime($request->input('hora_inicio3'));
                    $hora_fin = strtotime($request->input('hora_termino3'));
                    $total_horas = ($hora_fin - $hora_inicio) / 3600;
                }
                clase_horarios::create([
                    'clase_asignada' => $claseAsig->id,
                    'dia' => $request->input('dia3'),
                    'hora_inicio' => $request->input('hora_inicio3'),
                    'hora_fin' => $request->input('hora_termino3'),
                    'total_horas' => $total_horas,
                ]);
            }

            if($request->input('dia4')!=null){
                $total_horas = 0;
                if($request->input('hora_inicio4') && $request->input('hora_termino4')) {
                    $hora_inicio = strtotime($request->input('hora_inicio4'));
                    $hora_fin = strtotime($request->input('hora_termino4'));
                    $total_horas = ($hora_fin - $hora_inicio) / 3600;
                }
                clase_horarios::create([
                    'clase_asignada' => $claseAsig->id,
                    'dia' => $request->input('dia4'),
                    'hora_inicio' => $request->input('hora_inicio4'),
                    'hora_fin' => $request->input('hora_termino4'),
                    'total_horas' => $total_horas,
                ]);
            }
            if($request->input('dia5')!=null){
                $total_horas = 0;
                if($request->input('hora_inicio5') && $request->input('hora_termino5')) {
                    $hora_inicio = strtotime($request->input('hora_inicio5'));
                    $hora_fin = strtotime($request->input('hora_termino5'));
                    $total_horas = ($hora_fin - $hora_inicio) / 3600;
                }
                clase_horarios::create([
                    'clase_asignada' => $claseAsig->id,
                    'dia' => $request->input('dia5'),
                    'hora_inicio' => $request->input('hora_inicio5'),
                    'hora_fin' => $request->input('hora_termino5'),
                    'total_horas' => $total_horas,
                ]);
            }

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Taller Actualizado!',
                'text' => 'Actualizado Correctamente'
            ]);

            return redirect()->route('asignar-taller.index');
        } else {
            return redirect()->route('home');
         }  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        //
        if(auth()->check()) {
            $claseAsig = clase_asignada::findOrFail($id);
            $claseAsig->deshabilitado = 1;
            $claseAsig->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Taller Eliminado!',
                'text' => 'Eliminado Correctamente'
            ]);

            return redirect()->route('asignar-taller.index');
        } else {
            return redirect()->route('home');
         }
    }
}
