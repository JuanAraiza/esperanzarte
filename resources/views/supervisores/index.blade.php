@extends('adminlte::page')

@section('title', 'Supervisores')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Supervisores</h3>
        </div>
        <div class="col-sm-6" style="display: flex; justify-content: flex-end; align-items: center;">
            
        <?php /*
        <form action="{{ route('docentes.importar') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-primary">Importar docentes cargados</button>
        </form>
        <form action="{{ route('docentes.importar-asignaciones') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">Importar talleres y horarios</button>
        </form>*/ ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Nuevo Supervisor
        </button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="position: relative;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Supervisor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('supervisores.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
      
      <div class="modal-body">
        
        <div class="row">


         <div class="col-sm-6 relative">
            
            <label for="nombre" class="form-label">Foto</label>
            <label class="bg-white px-4 py-2 rounded-lg cursor-pointer" style="cursor: pointer;">
 <img id="imgPreview" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg" alt="">

        <input type="file" class="hidden" style="display: none;" name="image" accept="image/*" onchange="preview_image(event, '#imgPreview')">
    </label>

</div>

 <div class="col-md-6 row">
 
            <div class="form-group col-sm-6">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group col-sm-6">
                <label for="apaterno" class="form-label">Apellido Paterno</label>
                <input type="text" class="form-control" id="apaterno" name="apaterno" required>
            </div>
           
            <div class="form-group col-sm-6">
                <label for="amaterno" class="form-label">Apellido Materno</label>
                <input type="text" class="form-control" id="amaterno" name="amaterno" >
            </div>

            
            
            <div class="form-group col-sm-6">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" >
            </div>

            <div class="form-group col-sm-6">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" >
            </div>
            <div class="form-group col-sm-6">
                <label for="regimen"  class="form-label">Régimen</label>
                <select class="form-select" id="regimen" name="regimen">
                    <option value="">Seleccionar</option>
                    <option value="RESICO">RESICO</option>
                    <option value="RAEP">RAEP</option>
                </select>
            </div>


            <div class="form-group col-md-6">
                <label for="curp" class="form-label">CURP</label>
                <input type="text" class="form-control" id="curp" name="curp" >
            </div>
            
            <div class="form-group col-sm-6">
                <label for="rfc" class="form-label">RFC</label>
                <input type="text" class="form-control" id="rfc" name="rfc" >
            </div>


            <div class="col-sm-6">
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
            

</div>
            



            



        </div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Guardar Supervisor ">
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

                <table id="tablaDocentes" class="table table-striped table-bordered " style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID Docente</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>RFC</th>
                            <th>Regimen</th>
                            <th>CURP</th>
                            <th>Zona</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supervisores as $supervisor)
                            <tr>
                                <td><div class="card-heart p-0" style="height:150px; width: 120px;">
                                    <img src="{{ Storage::url($supervisor->foto) }}"  style="object-fit: cover; width:100%; height:100%;">
                                </div></td>

                                <td>{{ $supervisor->folio }}</td>
                                <td>{{ $supervisor->nombre }} {{ $supervisor->apaterno }} {{ $supervisor->amaterno }}</td>
                                <td>{{ $supervisor->correo }}</td>
                                <td>{{ $supervisor->telefono }}</td>
                                <td>{{ $supervisor->rfc }}</td>
                                <td>{{ $supervisor->regimen }}</td>
                                <td>{{ $supervisor->curp }}</td>
                                <td>{{ $supervisor->zona }}</td>
                                <td>
                                    <a  data-bs-toggle="modal" data-bs-target="#modalEditDocente{{ $supervisor->id }}" class="btn btn-warning"><span class="fas fa-edit"></span></a>
                                </td>
                                <td>
                                    <form  class="delete-form" action="{{ route('supervisores.destroy', $supervisor->id) }}" method="post">        
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="supervisor" value="{{ $supervisor->id }}"  >
                                            <input name="deshabilitado" value="1" type="hidden">
                                            <button class="btn btn-danger"><span class="fas fa-trash"></span></button>
                                    </form>
                                </td>
                              
                            </tr>


                    <!-- Modal Editar Centro -->
