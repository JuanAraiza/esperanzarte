<?php

namespace App\Http\Controllers;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\Gestor;
use Illuminate\Http\Request;

class gestorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         if(auth()->check()) {

       $gestores = Gestor::where('deshabilitado',0)->get();
      


           return view('gestores.index', compact('gestores'));
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
        Storage::disk('public')->put('gestores/'.$nameFile, (string) $image);

        }
            
    
            $gestor = new Gestor();
            $gestor->foto = 'gestores/'.$nameFile;
            $gestor->nombre = $request->input('nombre');
            $gestor->apaterno = $request->input('apaterno');
            $gestor->amaterno = $request->input('amaterno');
            $gestor->correo = $request->input('correo');
            $gestor->telefono = $request->input('telefono');
            $gestor->rfc = $request->input('rfc');
            $gestor->regimen = $request->input('regimen');
            $gestor->curp = $request->input('curp');
            
            $gestor->deshabilitado = 0;
            
            $gestor->save();

            $gestor->folio = 'GE'.str_pad($gestor->id, 3, '0', STR_PAD_LEFT);
            $gestor->clave = md5('GE'.str_pad($gestor->id, 3, '0', STR_PAD_LEFT).$request->input('nombre').$request->input('apaterno').$request->input('amaterno').date('YmdHsi'));
            $gestor->save();

            session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Gestor Registrado!',
            'text' => 'Creado Correctamente'
                ]);
    
            return redirect()->route('gestores.index');


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

            $gestor = Gestor::findOrFail($id);

            if($request->hasFile('image')){
                $upload = $request->file('image');
                $extension = $upload->getClientOriginalExtension();
                $nameFile = $request['nombre'].'-'.$request['apaterno'].'-'.$request['amaterno'].date('YmdHsi').'-ENIMG.'.$extension;
                $image = Image::decodeSplFileInfo($upload)
                    ->scale(width:800)
                    ->encodeUsingFileExtension($extension, quality: 70);
                Storage::disk('public')->put('gestores/'.$nameFile, (string) $image);
                $gestor->foto = 'gestores/'.$nameFile;
            }

            $gestor->nombre = $request->input('nombre');
            $gestor->apaterno = $request->input('apaterno');
            $gestor->amaterno = $request->input('amaterno');
            $gestor->correo = $request->input('correo');
            $gestor->telefono = $request->input('telefono');
            $gestor->rfc = $request->input('rfc');
            $gestor->regimen = $request->input('regimen');
            $gestor->curp = $request->input('curp');

            $gestor->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Gestor Actualizado!',
                'text' => 'Actualizado Correctamente'
            ]);

            return redirect()->route('gestores.index');
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
            $gestor = Gestor::findOrFail($id);
            $gestor->delete();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Gestor Eliminado!',
                'text' => 'Eliminado Correctamente'
            ]);

            return redirect()->route('gestores.index');
        }
        return redirect()->route('login');
    }
}
