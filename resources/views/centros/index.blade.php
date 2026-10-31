@extends('adminlte::page')

@section('title', 'Centros')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Centros</h3>
        </div>
        <div class="col-sm-6" style="display: flex; justify-content: flex-end; align-items: center;">
            
        <?php /* 
        <form action="{{ route('centros.importar') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-primary">Importar centros cargados</button>
        </form>
        */ ?>
     
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Nuevo Centro
        </button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Centro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('centros.store') }}" method="POST">
        @csrf
        
      
      <div class="modal-body">
        <div class="row">
            <div class="col-sm-4">
                <label for="centro" class="form-label">Centro Esperanzarte</label>
                <input type="text" class="form-control" id="centro" name="centro" required>
            </div>
            <div class="col-sm-4">
                <label for="zona" class="form-label">Zona</label>
                <select class="form-select" name="zona" id="zona" required>
                    <option value="">Seleccionar</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="Orquestas">Orquestas</option>
                </select>
               
            </div>
            <div class="col-sm-4">
                <label for="tipo_territorio" class="form-label">Tipo de Territorio</label>
                <select class="form-select" name="tipo_territorio" id="tipo_territorio" required>
                    <option value="">Seleccionar</option>
                    <option value="Colonia">Colonia</option>
                    <option value="Comunidad">Comunidad</option>
                </select>
            
                <input type="hidden" class="form-control" id="territorio" name="territorio" value="">
            </div>
            <div class="col-sm-4">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="col-sm-4">
                <label for="google_maps" class="form-label">Google Maps</label>
                <input type="text" class="form-control" id="google_maps" name="google_maps" >
            </div>
            <div class="col-sm-4">
                <label for="enlace" class="form-label">Enlace</label>
                <input type="text" class="form-control" id="enlace" name="enlace" >
            </div>
            <div class="col-sm-4">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" >
            </div>
            <div class="col-sm-4">
                <label for="supervisor" class="form-label">Supervisor</label>
                <input type="text" class="form-control" id="supervisor" name="supervisor" >
            </div>
            <div class="col-sm-4">
                <label for="telefono_1" class="form-label">Teléfono 2</label>
                <input type="text" class="form-control" id="telefono_1" name="telefono_1" >
            </div>

            <div class="col-12 mt-3">
                <label class="form-label">Ubicación del centro</label>
                <div id="mapaNuevoCentro" style="height: 320px;"></div>
                <div id="coordenadasCentro" class="form-text">Da clic en el mapa para posicionar el centro.</div>
                <input type="hidden" id="latitud" name="latitud" required>
                <input type="hidden" id="longitud" name="longitud" required>
            </div>

        </div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Guardar Centro">
      </div>
      </form>
    </div>
  </div>
</div>


        </div>
    </div>



@stop

@section('content')

    <x-adminlte-card theme="primary" theme-mode="outline">

                <table id="tablaCentros" class="table table-striped table-bordered " style="width:100%">
                    <thead>
                        <tr>
                            <th>ID Centro</th>
                            <th>Centro</th>
                            <th>Zona</th>
                            <th>Territorio</th>
                            <th>Dirección</th>
                            <th>Google Maps</th>
                            <th>Enlace</th>
                            <th>Teléfono</th>
                            <th>Supervisor</th>
                            <th>Teléfono 2</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($centros as $centro)
                            <tr>
                                <td>{{ $centro->id_centro }}</td>
                                <td>{{ $centro->centro }}</td>
                                <td>{{ $centro->zona }}</td>
                                <td>{{ $centro->tipo_territorio }}</td>
                                <td>{{ $centro->direccion }}</td>
                                <td style="text-align: center;"><a href="{{ $centro->google_maps }}" target="_blank" class="btn btn-primary btn-block"><i class="fas fa-map-marker-alt"></i></a></td>
                                <td>{{ $centro->enlace }}</td>
                                <td>{{ $centro->telefono }}</td>
                                <td>{{ $centro->supervisor }}</td>
                                <td>{{ $centro->telefono_1 }}</td>
                                <td>
                                    <a  data-bs-toggle="modal" data-bs-target="#modalEditCentro{{ $centro->id_centro }}" class="btn btn-warning"><span class="fas fa-edit"></span></a>
                                </td>
                                <td>
                                    <form  class="delete-form" action="{{ route('centros.destroy', $centro->id_centro) }}" method="post">
                                                        
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="centro" value="{{ $centro->id_centro }}"  >
                                                        <input name="deshabilitado" value="1" type="hidden">
                                                    <button class="btn btn-danger">  <span class="fas fa-trash"></span></button>
                                        </form>
                                </td>
                                <td>
                                    <a href="{{ route('centros.reporte', $centro->id) }}" class="btn btn-info"><i class="fa-solid fa-chart-pie"></i></a>
                                </td>
                            </tr>



                    <!-- Modal Editar Centro -->
