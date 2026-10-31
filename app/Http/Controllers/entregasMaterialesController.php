<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\docente;
use App\Models\EntregaMaterial;
use App\Models\Material;
use App\Models\MaterialesEntregado;

class entregasMaterialesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $busqueda = trim((string) $request->query('buscar', ''));
        $docentes = collect();
        $docenteSeleccionado = null;

        if ($request->filled('qr')) {
            $clave = $this->extraerClaveQr($request->query('qr'));
            $docenteSeleccionado = docente::where('clave', $clave)->first();

            if (!$docenteSeleccionado) {
                return redirect()->route('entregasmateriales.index')
                    ->with('error', 'No se encontró un docente para el código QR escaneado.');
            }
        } elseif ($request->filled('docente_id')) {
            $docenteSeleccionado = docente::findOrFail($request->query('docente_id'));
        } elseif ($busqueda !== '') {
            $docentes = docente::where('folio', 'like', "%{$busqueda}%")
                ->orWhere('nombre', 'like', "%{$busqueda}%")
                ->orWhere('paterno', 'like', "%{$busqueda}%")
                ->orWhere('materno', 'like', "%{$busqueda}%")
                ->orderBy('paterno')
                ->limit(20)
                ->get();
        }

        $materiales = $docenteSeleccionado
            ? Material::where('deshabilitado', 0)->orderBy('material')->get()
            : collect();

        return view('entregasmateriales.index', compact('busqueda', 'docentes', 'docenteSeleccionado', 'materiales'));
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
        $datos = $request->validate([
            'docente_id' => 'required|exists:docentes,id',
            'observaciones' => 'nullable|string|max:5000',
            'evidencia' => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
            'materiales' => 'required|array',
            'materiales.*.id' => 'required|exists:materials,id',
            'materiales.*.cantidad' => 'required|integer|min:0',
        ]);

        $materialesSolicitados = collect($datos['materiales'])
            ->filter(fn ($material) => (int) $material['cantidad'] > 0);

        if ($materialesSolicitados->isEmpty()) {
            return back()->withErrors(['materiales' => 'Indica al menos una cantidad para entregar.'])->withInput();
        }

        $rutaEvidencia = $request->file('evidencia')->store('evidencias_entregas', 'public');

        $entrega = DB::transaction(function () use ($datos, $materialesSolicitados, $rutaEvidencia) {
            $entrega = EntregaMaterial::create([
                'docente' => $datos['docente_id'],
                'fecha' => now()->toDateString(),
                'hora' => now()->toTimeString(),
                'estatus' => 1,
                'observaciones' => $datos['observaciones'] ?? null,
                'evidencia' => $rutaEvidencia,
            ]);
            $entrega->folio = 'EM-'.str_pad((string) $entrega->id, 6, '0', STR_PAD_LEFT);
            $entrega->save();

            foreach ($materialesSolicitados as $materialSolicitado) {
                $material = Material::where('deshabilitado', 0)
                    ->lockForUpdate()
                    ->findOrFail($materialSolicitado['id']);
                $cantidad = (int) $materialSolicitado['cantidad'];

                if ((int) $material->stock < $cantidad) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'materiales' => "El material {$material->material} no tiene existencias suficientes.",
                    ]);
                }

                MaterialesEntregado::create([
                    'entrega_material' => $entrega->id,
                    'material' => $material->id,
                    'cantidad' => $cantidad,
                ]);

                $material->decrement('stock', $cantidad);
            }

            return $entrega;
        });

        return redirect()->route('listadoentregas.pdf', ['entrega' => $entrega->id]);
    }

    private function extraerClaveQr(string $contenido): string
    {
        $query = parse_url($contenido, PHP_URL_QUERY);
        if ($query !== null) {
            parse_str($query, $parametros);
            return (string) ($parametros['esperanzaDE'] ?? $contenido);
        }

        return trim($contenido);
    }

    public function listadoEntregas(Request $request)
    {
        $docenteId = $request->query('docente_id');
        $entregas = EntregaMaterial::where('deshabilitado', 0)
            ->when($docenteId, fn ($consulta) => $consulta->where('docente', $docenteId))
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->get();

        return view('listadoentregas.index', compact('entregas', 'docenteId'));
    }

    public function pdfEntrega(EntregaMaterial $entrega)
    {
        $docente = docente::findOrFail($entrega->docente);
        $materialesEntregados = MaterialesEntregado::where('entrega_material', $entrega->id)
            ->get()
            ->map(function ($materialEntregado) {
                $materialEntregado->materialRegistro = Material::find($materialEntregado->material);
                return $materialEntregado;
            });

        $pdf = Pdf::loadView('listadoentregas.pdf', compact('entrega', 'docente', 'materialesEntregados'));

        return $pdf->stream('entrega_material_'.$entrega->folio.'.pdf');
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
    public function destroy(string $id)
    {
        //
    }
}
