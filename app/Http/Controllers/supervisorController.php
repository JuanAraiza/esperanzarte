<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class supervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if(auth()->check()) {
            // Your code to display the list of supervisors goes here
            $supervisores = Supervisor::all();
            return view('supervisores.index', compact('supervisores'));
        }
        return redirect()->route('login');  
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

         $nameFile='default.png';
        if($request->hasFile('image')){
        $upload = $request->file('image');
        $extension = $upload->getClientOriginalExtension();
        $nameFile = $request['nombre'].'-'.$request['apaterno'].'-'.$request['amaterno'].date('YmdHsi').'-ENIMG.'.$extension;
        $image = Image::decodeSplFileInfo($upload)
            ->scale(width:800)
            ->encodeUsingFileExtension($extension, quality: 70);
        Storage::disk('public')->put('supervisores/'.$nameFile, (string) $image);

        }
            
    
            $supervisor = new Supervisor();
            $supervisor->foto = 'supervisores/'.$nameFile;
            $supervisor->nombre = $request->input('nombre');
            $supervisor->apaterno = $request->input('apaterno');
            $supervisor->amaterno = $request->input('amaterno');
            $supervisor->correo = $request->input('correo');
            $supervisor->telefono = $request->input('telefono');
            $supervisor->rfc = $request->input('rfc');
            $supervisor->regimen = $request->input('regimen');
            $supervisor->curp = $request->input('curp');
            $supervisor->zona = $request->input('zona');
            $supervisor->deshabilitado = 0;
            
            $supervisor->save();

            $supervisor->folio = 'SU'.str_pad($supervisor->id, 3, '0', STR_PAD_LEFT);
            $supervisor->clave = md5('SU'.str_pad($supervisor->id, 3, '0', STR_PAD_LEFT).$request->input('nombre').$request->input('apaterno').$request->input('amaterno').date('YmdHsi'));
            $supervisor->save();

            session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Supervisor Registrado!',
            'text' => 'Creado Correctamente'
                ]);
    
            return redirect()->route('supervisores.index');

        }
        return redirect()->route('login');
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
            $supervisor = Supervisor::findOrFail($id);
            if($request->hasFile('image')){
                $upload = $request->file('image');
                $extension = $upload->getClientOriginalExtension();
                $nameFile = $request['nombre'].'-'.$request['apaterno'].'-'.$request['amaterno'].date('YmdHsi').'-ENIMG.'.$extension;
                $image = Image::decodeSplFileInfo($upload)
                    ->scale(width:800)
                    ->encodeUsingFileExtension($extension, quality: 70);
                Storage::disk('public')->put('supervisores/'.$nameFile, (string) $image);
                $supervisor->foto = 'supervisores/'.$nameFile;
            }
            $supervisor->nombre = $request->input('nombre');
            $supervisor->apaterno = $request->input('apaterno');
            $supervisor->amaterno = $request->input('amaterno');
            $supervisor->correo = $request->input('correo');
            $supervisor->telefono = $request->input('telefono');
            $supervisor->rfc = $request->input('rfc');
            $supervisor->regimen = $request->input('regimen');
            $supervisor->zona = $request->input('zona');

            $supervisor->curp = $request->input('curp');
            $supervisor->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Supervisor Actualizado!',
                'text' => 'Actualizado Correctamente'
            ]);

            return redirect()->route('supervisores.index');
        }   
        return redirect()->route('login');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        if(auth()->check()) {
            $supervisor = Supervisor::findOrFail($id);
            $supervisor->deshabilitado = 1;
            $supervisor->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Supervisor Eliminado!',
                'text' => 'Eliminado Correctamente'
            ]);

            return redirect()->route('supervisores.index');
        }
        return redirect()->route('login');
    }
}