<div class="modal fade" id="modalEditCentro{{ $centro->id_centro }}" tabindex="-1" aria-labelledby="modalEditCentroLabel{{ $centro->id_centro }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">        
        <h5 class="modal-title" id="modalEditCentroLabel{{ $centro->id_centro }}">Editar Centro {{ $centro->centro }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('centros.update', $centro->id) }}" method="post">
          @csrf
          @method('PUT')
          <input type="hidden" name="id_centro" value="{{ $centro->id }}">
            <div class="row">
                <div class="col-sm-4">
                    <label for="centro" class="form-label">Centro Esperanzarte</label>
                    <input type="text" class="form-control" id="centro" name="centro" value="{{ $centro->centro }}" required>
                </div>
                <div class="col-sm-4">
                <label for="zona" class="form-label">Zona</label>
                <select class="form-select" name="zona" id="zona" required>
                    <option value="">Seleccionar</option>
                    <option value="A" {{ $centro->zona == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ $centro->zona == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ $centro->zona == 'C' ? 'selected' : '' }}>C</option>
                    <option value="D" {{ $centro->zona == 'D' ? 'selected' : '' }}>D</option>
                    <option value="E" {{ $centro->zona == 'E' ? 'selected' : '' }}>E</option>
                    <option value="Orquestas" {{ $centro->zona == 'Orquestas' ? 'selected' : '' }}>Orquestas</option>
                </select>
              
            </div>


<div class="col-sm-4">
                <label for="tipo_territorio" class="form-label">Tipo de Territorio</label>
                <select class="form-select" name="tipo_territorio" id="tipo_territorio" required>
                    <option value="">Seleccionar</option>
                    <option value="Colonia" {{ $centro->tipo_territorio == 'Colonia' ? 'selected' : '' }}>Colonia</option>
                    <option value="Comunidad" {{ $centro->tipo_territorio == 'Comunidad' ? 'selected' : '' }}>Comunidad</option>
                </select>
                <input type="hidden" name="territorio" value="">
            </div>
            
            
            <div class="col-sm-4">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $centro->direccion }}" required>
            </div>
            <div class="col-sm-4">
                <label for="google_maps" class="form-label">Google Maps</label>
                <input type="text" class="form-control" id="google_maps" name="google_maps" value="{{ $centro->google_maps }}">
            </div>
            <div class="col-sm-4">
                <label for="enlace" class="form-label">Enlace</label>
                <input type="text" class="form-control" id="enlace" name="enlace" value="{{ $centro->enlace }}">
            </div>
            <div class="col-sm-4">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $centro->telefono }}">
            </div>
            <div class="col-sm-4">
                <label for="supervisor" class="form-label">Supervisor</label>
                <input type="text" class="form-control" id="supervisor" name="supervisor" value="{{ $centro->supervisor }}">
            </div>
            <div class="col-sm-4">
                <label for="telefono_1" class="form-label">Teléfono 2</label>
                <input type="text" class="form-control" id="telefono_1" name="telefono_1" value="{{ $centro->telefono_1 }}">
            </div>

            <div class="col-12 mt-3">
                <label class="form-label">Ubicación del centro</label>
                <div class="mapa-editar-centro" style="height: 320px;" data-latitud="{{ $centro->latitud }}" data-longitud="{{ $centro->longitud }}"></div>
                <div class="coordenadas-editar-centro form-text">Da clic en el mapa para ajustar la ubicación.</div>
                <input type="hidden" class="latitud-editar-centro" name="latitud" value="{{ $centro->latitud }}" required>
                <input type="hidden" class="longitud-editar-centro" name="longitud" value="{{ $centro->longitud }}" required>
            </div>

</div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Actualizar Centro">
      </div>
        </form>
    </div>
  </div>   
</div>
</div>
                    





                        @endforeach
                    </tbody>
                </table>

    </x-adminlte-card>

@stop


@push('js')
    @vite('resources/js/centros-map.js')
    <script>
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


var table = $('#tablaCentros').DataTable({
        /*order: [
            [0, 'desc'],
        
        ],*/
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
       retrieve: true
      
    });
    //********Esta bendita linea hace la magia, adjusta el header de la tabla con el body 
   table.columns.adjust();





  
      </script>


@endpush
