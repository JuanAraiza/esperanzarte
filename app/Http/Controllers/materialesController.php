<?php

namespace App\Http\Controllers;

use App\Models\actualizarMaterial;
use Illuminate\Http\Request;
use App\Models\Material;

class materialesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (auth()->check()) {
            
        $materiales = Material::where('deshabilitado', 0)->get();
            
            return view('materiales.index', compact('materiales'));

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
            $material = new Material();
            $material->material = $request->material;
            $material->detalles = $request->detalles;
            $material->marca = $request->marca;
            $material->costo = $request->costo;
            $material->unidad_medida = $request->unidad_medida;
            $material->detalles_producto = $request->detalles_producto;
            $material->stock = $request->stock;
            $material->deshabilitado = 0;
            $material->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Material Subido!',
                'text' => 'Material subido correctamente'
                ]);

            return redirect()->route('materiales.index');
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
        if (auth()->check()) {
            $material = Material::find($id);
            $material->material = $request->material;
            $material->detalles = $request->detalles;
            $material->marca = $request->marca;
            $material->costo = $request->costo;
            $material->unidad_medida = $request->unidad_medida;
            $material->detalles_producto = $request->detalles_producto;
            $material->stock = $request->stock;
            $material->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Material Actualizado!',
                'text' => 'Material actualizado correctamente'
                ]);

            return redirect()->route('materiales.index');
        } else {
            return redirect()->route('home');
        }
    }


    /**
     * Actualizar el stock de un material.
     */
    public function actualizarStock(Request $request)
    {
        if (auth()->check()) {
            $material = Material::find($request->material);
            $material->stock = $material->stock + $request->agregar;
            $material->save();

            actualizarMaterial::create([
                'material' => $material->id,
                'stockviejo' => $material->stock - $request->agregar,
                'agregar' => $request->agregar,
                'stocknuevo' => $material->stock,
                'fecha' => now(),
                'usuario' => auth()->id(),
            ]);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Stock Actualizado!',
                'text' => 'Stock actualizado correctamente'
                ]);

            return redirect()->route('materiales.index');
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
        if (auth()->check()) {
            $material = Material::find($id);
            $material->deshabilitado = 1;
            $material->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Material Eliminado!',
                'text' => 'Material eliminado correctamente'
                ]);

            return redirect()->route('materiales.index');
        } else {
            return redirect()->route('home');
        }
    }
}
