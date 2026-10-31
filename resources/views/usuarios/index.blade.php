@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Usuarios</h3>
        </div>
        <div class="col-sm-6" style="display: flex; justify-content: flex-end; align-items: center;">
            
        
        <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
 Nuevo Usuario
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('usuarios.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
      
      <div class="modal-body">
        <div class="row">


       



            <div class="col-sm-6">
                <label for="nombre" class="form-label">Nombre Completo </label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
           
            <div class="col-sm-6">
                <label for="email" class="form-label">Correo</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="col-sm-6">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="col-sm-6">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <option value="">Seleccionar Tipo</option>
                    <option value="2">Registros</option>
                    <option value="3">Administrativo Docentes</option>
                    <option value="1">Administrador</option>
                    
                </select>
            </div>

            


</div>
     
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Guardar Usuario ">
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
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Tipo</th>
                           
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>
                                    @if($usuario->tipo == 1)
                                        Administrador
                                    @elseif($usuario->tipo == 2)
                                        Registros
                                    @elseif($usuario->tipo == 3)
                                        Administrativo Docentes
                                    @else
                                        Desconocido
                                    @endif
                                </td>
                                <td>
                                    <a  data-bs-toggle="modal" data-bs-target="#modalEditUsuario{{ $usuario->id }}" class="btn btn-warning"><span class="fas fa-edit"></span></a>
                                </td>
                                <td>
                                    <form  class="delete-form" action="{{ route('usuarios.destroy', $usuario->id) }}" method="post">        
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="usuario" value="{{ $usuario->id }}"  >
                                            <input name="deshabilitado" value="1" type="hidden">
                                            <button class="btn btn-danger"><span class="fas fa-trash"></span></button>
                                    </form>
                                </td>
                                <td>@if($usuario->tipo == 2)
                                    <a target="_blank" href="{{ route('usuarios.credencialqr', $usuario->id) }}" class="btn btn-info"><span class="fas fa-qrcode"></span></a>
                                    @endif
                                    @if($usuario->tipo == 3)
                                    <a target="_blank" href="{{ route('usuarios.credencialqr', $usuario->id) }}" class="btn btn-info"><span class="fas fa-qrcode"></span></a>
                                    @endif
                                </td>
                            </tr>



                           
                    <!-- Modal Editar Usuario -->
<div class="modal fade" id="modalEditUsuario{{ $usuario->id }}" tabindex="-1" aria-labelledby="modalEditUsuarioLabel{{ $usuario->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">        
        <h5 class="modal-title" id="modalEditUsuarioLabel{{ $usuario->id }}">Editar Usuario {{ $usuario->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('usuarios.update', $usuario->id) }}" method="post" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" name="id_usuario" value="{{ $usuario->id }}">
            <div class="row">
                 
     
            <div class="col-sm-6">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $usuario->name }}" required>
            </div>
            <div class="col-sm-6">
                <label for="email" class="form-label">Correo</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $usuario->email }}" required>
            </div>

            <div class="col-sm-6">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Dejar en blanco para mantener la contraseña actual">
            </div>
            <div class="col-sm-6">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <option value="">Seleccionar Tipo</option>
                    <option value="2" {{ $usuario->tipo == 2 ? 'selected' : '' }}>Registros</option>
                    <option value="3" {{ $usuario->tipo == 3 ? 'selected' : '' }}>Administrativo Docentes</option>
                    <option value="1" {{ $usuario->tipo == 1 ? 'selected' : '' }}>Administrador</option>
                    
                </select>
            </div>
            
</div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Actualizar Usuario">
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
