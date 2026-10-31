@extends('adminlte::page')

@section('title', 'Centros')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Centro {{ $centro->centro }}</h3>
        </div>
        <div class="col-sm-6" style="display: flex; justify-content: flex-end; align-items: center;">
         
        </div>
    </div>



@stop

@section('content')

   <x-adminlte-card theme="olive" theme-mode="outline">
   
   <div class="row col-md-12">
    <div class="col-md-2">
        <label><strong>Id Centro / Zona:</strong></label>
        <p>{{ $centro->id_centro }} / {{ $centro->zona }}</p>
    </div>

   <div class="col-md-3">
    <label><strong>Dirección:</strong></label>
    <p>{{ $centro->direccion }}</p>
   </div>
   <div class="col-md-2">
    <label><strong>Enlace:</strong></label>
    <p>{{ $centro->enlace }}</p>
   </div>
   <div class="col-md-2">
    <label><strong>Teléfono:</strong></label>
    <p>{{ $centro->telefono }}</p>
    <p>{{ $centro->telefono_1 }}</p>
   </div>
   <div class="col-md-2">
    <label><strong>Supervisor:</strong></label>
    <p>{{ $centro->supervisor }}</p>
   </div>

    </div>


</x-adminlte-card>
<br>
<x-adminlte-card title="Talleres Asignados" theme="primary" theme-mode="outline">

    <div class="row col-md-12">

    <table class="table table-bordered" id="tablaTalleres">
        <thead>
            <tr>
                <th>Taller</th>
               <th>Docente</th>
               <th>Ultima Asistencia</th>
               <th>Asistentes</th>
                <th>Ubicaciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($clasesAsignadas as $clase)
            @php
                $taller = \DB::table('tallers')->where('id', $clase->taller)->first();
                $docente = \DB::table('docentes')->where('id', $clase->docente)->first();
                $ultimaAsistencia = \DB::table('asistencias')->where('clase_asig_id', $clase->id)->orderBy('created_at', 'desc')->first();
                //$contador = \DB::table('asistencias')->where('clase_asig_id', $clase->id)->where('dia', $ultimaAsistencia->dia)->count();
            @endphp
            <tr>
                <td>{{ $taller->nombre }}</td>
                <td>{{ $docente->nombre }} {{ $docente->paterno }}</td>
                <td>{{ $ultimaAsistencia ? $ultimaAsistencia->dia : 'N/A' }}</td>
                <td class="text-center"><a  data-bs-toggle="modal" data-bs-target="#modalAsistentes{{ $clase->id }}" class="btn btn-warning">{{ $ultimaAsistencia ? \DB::table('asistencias')->where('clase_asig_id', $clase->id)->where('dia', $ultimaAsistencia->dia)->count() : 'N/A' }}</a></td>
                <td class="text-center"><a  data-bs-toggle="modal" data-bs-target="#modalUbicaciones{{ $clase->id }}" class="btn btn-success"><i class="fa-solid fa-map-location-dot"></i></a></td>

            </tr>
            <!-- Modal -->
            <div class="modal fade" id="modalAsistentes{{ $clase->id }}" tabindex="-1" aria-labelledby="modalAsistentesLabel{{ $clase->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAsistentesLabel{{ $clase->id }}">Asistentes del Taller {{ $taller->nombre }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul>
                                @if($ultimaAsistencia)
                                @foreach(\DB::table('asistencias')->where('clase_asig_id', $clase->id)->where('dia', $ultimaAsistencia->dia)->get() as $asistencia)
                                
                                @php
                                
                                    $alumno = \DB::table('alumnos')->where('id', $asistencia->alumno_id)->first();
                                @endphp
                                    <li>{{ $alumno ? $alumno->nombre : 'N/A' }} {{ $alumno ? $alumno->paterno : 'N/A' }} {{ $alumno ? $alumno->materno : 'N/A' }} | {{ $alumno ? $alumno->curp : 'N/A' }}</li>


                              
                                @endforeach
                                @else
                                    <li>No hay asistentes para esta fecha</li>
                                @endif
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal Ubicaciones -->
            <div class="modal fade" id="modalUbicaciones{{ $clase->id }}" tabindex="-1" aria-labelledby="modalUbicacionesLabel{{ $clase->id }}" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalUbicacionesLabel{{ $clase->id }}">Ubicaciones del Taller {{ $taller->nombre }} - {{ $ultimaAsistencia ? $ultimaAsistencia->dia : 'N/A' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="mapa{{ $clase->id }}" style="height: 500px; width: 100%; border-radius: 8px;"></div>
                            <div id="sinDatos{{ $clase->id }}" class="text-muted py-4 text-center d-none">
                                No hay ubicaciones registradas para este taller en la última fecha de asistencia.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.getElementById('modalUbicaciones{{ $clase->id }}').addEventListener('show.bs.modal', function() {
                    const mapId = 'mapa{{ $clase->id }}';
                    const sinDatosId = 'sinDatos{{ $clase->id }}';
                    const claseId = {{ $clase->id }};
                    const ultimaFecha = '{{ $ultimaAsistencia ? $ultimaAsistencia->dia : null }}';

                    if (!ultimaFecha) {
                        document.getElementById(sinDatosId).classList.remove('d-none');
                        document.getElementById(mapId).classList.add('d-none');
                        return;
                    }

                    // Obtener datos de asistencias
                    fetch(`/api/asistencias-ubicaciones/${claseId}/${ultimaFecha}`)
                        .then(response => response.json())
                        .then(data => {
                            const asistencias = data.asistencias || [];

                            if (asistencias.length === 0) {
                                document.getElementById(sinDatosId).classList.remove('d-none');
                                document.getElementById(mapId).classList.add('d-none');
                                return;
                            }

                            document.getElementById(mapId).classList.remove('d-none');
                            document.getElementById(sinDatosId).classList.add('d-none');

                            // Cargar Leaflet si no está disponible
                            if (!window.L) {
                                const linkLeaflet = document.createElement('link');
                                linkLeaflet.rel = 'stylesheet';
                                linkLeaflet.href = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css';
                                document.head.appendChild(linkLeaflet);

                                const scriptLeaflet = document.createElement('script');
                                scriptLeaflet.src = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js';
                                scriptLeaflet.onload = () => inicializarMapa(mapId, asistencias);
                                document.head.appendChild(scriptLeaflet);
                            } else {
                                inicializarMapa(mapId, asistencias);
                            }
                        });
                });

                function inicializarMapa(mapId, asistencias) {
                    const mapElement = document.getElementById(mapId);
                    
                    // Limpiar mapa anterior si existe
                    if (window.mapasInstancias && window.mapasInstancias[mapId]) {
                        window.mapasInstancias[mapId].remove();
                    }

                    if (!window.mapasInstancias) {
                        window.mapasInstancias = {};
                    }

                    const L = window.L;
                    const mapa = L.map(mapId);
                    window.mapasInstancias[mapId] = mapa;
                    const marcadores = [];

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 16,
                        attribution: '&copy; OpenStreetMap contributors',
                    }).addTo(mapa);

                    asistencias.forEach((asistencia) => {
                        if (asistencia.latitud && asistencia.longitud) {
                            const marcador = L.marker([asistencia.latitud, asistencia.longitud])
                                .bindPopup('<strong>' + asistencia.alumno + '</strong><br>' + asistencia.hora)
                                .addTo(mapa);
                            marcadores.push(marcador);
                        }
                    });

                    if (marcadores.length > 0) {
                        mapa.fitBounds(L.featureGroup(marcadores).getBounds(), { padding: [50, 50] });
                    }
                }
            </script>


        @endforeach
        </tbody>
    </table>

       
    </div>

