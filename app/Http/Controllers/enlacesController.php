<?php

namespace App\Http\Controllers;
use Intervention\Image\Laravel\Facades\Image;
use Carbon\Carbon;
use App\Models\Enlaces;
use App\Models\centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class enlacesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
       if(auth()->check()) {

       $enlaces = Enlaces::where('deshabilitado',0)->get();
       $centros = centro::all();


           return view('enlaces.index', compact('enlaces', 'centros'));
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
        Storage::disk('public')->put('enlaces/'.$nameFile, (string) $image);

        }
            
    
            $docente = new Enlaces();
            $docente->foto = 'enlaces/'.$nameFile;
            $docente->nombre = $request->input('nombre');
            $docente->apaterno = $request->input('apaterno');
            $docente->amaterno = $request->input('amaterno');
            $docente->centro = $request->input('centro');
            $docente->correo = $request->input('correo');
            $docente->telefono = $request->input('telefono');
            $docente->rfc = $request->input('rfc');
            $docente->regimen = $request->input('regimen');
            $docente->curp = $request->input('curp');
            
            $docente->deshabilitado = 0;
            
            $docente->save();

            $docente->folio = 'EE'.str_pad($docente->id, 3, '0', STR_PAD_LEFT);
            $docente->clave = md5('EE'.str_pad($docente->id, 3, '0', STR_PAD_LEFT).$request->input('nombre').$request->input('apaterno').$request->input('amaterno').date('YmdHsi'));
            $docente->save();

            session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Enlace Registrado!',
            'text' => 'Creado Correctamente'
                ]);
    
            return redirect()->route('enlaces.index');

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

        $docente = Enlaces::find($id);
        if(!$docente) {
            return redirect()->route('enlaces.index')->with('error', 'Enlace no encontrado');
        }

        if($request->hasFile('image')){
            $upload = $request->file('image');
            $extension = $upload->getClientOriginalExtension();
            $nameFile = $request['nombre'].'-'.$request['apaterno'].'-'.$request['amaterno'].date('YmdHsi').'-ENIMG.'.$extension;
            $image = Image::decodeSplFileInfo($upload)
                ->scale(width:800)
                ->encodeUsingFileExtension($extension, quality: 70);
            Storage::disk('public')->put('enlaces/'.$nameFile, (string) $image);
            $docente->foto = 'enlaces/'.$nameFile;
        }

        $docente->nombre = $request->input('nombre');
        $docente->apaterno = $request->input('apaterno');
        $docente->amaterno = $request->input('amaterno');
        $docente->centro = $request->input('centro');
        $docente->correo = $request->input('correo');
        $docente->telefono = $request->input('telefono');
        $docente->rfc = $request->input('rfc');
        $docente->regimen = $request->input('regimen');
        $docente->curp = $request->input('curp');
        $docente->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Enlace Actualizado!',
            'text' => 'Actualizado Correctamente'
        ]);

        return redirect()->route('enlaces.index');
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
            $docente = Enlaces::find($id);
            if($docente) {
                $docente->delete();
                session()->flash('swal', [
                    'icon' => 'success',
                    'title' => 'Enlace Eliminado!',
                    'text' => 'Eliminado Correctamente'
                ]);
            }
            return redirect()->route('enlaces.index');
        }
        return redirect()->route('login');      
    }
}
