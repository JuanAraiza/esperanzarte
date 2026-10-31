<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

use App\Models\alumno;
use App\Models\colonia;
use App\Models\localidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class alumnosController extends Controller
{
    private function prepararArchivoParaCurl($rutaImagen)
    {
        $resultado = [
            'ruta_local' => null,
            'nombre_archivo' => null,
            'mime' => 'application/octet-stream',
            'temporal' => null,
        ];

        if (filter_var($rutaImagen, FILTER_VALIDATE_URL)) {
            $contenidoRemoto = @file_get_contents($rutaImagen);
            if ($contenidoRemoto === false) {
                throw new \RuntimeException('No se pudo descargar la imagen remota: ' . $rutaImagen);
            }

            $archivoTemporal = tempnam(sys_get_temp_dir(), 'ocr_');
            if ($archivoTemporal === false || file_put_contents($archivoTemporal, $contenidoRemoto) === false) {
                throw new \RuntimeException('No se pudo crear archivo temporal para enviar la imagen.');
            }

            $nombreArchivo = basename((string) parse_url($rutaImagen, PHP_URL_PATH));
            if ($nombreArchivo === '') {
                $nombreArchivo = 'imagen.jpg';
            }

            $resultado['ruta_local'] = $archivoTemporal;
            $resultado['nombre_archivo'] = $nombreArchivo;
            $resultado['temporal'] = $archivoTemporal;
        } else {
            if (!file_exists($rutaImagen)) {
                throw new \RuntimeException('No se encontro la imagen en la ruta: ' . $rutaImagen);
            }
            if (!is_readable($rutaImagen)) {
                throw new \RuntimeException('La imagen no tiene permisos de lectura: ' . $rutaImagen);
            }

            $resultado['ruta_local'] = $rutaImagen;
            $resultado['nombre_archivo'] = basename($rutaImagen);
        }

        if (function_exists('mime_content_type')) {
            $mimeDetectado = mime_content_type($resultado['ruta_local']);
            if ($mimeDetectado !== false && $mimeDetectado !== '') {
                $resultado['mime'] = $mimeDetectado;
            }
        }

        return $resultado;
    }

    private function consumirOcrPorRuta($rutaImagen, $endpoint = 'https://api-ria.okip.com.mx/api/v1/n8n/ocr-identification')
    {
        $archivo = $this->prepararArchivoParaCurl($rutaImagen);
        $rutaLocal = (string) $archivo['ruta_local'];
        $mime = (string) $archivo['mime'];
        $nombreArchivo = (string) $archivo['nombre_archivo'];

        $params = [
            'file' => new \CURLFile($rutaLocal, $mime, $nombreArchivo),
        ];

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($archivo['temporal'] !== null && file_exists($archivo['temporal'])) {
            unlink($archivo['temporal']);
        }

        if ($response === false) {
            return [
                'ok' => false,
                'http' => $statusCode,
                'error' => $curlError,
                'respuesta' => null,
            ];
        }

        return [
            'ok' => $statusCode >= 200 && $statusCode < 300,
            'http' => $statusCode,
            'error' => null,
            'respuesta' => json_decode($response, true),
            'raw' => $response,
        ];
    }

    private function mapearOcrParaTabla($payload)
    {
        if (!is_array($payload)) {
            return null;
        }

        $item = $payload['data'][0] ?? null;
        if (!is_array($item)) {
            return null;
        }

        $birthDateRaw = isset($item['birthDate']) ? trim((string) $item['birthDate']) : null;
        $birthDateSql = null;
        if (!empty($birthDateRaw)) {
            $fecha = \DateTime::createFromFormat('d/m/Y', $birthDateRaw);
            if ($fecha !== false) {
                $birthDateSql = $fecha->format('Y-m-d');
            }
        }

        $expirationYear = isset($item['expiration']) ? (int) $item['expiration'] : null;

        return [
            'full_name' => isset($item['fullName']) ? trim((string) $item['fullName']) : null,
            'last_name' => isset($item['lastName']) ? trim((string) $item['lastName']) : null,
            'second_last_name' => isset($item['secondLastName']) ? trim((string) $item['secondLastName']) : null,
            'curp' => isset($item['curp']) ? strtoupper(trim((string) $item['curp'])) : null,
            'birth_date_raw' => $birthDateRaw,
            'birth_date_sql' => $birthDateSql,
            'gender' => isset($item['gender']) ? trim((string) $item['gender']) : null,
            'address' => isset($item['address']) ? trim((string) $item['address']) : null,
            'exterior_number' => isset($item['exteriorNumber']) ? trim((string) $item['exteriorNumber']) : null,
            'neighborhood' => isset($item['neighborhood']) ? trim((string) $item['neighborhood']) : null,
            'city' => isset($item['city']) ? trim((string) $item['city']) : null,
            'state' => isset($item['state']) ? trim((string) $item['state']) : null,
            'zip_code' => isset($item['zipCode']) ? trim((string) $item['zipCode']) : null,
            'expiration_year' => $expirationYear,
            'electoral_key' => isset($item['electoralKey']) ? strtoupper(trim((string) $item['electoralKey'])) : null,
            'section' => isset($item['section']) ? trim((string) $item['section']) : null,
            'card_type' => isset($item['cardType']) ? trim((string) $item['cardType']) : null,
            'api_success' => !empty($payload['success']) ? 1 : 0,
            'api_status' => isset($payload['status']) ? (int) $payload['status'] : null,
            'api_method' => isset($payload['method']) ? (string) $payload['method'] : null,
            'api_path' => isset($payload['path']) ? (string) $payload['path'] : null,
        ];
    }

    private function mapearCamposOcr(?array $fila): array
    {
        if (empty($fila)) {
            return [];
        }

        $campos = [];
       if (!empty($fila['full_name'])) {
            $campos['nombre'] = trim((string) $fila['full_name']);
        }

        if (!empty($fila['last_name'])) {
            $campos['paterno'] = trim((string) $fila['last_name']);
        }
        if (!empty($fila['second_last_name'])) {
            $campos['materno'] = trim((string) $fila['second_last_name']);
        }
        if (!empty($fila['curp'])) {
            $campos['curp'] = strtoupper(trim((string) $fila['curp']));
        }
        if (!empty($fila['birth_date_sql'])) {
            $campos['fecha_nacimiento'] = $fila['birth_date_sql'];
        }
        if (!empty($fila['gender'])) {
            $genero = strtolower(trim((string) $fila['gender']));
            if (in_array($genero, ['m', 'male', 'masculino', 'h', 'hombre'])) {
                $campos['sexo'] = 'HOMBRE';
            } elseif (in_array($genero, ['f', 'female', 'femenino', 'mujer'])) {
                $campos['sexo'] = 'MUJER';
            }
        }
        if (!empty($fila['address'])) {
            $campos['domicilio'] = trim((string) $fila['address']);
        }
        if (!empty($fila['state'])) {
            $campos['estado'] = trim((string) $fila['state']);
        }
        if (!empty($fila['city'])) {
            $campos['municipio'] = trim((string) $fila['city']);
        }
        if (!empty($fila['zip_code'])) {
            $campos['cod_postal'] = trim((string) $fila['zip_code']);
        }
        if (!empty($fila['electoral_key'])) {
            $campos['clave_electoral'] = strtoupper(trim((string) $fila['electoral_key']));
        }
        if (!empty($fila['section'])) {
            $campos['seccion'] = trim((string) $fila['section']);
        }
        if (!empty($fila['expiration_year'])) {
            $campos['vigencia'] = (int) $fila['expiration_year'];
        }
        if (!empty($fila['exterior_number'])) {
            $campos['num_ext'] = trim((string) $fila['exterior_number']);
        }
        if (!empty($fila['register_year'])) {
            $campos['numero_emision'] = trim((string) $fila['register_year']);
        }
        if (!empty($fila['neighborhood'])) {
            $campos['colonia'] = trim((string) $fila['neighborhood']);
             $campos['localidad'] = trim((string) $fila['neighborhood']);
        }
       
       

        return array_filter($campos, function ($valor) {
            return $valor !== null && $valor !== '';
        });
    }

    private function aplicarCamposOcr(array $datosAlumno, ?array $fila): array
    {
        $camposOcr = $this->mapearCamposOcr($fila);

        foreach ($camposOcr as $campo => $valor) {
            if (empty($datosAlumno[$campo])) {
                $datosAlumno[$campo] = $valor;
            }
        }

        return $datosAlumno;
    }

    private function asignarCamposOcrAlModelo($alumno, ?array $fila): void
    {
        if (empty($fila)) {
            return;
        }

        $camposOcr = $this->mapearCamposOcr($fila);
        $mapeoCampos = [
            'nombre' => 'nombre',
            'paterno' => 'paterno',
            'materno' => 'materno',
            'curp' => 'curp',
            'fecha_nacimiento' => 'fecha_nacimiento',
            'sexo' => 'sexo',
            'domicilio' => 'domicilio',
            'estado' => 'estado',
            'municipio' => 'municipio',
            'cod_postal' => 'cod_postal',
            'clave_electoral' => 'clave_electoral',
            'seccion' => 'seccion',
            'vigencia' => 'vigencia',
            'num_ext' => 'num_ext',
            'colonia' => 'colonia',
            'localidad' => 'localidad',
        ];

        foreach ($camposOcr as $campo => $valor) {
            $atributo = $mapeoCampos[$campo] ?? null;
            if ($atributo !== null) {
                $alumno->{$atributo} = $valor;
            }
        }
    }

    private function construirGeneroFolio(?string $sexo, ?string $fechaNacimiento): string
    {
        $sexoNormalizado = strtoupper(trim((string) $sexo));
        if ($sexoNormalizado === 'HOMBRE') {
            $genero = 'H';
        } elseif ($sexoNormalizado === 'MUJER') {
            $genero = 'M';
        } else {
            $genero = 'X';
        }

        $rangoEdad = 'U';
        try {
            if (!empty($fechaNacimiento)) {
                $edad = \Carbon\Carbon::parse($fechaNacimiento)->age;
                $rangoEdad = $edad < 18 ? 'M' : 'A';
            }
        } catch (\Throwable $e) {
            $rangoEdad = 'U';
        }

        return $genero.'|'.$rangoEdad;
    }

    private function guardarCatUbicacion(alumno $alumno): void
    {
        if ($alumno->ur == 'COLONIA') {
            $existecolonia = colonia::where('nombre', $alumno->colonia)->first();
            if (!$existecolonia && !empty($alumno->colonia)) {
                $nuevacolonia = new colonia();
                $nuevacolonia->nombre = $alumno->colonia;
                $nuevacolonia->seccion = $alumno->seccion;
                $nuevacolonia->deshabilitado = 0;
                $nuevacolonia->save();
            }

            return;
        }

        if ($alumno->ur == 'LOCALIDAD') {
            $exitelocalidad = localidad::where('nombre', $alumno->localidad)->first();
            if (!$exitelocalidad && !empty($alumno->localidad)) {
                $nuevalocalidad = new localidad();
                $nuevalocalidad->nombre = $alumno->localidad;
                $nuevalocalidad->seccion = $alumno->seccion;
                $nuevalocalidad->deshabilitado = 0;
                $nuevalocalidad->save();
            }
        }
    }

    private function normalizarCurp(?string $curp): ?string
    {
        if ($curp === null) {
            return null;
        }

        $curpNormalizado = trim((string) $curp);
        if ($curpNormalizado === '') {
            return null;
        }

        return strtoupper($curpNormalizado);
    }

    private function buscarAlumnoPorCurp(?string $curp): ?alumno
    {
        $curpNormalizado = $this->normalizarCurp($curp);
        if ($curpNormalizado === null) {
            return null;
        }

        return alumno::whereRaw('UPPER(TRIM(curp)) = ?', [$curpNormalizado])->first();
    }

    private function limpiarArchivosDeRegistro(array $rutasArchivos): void
    {
        foreach ($rutasArchivos as $ruta) {
            if (!empty($ruta)) {
                Storage::disk('public')->delete($ruta);
            }
        }
    }

    private function responderDuplicadoPorCurp(?alumno $alumnoExistente): \Illuminate\Http\RedirectResponse
    {
        $mensaje = 'Ya existe un alumno con la misma CURP.';
        if ($alumnoExistente?->folio) {
            $mensaje .= ' Folio del alumno registrado: '.$alumnoExistente->folio;
        }

        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Alumno duplicado',
            'text' => $mensaje,
        ]);

        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if(auth()->check()) {
            $alumnos = alumno::where('deshabilitado', 0)->orderBy('id', 'desc')->get();
            $historialAsistencias = DB::table('asistencias as a')
                ->leftJoin('docentes as d', 'd.id', '=', 'a.docente_id')
                ->leftJoin('clase_asignadas as ca', 'ca.id', '=', 'a.clase_asig_id')
                ->leftJoin('centros as c', 'c.id', '=', 'ca.centro')
                ->leftJoin('tallers as t', 't.id', '=', 'ca.taller')
                ->select(
                    'a.id',
                    'a.alumno_id',
                    'a.dia',
                    'a.hora',
                    'a.latitud',
                    'a.longitud',
                    DB::raw("TRIM(CONCAT(COALESCE(d.nombre, ''), ' ', COALESCE(d.paterno, ''), ' ', COALESCE(d.materno, ''))) as docente_nombre"),
                    DB::raw("COALESCE(c.centro, '') as centro_nombre"),
                    DB::raw("COALESCE(t.nombre, CONCAT('Taller #', ca.taller)) as taller_nombre")
                )
                ->whereIn('a.alumno_id', $alumnos->pluck('id'))
                ->orderByDesc('a.id')
                ->get()
                ->groupBy('alumno_id');

            $colonias = colonia::where('deshabilitado', 0)->get();
            $localidades = localidad::where('deshabilitado', 0)->get();
            $estados = [
                'Aguascalientes','Baja California','Baja California Sur','Campeche','Chiapas','Chihuahua','Coahuila','Colima','Ciudad de México','Durango','Guanajuato','Guerrero','Hidalgo','Jalisco','México','Michoacán','Morelos','Nayarit','Nuevo León','Oaxaca','Puebla','Querétaro','Quintana Roo','San Luis Potosí','Sinaloa','Sonora','Tabasco','Tamaulipas','Tlaxcala','Veracruz','Yucatán','Zacatecas'
            ];
            $municipios = [
                'Aguascalientes' => ['Aguascalientes','Asientos','Calvillo','Cosío','Jesús María','Pabellón de Arteaga','Rincón de Romos','San José de Gracia','Tepezalá','El Llano','San Francisco de los Romo'],
                'Baja California' => ['Ensenada','Mexicali','Playas de Rosarito','Tecate','Tijuana'],
                'Baja California Sur' => ['Comondú','La Paz','Loreto','Los Cabos','Mulegé'],
                'Campeche' => ['Campeche','Carmen','Champotón','Hecelchakán','Hopelchén','Palizada','San Francisco de Campeche','Tenabo'],
                'Chiapas' => ['Acacoyagua','Arriaga','Cintalapa','Comitán','Chiapa de Corzo','Tuxtla Gutiérrez','Tapachula','Pichucalco'],
                'Chihuahua' => ['Ahumada','Camargo','Chihuahua','Cuauhtémoc','Delicias','Juárez','Parral','Guachochi'],
                'Coahuila' => ['Saltillo','Monclova','Piedras Negras','Acuña','Torreón','Matamoros','San Pedro','Ramos Arizpe'],
                'Colima' => ['Armería','Colima','Comala','Coquimatlán','Cuauhtémoc','Ixtlahuacán','Manzanillo','Minatitlán','Tecomán','Villa de Álvarez'],
                'Ciudad de México' => ['Álvaro Obregón','Azcapotzalco','Benito Juárez','Coyoacán','Cuajimalpa','Cuauhtémoc','Gustavo A. Madero','Iztacalco','Iztapalapa','Magdalena Contreras','Miguel Hidalgo','Milpa Alta','Tláhuac','Tlálpan','Venustiano Carranza','Xochimilco'],
                'Durango' => ['Durango','Gómez Palacio','Lerdo','Canatlán','Peñón Blanco','San Juan del Río','Santiago Papasquiaro'],
                'Guanajuato' => ['Celaya','Irapuato','León','Salamanca','Guanajuato','Dolores Hidalgo','San Miguel de Allende','Uriangato'],
                'Guerrero' => ['Acapulco','Chilpancingo','Iguala','Taxco','Zihuatanejo','Tixtla','Coyuca de Benítez'],
                'Hidalgo' => ['Pachuca','Tulancingo','Huejutla','Tula','Tepeji','Actopan','Ixmiquilpan'],
                'Jalisco' => ['Guadalajara','Zapopan','Tlaquepaque','Puerto Vallarta','Lagos de Moreno','Tlajomulco','Tonalá','Autlán'],
                'México' => ['Ecatepec','Nezahualcóyotl','Toluca','Atlacomulco','Coacalco','Naucalpan','Tlalnepantla','Zumpango'],
                'Michoacán' => ['Morelia','Uruapan','Zamora','Apatzingán','Lázaro Cárdenas','Pátzcuaro','Sahuayo','Uruapan'],
                'Morelos' => ['Cuernavaca','Jiutepec','Temixco','Yautepec','Emiliano Zapata','Miacatlán','Puente de Ixtla'],
                'Nayarit' => ['Tepic','Compostela','San Blas','Xalisco','Bahía de Banderas'],
                'Nuevo León' => ['Monterrey','Santa Catarina','San Nicolás','Guadalupe','Apodaca','General Escobedo','Cadereyta Jiménez'],
                'Oaxaca' => ['Oaxaca de Juárez','Salina Cruz','Juchitán','Tlacolula','Miahuatlán','Santo Domingo Tehuantepec'],
                'Puebla' => ['Puebla','Tehuacán','Atlixco','Cholula','Teziutlán','San Martín Texmelucan','Cuautlancingo'],
                'Querétaro' => ['Querétaro','San Juan del Río','Corregidora','El Marqués','Pedro Escobedo'],
                'Quintana Roo' => ['Chetumal','Cancún','Playa del Carmen','Cozumel','Isla Mujeres','Benito Juárez','José María Morelos'],
                'San Luis Potosí' => ['San Luis Potosí','Soledad de Graciano Sánchez','Matehuala','Rioverde','Tamazunchale','Ciudad Valles'],
                'Sinaloa' => ['Culiacán','Mazatlán','Los Mochis','Guamúchil','Ahome','Elota','Navolato'],
                'Sonora' => ['Hermosillo','Cajeme','Nogales','San Luis Río Colorado','Guaymas','Navojoa','Agua Prieta'],
                'Tabasco' => ['Villahermosa','Comalcalco','Cárdenas','Paraíso','Balancán','Jalapa','Macuspana'],
                'Tamaulipas' => ['Ciudad Victoria','Reynosa','Matamoros','Tampico','Nuevo Laredo','Madero','Altamira'],
                'Tlaxcala' => ['Tlaxcala','Apizaco','Chiautempan','San Pablo del Monte','Huamantla','Zacatelco'],
                'Veracruz' => ['Veracruz','Xalapa','Coatzacoalcos','Poza Rica','Orizaba','Córdoba','Martínez de la Torre'],
                'Yucatán' => ['Mérida','Kanasín','Valladolid','Progreso','Ticul','Tizimín'],
                'Zacatecas' => ['Zacatecas','Fresnillo','Jerez','Loreto','Guadalupe','Rio Grande','Mazapil']
            ];
            return view('alumnos.index', compact('alumnos', 'historialAsistencias', 'colonias', 'localidades', 'estados', 'municipios'));
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
       if(auth()->check()) {

       

            $request->validate([
               
                'nombre' => 'required',
                'paterno' => 'required',
                'materno' => 'required',
                // Add validation rules for other fields as needed
            ]);

            $datosAlumno = $request->all();
            $ocrFila = null;
            $resultadoOcr = null;

            $imgfoto='default.png';
            if($request->hasFile('image1')){
            $upload = $request->file('image1');
            $extension = $upload->getClientOriginalExtension();
            $imgfoto = $request['nombre'].'-'.$request['paterno'].'-'.$request['materno'].date('YmdHsi').'-FOTOIMG.'.$extension;
            $image = Image::decodeSplFileInfo($upload)
                ->scale(width:800)
                ->encodeUsingFileExtension($extension, quality: 70);
            Storage::disk('public')->put('alumnos/'.$imgfoto, (string) $image);
            }



            $imginef='default.png';
            if($request->hasFile('image2')){
            $upload = $request->file('image2');
            $extension = $upload->getClientOriginalExtension();
            $imginef = $request['nombre'].'-'.$request['paterno'].'-'.$request['materno'].date('YmdHsi').'-INEFIMG.'.$extension;
            $image = Image::decodeSplFileInfo($upload)
                ->scale(width:800)
                ->encodeUsingFileExtension($extension, quality: 70);
            Storage::disk('public')->put('alumnos/'.$imginef, (string) $image);

            try {
                $rutaImagen = Storage::disk('public')->path('alumnos/'.$imginef);
                $resultadoOcr = $this->consumirOcrPorRuta($rutaImagen);
                if (!empty($resultadoOcr['ok']) && !empty($resultadoOcr['respuesta'])) {
                    $ocrFila = $this->mapearOcrParaTabla($resultadoOcr['respuesta']);
                    $datosAlumno = $this->aplicarCamposOcr($datosAlumno, $ocrFila);
                }
            } catch (\Throwable $e) {
                $datosAlumno['txt_regreso'] = $e->getMessage();
            }
            }



            $imginer='default.png';
            if($request->hasFile('image3')){
            $upload = $request->file('image3');
            $extension = $upload->getClientOriginalExtension();
            $imginer = $request['nombre'].'-'.$request['paterno'].'-'.$request['materno'].date('YmdHsi').'-INERIMG.'.$extension;
            $image = Image::decodeSplFileInfo($upload)
                ->scale(width:800)
                ->encodeUsingFileExtension($extension, quality: 70);
            Storage::disk('public')->put('alumnos/'.$imginer, (string) $image);
            }


            $alumnoDuplicado = $this->buscarAlumnoPorCurp($datosAlumno['curp'] ?? null);
            if ($alumnoDuplicado) {
                $this->limpiarArchivosDeRegistro([
                    'alumnos/'.$imgfoto,
                    'alumnos/'.$imginef,
                    'alumnos/'.$imginer,
                ]);

                return $this->responderDuplicadoPorCurp($alumnoDuplicado);
            }

            $alumno = alumno::create(array_merge($datosAlumno, [
                'foto' => 'alumnos/'.$imgfoto,
                'inef' => 'alumnos/'.$imginef,
                'inea' => 'alumnos/'.$imginer,
            ]));

            if($alumno->sexo=='HOMBRE'){
                $genero='H';
            }else{
                $genero='M';
            }

            $edad = \Carbon\Carbon::parse($alumno->fecha_nacimiento)->age;
            if($edad < 18){
                $genero.='|M';
            }else{
                $genero.='|A';
            }

            $alumno->folio = str_pad($alumno->id, 5, '0', STR_PAD_LEFT).'|'.date('Y').'|'.$genero;
            $seccion=0;
            if($alumno->ur=='COLONIA'){
            $seccion = colonia::where('nombre', $alumno->colonia)->value('seccion');
            }

            if($alumno->ur=='LOCALIDAD'){
                $seccion = localidad::where('nombre', $alumno->localidad)->value('seccion');
            }

            $alumno->seccion = $seccion;
            $alumno->estatus = 1;
            $alumno->deshabilitado = 0;
            $alumno->edad = $edad;
            $alumno->id_user = auth()->user()->id;
            $alumno->save();
            if (!empty($ocrFila)) {
                $this->asignarCamposOcrAlModelo($alumno, $ocrFila);
                $alumno->save();
            }

            $nombreParaClave = $datosAlumno['nombre'] ?? $request->input('nombre');
            $paternoParaClave = $datosAlumno['paterno'] ?? $request->input('paterno');
            $maternoParaClave = $datosAlumno['materno'] ?? $request->input('materno');
            $alumno->txt_regreso = !empty($resultadoOcr['respuesta']) ? json_encode($resultadoOcr['respuesta'], JSON_UNESCAPED_UNICODE) : null;


            $alumno->clave = md5(str_pad($alumno->id, 5, '0', STR_PAD_LEFT).'|'.date('Y').'|'.$genero.$nombreParaClave.$paternoParaClave.$maternoParaClave.date('YmdHsi'));

            $alumno->save();

            if($alumno->ur == 'COLONIA') {
           

            $existecolonia = colonia::where('nombre', $alumno->colonia)->first();
            if(!$existecolonia){
                $nuevacolonia = new colonia();
                $nuevacolonia->nombre = $alumno->colonia;
                $nuevacolonia->seccion = $alumno->seccion;
                $nuevacolonia->deshabilitado = 0;
                $nuevacolonia->save();
            }

            }else{

            

            $exitelocalidad = localidad::where('nombre', $alumno->localidad)->first();
            if(!$exitelocalidad){
                $nuevalocalidad = new localidad();
                $nuevalocalidad->nombre = $alumno->localidad;   
                $nuevalocalidad->seccion = $alumno->seccion;
                $nuevalocalidad->deshabilitado = 0;
                $nuevalocalidad->save();
            }

            }

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Alumno Registrado!',
                'text' => 'Registrado Correctamente'
            ]);
            return redirect()->back();
        }else{
            return redirect()->route('home');
        }
        // Redirect if not authenticated
    }

    public function storeRia(Request $request)
    {
        $request->validate($this->reglasRia());

        try {
            $this->procesarRegistroRia($request);
        } catch (\Throwable $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Alumno duplicado',
                'text' => $e->getMessage(),
            ]);

            return redirect()->route('alumnos.registroalumnosria');
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Alumno Registrado!',
            'text' => 'Registro completado correctamente.'
        ]);

        return redirect()->route('alumnos.registroalumnosria');
    }

    public function storeRiaStream(Request $request)
    {
        $validator = Validator::make($request->all(), $this->reglasRia());
        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        return response()->stream(function () use ($request) {
            @ini_set('output_buffering', 'off');
            @ini_set('zlib.output_compression', false);

            $emitir = function (string $tipo, int $step, string $mensaje, array $extra = []) {
                echo json_encode(array_merge([
                    'type' => $tipo,
                    'step' => $step,
                    'message' => $mensaje,
                ], $extra), JSON_UNESCAPED_UNICODE)."\n";

                if (ob_get_level() > 0) {
                    @ob_flush();
                }
                flush();
            };

            try {
                $emitir('progress', 1, 'Validando capturas...');
                $this->procesarRegistroRia($request, function (int $step, string $mensaje) use ($emitir) {
                    $emitir('progress', $step, $mensaje);
                });

                session()->flash('swal', [
                    'icon' => 'success',
                    'title' => '¡Alumno Registrado!',
                    'text' => 'Registro completado correctamente.'
                ]);

                $emitir('done', 8, 'Registro completado correctamente.', [
                    'redirect' => route('alumnos.registroalumnosria'),
                ]);
            } catch (\Throwable $e) {
                $emitir('error', 0, 'No se pudo completar el registro: '.$e->getMessage());
            }
        }, 200, [
            'Content-Type' => 'application/x-ndjson',
            'Cache-Control' => 'no-cache, no-transform',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function reglasRia(): array
    {
        return [
            
            'image1' => 'required|image',
            'image2' => 'required|image',
            'image3' => 'required|image',
            'submission_token' => 'required|string|max:100',
        ];
    }

    private function iniciarRegistroRia(Request $request): string
    {
        $token = trim((string) $request->input('submission_token', ''));
        if ($token === '') {
            throw new \RuntimeException('La solicitud de registro no es valida.');
        }

        $tokensProcesados = session('ria_submission_tokens', []);
        if (array_key_exists($token, $tokensProcesados)) {
            throw new \RuntimeException('Este registro ya fue enviado. Espera a que finalice antes de intentarlo nuevamente.');
        }

        $tokensProcesados[$token] = now()->toDateTimeString();
        session(['ria_submission_tokens' => $tokensProcesados]);

        return $token;
    }

    private function liberarRegistroRia(string $token): void
    {
        $tokensProcesados = session('ria_submission_tokens', []);
        if (!array_key_exists($token, $tokensProcesados)) {
            return;
        }

        unset($tokensProcesados[$token]);
        session(['ria_submission_tokens' => $tokensProcesados]);
    }

    private function procesarRegistroRia(Request $request, ?callable $notificar = null): alumno
    {
        $tokenRegistro = $this->iniciarRegistroRia($request);

        $reportar = function (int $step, string $mensaje) use ($notificar): void {
            if ($notificar !== null) {
                $notificar($step, $mensaje);
            }
        };

        try {
            $datosAlumno = $request->only(['telefono', 'correo', 'curp']);
            $datosAlumno['ur'] = $datosAlumno['ur'] ?? 'COLONIA';
            $ocrFila = null;
            $resultadoOcr = null;
            $baseArchivo = 'RIA-'.date('YmdHsi');

            $reportar(2, 'Guardando foto de rostro...');
            $uploadFoto = $request->file('image1');
            $extensionFoto = $uploadFoto->getClientOriginalExtension();
            $imgfoto = $baseArchivo.'-FOTOIMG.'.$extensionFoto;
            $imageFoto = Image::decodeSplFileInfo($uploadFoto)
                ->scale(width: 800)
                ->encodeUsingFileExtension($extensionFoto, quality: 70);
            Storage::disk('public')->put('alumnos/'.$imgfoto, (string) $imageFoto);

            $reportar(3, 'Guardando INE frente...');
            $uploadInef = $request->file('image2');
            $extensionInef = $uploadInef->getClientOriginalExtension();
            $imginef = $baseArchivo.'-INEFIMG.'.$extensionInef;
            $imageInef = Image::decodeSplFileInfo($uploadInef)
                ->scale(width: 800)
                ->encodeUsingFileExtension($extensionInef, quality: 70);
            Storage::disk('public')->put('alumnos/'.$imginef, (string) $imageInef);

            $reportar(4, 'Ejecutando OCR de INE...');
            try {
                $rutaImagen = Storage::disk('public')->path('alumnos/'.$imginef);
                $resultadoOcr = $this->consumirOcrPorRuta($rutaImagen);
                if (!empty($resultadoOcr['ok']) && !empty($resultadoOcr['respuesta'])) {
                    $ocrFila = $this->mapearOcrParaTabla($resultadoOcr['respuesta']);
                    $datosAlumno = $this->aplicarCamposOcr($datosAlumno, $ocrFila);
                }
            } catch (\Throwable $e) {
                $datosAlumno['txt_regreso'] = $e->getMessage();
            }

            $reportar(5, 'Guardando INE reverso...');
            $uploadIner = $request->file('image3');
            $extensionIner = $uploadIner->getClientOriginalExtension();
            $imginer = $baseArchivo.'-INERIMG.'.$extensionIner;
            $imageIner = Image::decodeSplFileInfo($uploadIner)
                ->scale(width: 800)
                ->encodeUsingFileExtension($extensionIner, quality: 70);
            Storage::disk('public')->put('alumnos/'.$imginer, (string) $imageIner);

            $reportar(6, 'Validando CURP duplicada...');
            $alumnoDuplicado = $this->buscarAlumnoPorCurp($datosAlumno['curp'] ?? null);
            if ($alumnoDuplicado) {
                $this->limpiarArchivosDeRegistro([
                    'alumnos/'.$imgfoto,
                    'alumnos/'.$imginef,
                    'alumnos/'.$imginer,
                ]);

                throw new \RuntimeException('Ya existe un alumno con la misma CURP. Folio del alumno registrado: '.$alumnoDuplicado->folio);
            }

            $reportar(6, 'Creando alumno...');
            $alumno = alumno::create(array_merge($datosAlumno, [
                'foto' => 'alumnos/'.$imgfoto,
                'inef' => 'alumnos/'.$imginef,
                'inea' => 'alumnos/'.$imginer,
                'estatus' => 1,
                'deshabilitado' => 0,
                'id_user' => auth()->check() ? auth()->user()->id : null,
            ]));

            if (!empty($ocrFila)) {
                $this->asignarCamposOcrAlModelo($alumno, $ocrFila);
            }

            $generoFolio = $this->construirGeneroFolio($alumno->sexo, $alumno->fecha_nacimiento);
            $alumno->folio = str_pad((string) $alumno->id, 5, '0', STR_PAD_LEFT).'|'.date('Y').'|'.$generoFolio;

            $seccion = 0;
            if ($alumno->ur == 'COLONIA' && !empty($alumno->colonia)) {
                $seccion = colonia::where('nombre', $alumno->colonia)->value('seccion') ?? 0;
            }
            if ($alumno->ur == 'LOCALIDAD' && !empty($alumno->localidad)) {
                $seccion = localidad::where('nombre', $alumno->localidad)->value('seccion') ?? 0;
            }
            $alumno->seccion = $seccion;

            $reportar(7, 'Finalizando guardado...');
            $nombreParaClave = $alumno->nombre ?? 'SINNOMBRE';
            $paternoParaClave = $alumno->paterno ?? 'SINPATERNO';
            $maternoParaClave = $alumno->materno ?? 'SINMATERNO';
            $alumno->txt_regreso = !empty($resultadoOcr['respuesta']) ? json_encode($resultadoOcr['respuesta'], JSON_UNESCAPED_UNICODE) : ($datosAlumno['txt_regreso'] ?? null);
            $alumno->clave = md5(
                str_pad((string) $alumno->id, 5, '0', STR_PAD_LEFT)
                .'|'.date('Y')
                .'|'.$generoFolio
                .$nombreParaClave
                .$paternoParaClave
                .$maternoParaClave
                .date('YmdHsi')
            );
            $alumno->edad = \Carbon\Carbon::parse($alumno->fecha_nacimiento)->age;
            $alumno->save();

            $this->guardarCatUbicacion($alumno);

            return $alumno;
        } catch (\Throwable $e) {
            $this->liberarRegistroRia($tokenRegistro);
            throw $e;
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
            $alumno = alumno::findOrFail($id);
            $datosAlumno = $request->all();

            if($request->hasFile('image1')){
                if($alumno->foto){
        Storage::disk('public')->delete($alumno->foto);
    }
            $upload = $request->file('image1');
            $extension = $upload->getClientOriginalExtension();
            $imgfoto = $request['nombre'].'-'.$request['paterno'].'-'.$request['materno'].date('YmdHsi').'-FOTOIMG.'.$extension;
            $image = Image::decodeSplFileInfo($upload)
                ->scale(width:800)
                ->encodeUsingFileExtension($extension, quality: 70);
            Storage::disk('public')->put('alumnos/'.$imgfoto, (string) $image);
            }


            if($request->hasFile('image2')){
                if($alumno->inef){
        Storage::disk('public')->delete($alumno->inef);
    }
            $upload = $request->file('image2');
            $extension = $upload->getClientOriginalExtension();
            $imginef = $request['nombre'].'-'.$request['paterno'].'-'.$request['materno'].date('YmdHsi').'-INEFIMG.'.$extension;
            $image = Image::decodeSplFileInfo($upload)
                ->scale(width:800)
                ->encodeUsingFileExtension($extension, quality: 70);
            Storage::disk('public')->put('alumnos/'.$imginef, (string) $image);

            try {
                $rutaImagen = Storage::disk('public')->path('alumnos/'.$imginef);
                $resultadoOcr = $this->consumirOcrPorRuta($rutaImagen);
                if (!empty($resultadoOcr['ok']) && !empty($resultadoOcr['respuesta'])) {
                    $ocrFila = $this->mapearOcrParaTabla($resultadoOcr['respuesta']);
                    $datosAlumno = $this->aplicarCamposOcr($datosAlumno, $ocrFila);
                }
            } catch (\Throwable $e) {
                $datosAlumno['txt_regreso'] = $e->getMessage();
            }
            $alumno->inef = 'alumnos/'.$imginef;
            }



            
            if($request->hasFile('image3')){
                if($alumno->inea){
                    Storage::disk('public')->delete($alumno->inea);
                }
            $upload = $request->file('image3');
            $extension = $upload->getClientOriginalExtension();
            $imginer = $request['nombre'].'-'.$request['paterno'].'-'.$request['materno'].date('YmdHsi').'-INERIMG.'.$extension;
            $image = Image::decodeSplFileInfo($upload)
                ->scale(width:800)
                ->encodeUsingFileExtension($extension, quality: 70);
            Storage::disk('public')->put('alumnos/'.$imginer, (string) $image);
            $alumno->inea = 'alumnos/'.$imginer;
            }
            $alumno->edad = \Carbon\Carbon::parse($datosAlumno['fecha_nacimiento'])->age;
            $alumno->update($datosAlumno);

            if (!empty($ocrFila)) {
                $this->asignarCamposOcrAlModelo($alumno, $ocrFila);
                $alumno->update();
            }

            if($alumno->ur == 'COLONIA') {
           

            $existecolonia = colonia::where('nombre', $alumno->colonia)->first();
            if(!$existecolonia){
                $nuevacolonia = new colonia();
                $nuevacolonia->nombre = $alumno->colonia;
                $nuevacolonia->seccion = $alumno->seccion;
                $nuevacolonia->deshabilitado = 0;
                $nuevacolonia->save();
            }

            }else{

            

            $exitelocalidad = localidad::where('nombre', $alumno->localidad)->first();
            if(!$exitelocalidad){
                $nuevalocalidad = new localidad();
                $nuevalocalidad->nombre = $alumno->localidad;   
                $nuevalocalidad->seccion = $alumno->seccion;
                $nuevalocalidad->deshabilitado = 0;
                $nuevalocalidad->save();
            }

            }

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Alumno Actualizado!',
                'text' => 'Actualizado Correctamente'
            ]);

            return redirect()->route('alumnos.index');
        } else {
            return redirect()->route('home');
        }   
    }


    public function credencialqrAlumno(string $alumnoId)
    {
        if (auth()->check()) {
       $alumno = alumno::find($alumnoId);
       // return($tipovs);
       
       $customPaper = array(0,0,300.00,472.00);
       $pdf = Pdf::loadView('alumnos.credencialqr', compact('alumno'))->setPaper($customPaper, 'landscape');
       return $pdf->stream('qrAlumno.pdf');
        //return view('unidad.imvale', compact('unidades','areas','responsables','tipos','operadores','vales'));
    }else{
             return redirect()->route('login');
        }
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        if(auth()->check()) {
            $alumno = alumno::findOrFail($id);
            $alumno->deshabilitado = 1;
            $alumno->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Alumno Deshabilitado!',
                'text' => 'Deshabilitado Correctamente'
            ]);

            return redirect()->route('alumnos.index');
        } else {
            return redirect()->route('home');
        }
    }
}
