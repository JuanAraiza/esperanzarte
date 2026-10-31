<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\centro;
use App\Models\centros_para_subir;
use App\Models\clase_asignada;
use App\Models\taller;
use Illuminate\Support\Facades\Validator;

class centrosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if(auth()->check()) {
        $centros = centro::where('deshabilitado', 0)->get();

        return view('centros.index', compact('centros'));
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
        if(auth()->check()) {
            $request->validate([
                'centro' => 'required|string|max:255',
                'zona' => 'required|string|max:255',
                'latitud' => 'required|numeric|between:-90,90',
                'longitud' => 'required|numeric|between:-180,180',
            ]);

            $centro = new centro();
            $centro->centro = $request->input('centro');
            $centro->zona = $request->input('zona');
            $centro->tipo_territorio = $request->input('tipo_territorio');
            $centro->territorio = $request->input('territorio');
            $centro->direccion = $request->input('direccion');
            $centro->google_maps = $request->input('google_maps');
            $centro->enlace = $request->input('enlace');
            $centro->telefono = $request->input('telefono');
            $centro->supervisor = $request->input('supervisor');
            $centro->telefono_1 = $request->input('telefono_1');
            $centro->latitud = $request->input('latitud');
            $centro->longitud = $request->input('longitud');
            $centro->estatus=1;
            $centro->deshabilitado = 0;
            $centro->save();

            $centro->id_centro = 'EspArt-' . str_pad($centro->id, 3, '0', STR_PAD_LEFT);
            $centro->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Centro Creado!',
                'text' => 'Creado Correctamente'
            ]);

            return redirect()->route('centros.index');
        } else {
            return redirect()->route('home');
        }
    }

    public function importarCentros()
    {
        if (!auth()->check()) {
            return redirect()->route('home');
        }

        $importados = 0;
        $omitidos = [];
        $registros = centros_para_subir::orderBy('id')->get();

        foreach ($registros as $registro) {
            $datos = [
                'centro' => trim((string) $registro->nombre),
                'zona' => strtoupper(trim((string) $registro->zona)),
                'tipo_territorio' => trim((string) $registro->tipo_territorio),
                'territorio' => trim((string) $registro->territorio),
                'direccion' => trim((string) $registro->direccion),
                'google_maps' => trim((string) $registro->google_maps),
                'telefono' => trim((string) $registro->telefono),
                'supervisor' => trim((string) $registro->supervisor),
                'telefono_1' => trim((string) $registro->telefono2),
            ];

            $validator = Validator::make($datos, [
                'centro' => 'required|string|max:255',
                'zona' => 'required|in:A,B,C,D,E',
                'territorio' => 'nullable|string|max:255',
                'direccion' => 'required|string|max:255',
                'google_maps' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:255',
                'supervisor' => 'nullable|string|max:255',
                'telefono_1' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                $omitidos[] = $registro->nombre ?: 'Registro #'.$registro->id;
                continue;
            }

            if (centro::whereRaw('LOWER(centro) = ?', [mb_strtolower($datos['centro'])])->exists()) {
                $omitidos[] = $datos['centro'].' (duplicado)';
                continue;
            }

            $centro = centro::create([
                ...$datos,
                'enlace' => null,
                'estatus' => 1,
                'deshabilitado' => 0,
            ]);
            $centro->id_centro = 'EspArt-'.str_pad($centro->id, 3, '0', STR_PAD_LEFT);
            $centro->save();
            $importados++;
        }

        session()->flash('swal', [
            'icon' => $omitidos ? 'warning' : 'success',
            'title' => 'Importación terminada',
            'text' => 'Importados: '.$importados.'. Omitidos: '.count($omitidos).($omitidos ? '. '.implode(', ', array_slice($omitidos, 0, 5)) : ''),
        ]);

        return redirect()->route('centros.index');
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
            $centro = centro::findOrFail($id);

            $request->validate([
                'centro' => 'required|string|max:255',
                'zona' => 'required|string|max:255',
                'latitud' => 'required|numeric|between:-90,90',
                'longitud' => 'required|numeric|between:-180,180',
            ]);

            $centro->centro = $request->input('centro');
            $centro->zona = $request->input('zona');
            $centro->tipo_territorio = $request->input('tipo_territorio');
            $centro->territorio = $request->input('territorio');
            $centro->direccion = $request->input('direccion');
            $centro->google_maps = $request->input('google_maps');
            $centro->enlace = $request->input('enlace');
            $centro->telefono = $request->input('telefono');
            $centro->supervisor = $request->input('supervisor');
            $centro->telefono_1 = $request->input('telefono_1');
            $centro->latitud = $request->input('latitud');
            $centro->longitud = $request->input('longitud');

            $centro->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Centro Actualizado!',
                'text' => 'Actualizado Correctamente'
            ]);

            return redirect()->route('centros.index');
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
            $centro = centro::findOrFail($id);
            $centro->deshabilitado = 1;
            $centro->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Centro Eliminado!',
                'text' => 'Eliminado Correctamente'
            ]);

            return redirect()->route('centros.index');
        } else {
            return redirect()->route('home');
        }
    }


    /**
     * Generate a report for the specified centro.
     */
    public function reporteCentro(string $id)
    {
        if(auth()->check()) {
            $centro = centro::findOrFail($id);

            $clasesAsignadas = clase_asignada::where('centro', $centro->id)->get();
            $talleres = taller::whereIn('id', $clasesAsignadas->pluck('taller'))->get();

            // Aquí puedes generar el reporte según tus necesidades.
            // Por ejemplo, podrías retornar una vista con los datos del centro.
            // return $clasesAsignadas;
            // return $talleres;
            return view('centros.reporte', compact('centro','clasesAsignadas','talleres'));
        } else {
            return redirect()->route('home');
        }
    }



}
