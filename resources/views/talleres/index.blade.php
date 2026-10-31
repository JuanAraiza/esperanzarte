@extends('adminlte::page')

@section('title', 'Talleres')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Talleres</h3>
        </div>
        <div class="col-sm-6" style="display: flex; justify-content: flex-end; align-items: center;">
            
        
        <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
 Nuevo Taller
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Taller</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('talleres.store') }}" method="POST">
        @csrf
        
      
      <div class="modal-body">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Taller</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Guardar Taller">
      </div>
      </form>
    </div>
  </div>
</div>


        </div>
    </div>



@stop

@section('content')
   <div class="row">
    <div class="col-lg-12">
        <div class="card">
            
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($talleres as $taller)
                            <tr>
                                <td>{{ $taller->nombre }}</td>
                                <td>
                                    <!-- Aquí puedes agregar botones para editar o eliminar el taller -->
                                        <form  class="delete-form" action="{{ route('talleres.destroy', $taller->id) }}" method="post">
                                                        
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="taller" value="{{ $taller->id }}"  >
                                                        <input name="deshabilitado" value="1" type="hidden">
                                                    <button class="btn btn-danger">  <span class="fas fa-trash"></span></button>
                                        </form>     
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
   </div>
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
@endpush
