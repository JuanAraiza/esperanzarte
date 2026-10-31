<?php

namespace App\Http\Controllers;

use App\Models\taller;
use Illuminate\Http\Request;

class talleresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (auth()->check()) {
            
        $talleres = taller::where('deshabilitado', 0)->get();
            
            return view('talleres.index', compact('talleres'));

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
        if (auth()->check()) {
            $request->validate([
                'nombre' => 'required|string|max:255',
            ]);

            $taller = new taller();
            $taller->nombre = $request->input('nombre');
            $taller->deshabilitado = 0;
            $taller->save();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Taller Creado!',
            'text' => 'Creado Correctamente'
        ]);
            return redirect()->route('talleres.index');
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        //
        if (auth()->check()) {
            $taller = taller::findOrFail($id);
            $taller->deshabilitado = 1;
            $taller->save();
            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Taller Eliminado!',
                'text' => 'Eliminado Correctamente'
            ]);
            return redirect()->route('talleres.index');
        } else {
            return redirect()->route('home');
        }   
    }
}