</x-adminlte-card>

<br>
<x-adminlte-card title="Grafica Asistencias por Taller" theme="primary" theme-mode="outline">
    <div class="row">
        <div class="col-12">
            <div style="position: relative; height: 300px;">
                <canvas id="chartAsistenciasTalleres"></canvas>
            </div>
        </div>
    </div>
</x-adminlte-card>
@stop


@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    @php
            $talleresAsistencias = [];
            foreach($clasesAsignadas as $clase) {
                $taller = \DB::table('tallers')->where('id', $clase->taller)->first();
                $ultimaAsistencia = \DB::table('asistencias')->where('clase_asig_id', $clase->id)->orderBy('created_at', 'desc')->first();
                $contador = 0;
                
                if ($ultimaAsistencia) {
                    $contador = \DB::table('asistencias')
                        ->where('clase_asig_id', $clase->id)
                        ->where('dia', $ultimaAsistencia->dia)
                        ->count();
                }
                
                $talleresAsistencias[] = [
                    'nombre' => $taller->nombre ?? 'Taller sin nombre',
                    'asistentes' => $contador
                ];
            }
        @endphp
    @vite('resources/js/centros-map.js')
    <script>
        // Preparar datos para la gráfica
        

        // Obtener datos para la gráfica
        const talleresData = @json($talleresAsistencias);
        const talleresLabels = talleresData.map(t => t.nombre);
        const talleresValues = talleresData.map(t => t.asistentes);

        // Crear gráfica
        const ctxTalleres = document.getElementById('chartAsistenciasTalleres').getContext('2d');
        new Chart(ctxTalleres, {
            type: 'bar',
            data: {
                labels: talleresLabels,
                datasets: [{
                    label: 'Total de Asistentes (Última Clase)',
                    data: talleresValues,
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#ec4899',
                        '#06b6d4',
                        '#14b8a6'
                    ],
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'x',
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', (e) =>{
                e.preventDefault();
                Swal.fire({
                    title: "¿Estas Seguro?",
                    text: "¡No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "¡Si, Eliminar!"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>


<script>


var table = $('#tablaTalleres').DataTable({
        
        "scrollX": true,
         "scrollCollapse": true,
     
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        
       
        "aoColumnDefs": [
         { "bSearchable": false, "aTargets": [ 1 ] }
       ] ,
       retrieve: true,
      "paging": false,      // Desactiva la paginación
        "searching": false,   // Desactiva la barra de búsqueda
        "info": false,        // Desactiva el texto informativo (ej. "Mostrando 1 a 10 de 50 entradas")
        "lengthChange": false,
        "ordering": false,
    });

   table.columns.adjust();





  
      </script>


@endpush
