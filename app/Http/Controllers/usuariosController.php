<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usuarios;
use Barryvdh\DomPDF\Facade\Pdf;

class usuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (auth()->check()) {
            $usuarios = usuarios::where('deshabilitado', 0)->get();
            return view('usuarios.index', compact('usuarios'));
        } else {
            return redirect()->route('login');
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
            $existente = usuarios::where('email', $request->input('email'))->first();
            if ($existente) {
                if ((int) $existente->deshabilitado === 0) {
                    session()->flash('swal', [
                        'icon' => 'error',
                        'title' => 'Correo duplicado',
                        'text' => 'Ya existe un usuario activo con ese correo'
                    ]);
                    return redirect()->route('usuarios.index');
                }
                $existente->delete();
            }

            $usuario = new usuarios();
            $usuario->name = $request->input('nombre');
            $usuario->email = $request->input('email');
            $usuario->password = bcrypt($request->input('password'));
            $usuario->tipo = $request->input('tipo');
            $usuario->deshabilitado = 0;
            $usuario->clave = uniqid(); // Genera un valor único para la columna 'clave'
            $usuario->save();

            session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario Registrado!',
            'text' => 'Creado Correctamente'
                ]);

            return redirect()->route('usuarios.index');
        } else {
            return redirect()->route('login');
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
            $usuario = usuarios::findOrFail($id);
            $usuario->name = $request->input('nombre');
            $usuario->email = $request->input('email');
            if ($request->filled('password')) {
                $usuario->password = bcrypt($request->input('password'));
            }
            $usuario->tipo = $request->input('tipo');       
            $usuario->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Usuario Actualizado!',
                'text' => 'Actualizado Correctamente'
            ]);

            return redirect()->route('usuarios.index');
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        //
        if(auth()->check()) {
            $usuario = usuarios::findOrFail($id);
            $usuario->deshabilitado = 1;
            $usuario->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Usuario Eliminado!',
                'text' => 'Eliminado Correctamente'
            ]);

            return redirect()->route('usuarios.index');
        } else {
            return redirect()->route('login');
        }
    }

    public function credencialqrUsuario(string $usuarioId)
    {
        if (auth()->check()) {
       $usuarios = usuarios::find($usuarioId);
       // return($tipovs);
       
       $customPaper = array(0,0,472.00,300.00);
       $pdf = Pdf::loadView('usuarios.credencialqr', compact('usuarios'))->setPaper($customPaper, 'landscape');
       return $pdf->stream('qrUsuario.pdf');
        //return view('unidad.imvale', compact('unidades','areas','responsables','tipos','operadores','vales'));
    }else{
             return redirect()->route('login');
        }
    
    }

    public function registroalumnosria()
    {
    
            return view('registraralria.index');
       
    }


}