<div class="modal fade" id="modalEditDocente{{ $supervisor->id }}" tabindex="-1" aria-labelledby="modalEditDocenteLabel{{ $supervisor->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">        
        <h5 class="modal-title" id="modalEditDocenteLabel{{ $supervisor->id }}">Editar Docente {{ $supervisor->nombre }} {{ $supervisor->apaterno }} {{ $supervisor->amaterno }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('supervisores.update', $supervisor->id) }}" method="post" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" name="id_docente" value="{{ $supervisor->id }}">
            <div class="row">
                 
            
            <div class="col-sm-6 relative">
            <label for="nombre" class="form-label">Foto</label>
            <label class="bg-white px-4 py-2 rounded-lg cursor-pointer" style="cursor: pointer;">
                @if($supervisor->foto=='supervisores/default.png')
                 <img id="imgPreview" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg" alt="">
                @else
 <img id="imgPreview" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" src="{{ Storage::url($supervisor->foto) }}" alt="">
                @endif

        <input type="file" class="hidden" style="display: none;" name="image" accept="image/*" onchange="preview_image(event, '#imgPreview')">
    </label>

</div>

 <div class="col-sm-6 row">
            
          
              <div class="form-group col-sm-6">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $supervisor->nombre }}" required>
            </div>
            
            <div class="form-group col-sm-6">
                <label for="apaterno" class="form-label">Apellido Paterno</label>
                <input type="text" class="form-control" id="apaterno" name="apaterno" value="{{ $supervisor->apaterno }}" required>
            </div>
           
            <div class="form-group col-sm-6">
                <label for="amaterno" class="form-label">Apellido Materno</label>
                <input type="text" class="form-control" id="amaterno" name="amaterno" value="{{ $supervisor->amaterno }}" >
            </div>
            
            <div class="form-group col-sm-6">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" value="{{ $supervisor->correo }}" >
            </div>

            <div class="form-group col-sm-6">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $supervisor->telefono }}" >
            </div>
            <div class="form-group col-sm-6">
                <label for="regimen"  class="form-label">Régimen</label>
                <select class="form-select" id="regimen" name="regimen">
                    <option value="">Seleccionar</option>
                    <option value="RESICO" {{ $supervisor->regimen == 'RESICO' ? 'selected' : '' }}>RESICO</option>
                    <option value="RAEP" {{ $supervisor->regimen == 'RAEP' ? 'selected' : '' }}>RAEP</option>
                </select>
            </div>


            <div class="form-group col-md-6">
                <label for="curp" class="form-label">CURP</label>
                <input type="text" class="form-control" id="curp" name="curp" value="{{ $supervisor->curp }}" >
            </div>
            
            <div class="form-group col-sm-6">
                <label for="rfc" class="form-label">RFC</label>
                <input type="text" class="form-control" id="rfc" name="rfc" value="{{ $supervisor->rfc }}" >
            </div>


             <div class="col-sm-6">
                <label for="zona" class="form-label">Zona</label>
                <select class="form-select" name="zona" id="zona" required>
                    <option value="">Seleccionar</option>
                    <option value="A" {{ $supervisor->zona == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ $supervisor->zona == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ $supervisor->zona == 'C' ? 'selected' : '' }}>C</option>
                    <option value="D" {{ $supervisor->zona == 'D' ? 'selected' : '' }}>D</option>
                    <option value="E" {{ $supervisor->zona == 'E' ? 'selected' : '' }}>E</option>
                    <option value="Orquestas" {{ $supervisor->zona == 'Orquestas' ? 'selected' : '' }}>Orquestas</option>
                </select>
               
            </div>

</div>


            
</div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Actualizar Supervisor">
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


var table = $('#tablaDocentes').DataTable({
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
