@extends('adminlte::page')

@section('title', 'Materiales')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Materiales</h3>
        </div>
        <div class="col-sm-6" style="display: flex; justify-content: flex-end; align-items: center;">
            
        <?php /* 
        <form action="{{ route('centros.importar') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-primary">Importar centros cargados</button>
        </form>

        */ ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Nuevo Material
        </button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Material</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('materiales.store') }}" method="POST">
        @csrf
        
      
      <div class="modal-body">
        <div class="row">

            <div class="col-sm-4">
                <label for="centro" class="form-label">Material</label>
                <input type="text" class="form-control" id="material" name="material" required>
            </div>   

            <div class="col-sm-4">
                <label for="detalles" class="form-label">Detalles</label>
                <input type="text" class="form-control" id="detalles" name="detalles">
            </div>
            <div class="col-sm-4">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" class="form-control" id="marca" name="marca">
            </div>
            <div class="col-sm-4">
                <label for="costo" class="form-label">Costo</label>
                <input type="number" step="0.01" class="form-control" id="costo" name="costo">
            </div>
            <div class="col-sm-4">
                <label for="unidad_medida" class="form-label">Unidad de Medida</label>
                <input type="text" class="form-control" id="unidad_medida" name="unidad_medida">
            </div>
            <div class="col-sm-4">
                <label for="detalles_producto" class="form-label">Detalles del Producto</label>
                <input type="text" class="form-control" id="detalles_producto" name="detalles_producto">
            </div>
            <div class="col-sm-4">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock">
            </div>
           
            
        </div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Guardar Material">
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
                            <th>Material</th>
                            <th>Detalles</th>
                            <th>Marca</th>
                            <th>Costo</th>
                            <th>Unidad de Medida</th>
                            <th>Detalles del Producto</th>
                            <th>Stock</th>
                            <th>Agregar</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materiales as $material)
                            <tr>
                                <td>{{ $material->material }}</td>
                                <td>{{ $material->detalles }}</td>
                                <td>{{ $material->marca }}</td>
                                <td>{{ $material->costo }}</td>
                                <td>{{ $material->unidad_medida }}</td>
                                <td>{{ $material->detalles_producto }}</td>
                                <td>{{ $material->stock }}</td>
                                <td class="text-center">
                                    <a  data-bs-toggle="modal" data-bs-target="#modalActualizarMaterial{{ $material->id }}" class="btn btn-info"><span class="fas fa-plus"></span></a>
                                </td>
                                <td class="text-center">
                                    <a  data-bs-toggle="modal" data-bs-target="#modalEditMaterial{{ $material->id }}" class="btn btn-warning"><span class="fas fa-edit"></span></a>
                                </td>
                                <td class="text-center">
                                    <form  class="delete-form" action="{{ route('materiales.destroy', $material->id) }}" method="post">
                                                        
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="material" value="{{ $material->id }}"  >
                                                        <input name="deshabilitado" value="1" type="hidden">
                                                    <button class="btn btn-danger">  <span class="fas fa-trash"></span></button>
                                        </form>
                                </td>
                            </tr>


                   <!-- Modal Actualizar Material -->


                   <div class="modal fade" id="modalActualizarMaterial{{ $material->id }}" tabindex="-1" aria-labelledby="modalActualizarMaterialLabel{{ $material->id }}" aria-hidden="true">
                     <div class="modal-dialog modal-lg">
                       <div class="modal-content">
                         <div class="modal-header">
                           <h5 class="modal-title" id="modalActualizarMaterialLabel{{ $material->id }}">Actualizar Material {{ $material->material }}</h5>
                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                         </div>
                         <div class="modal-body">
                           <form action="{{ route('materiales.actualizarStock') }}" method="post">
                             @csrf
                             <input type="hidden" name="material" value="{{ $material->id }}">
                             <div class="row">
                               <div class="col-sm-4">
                                 <label for="stockviejo" class="form-label">Stock Actual</label>
                                 <input type="text" class="form-control" id="stockviejo" name="stockviejo" readonly value="{{ $material->stock }}" readonly>
                               </div>
                               <div class="col-sm-4">
                                 <label for="agregar" class="form-label">Agregar al Stock</label>
                                 <input type="number" class="form-control" id="agregar" name="agregar" required>
                               </div>
                               
                               <div class="col-sm-12">
                                 <h4>Historial</h4>
                                 <div class="row col-sm-12">
                                       <div class="col-sm-3"><strong>Stock Anterior</strong></div>
                                       <div class="col-sm-2"><strong>Agregar</strong></div>
                                       <div class="col-sm-2"><strong>Stock Nuevo</strong></div>
                                       <div class="col-sm-3"><strong>Fecha</strong></div>
</div>
                                @php
                                    $historiales = \DB::table('actualizar_materials')->where('material', $material->id)->take(10)->orderBy('created_at', 'desc')->get();
                                @endphp
                                     @foreach($historiales as $historial)
                                     <div class="row col-sm-12">
                                       <div class="col-sm-3">{{ $historial->stockviejo }}</div>
                                       <div class="col-sm-2">{{ $historial->agregar }}</div>
                                       <div class="col-sm-2">{{ $historial->stocknuevo }}</div>
                                       <div class="col-sm-3">{{ $historial->created_at }}</div>
                                     </div>
                                     @endforeach
                                 
                               </div>
                              
                             </div>
                             <div class="modal-footer">
                               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                               <button type="submit" class="btn btn-primary">Actualizar</button>
                             </div>
                           </form>
                         </div>
                       </div>
                     </div>
                   </div>   

                   <!-- Fin Modal Actualizar Material -->


                    <!-- Modal Editar Material -->
<div class="modal fade" id="modalEditMaterial{{ $material->id }}" tabindex="-1" aria-labelledby="modalEditMaterialLabel{{ $material->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">        
        <h5 class="modal-title" id="modalEditMaterialLabel{{ $material->id }}">Editar Material {{ $material->material }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('materiales.update', $material->id) }}" method="post">
          @csrf
          @method('PUT')
          <input type="hidden" name="id_material" value="{{ $material->id }}">
            <div class="row">
                <div class="col-sm-4">
                    <label for="material" class="form-label">Material</label>
                    <input type="text" class="form-control" id="material" name="material" value="{{ $material->material }}" required>
                </div>
                <div class="col-sm-4">
                    <label for="detalles" class="form-label">Detalles</label>
                    <input type="text" class="form-control" id="detalles" name="detalles" value="{{ $material->detalles }}">
                </div>
                <div class="col-sm-4">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" class="form-control" id="marca" name="marca" value="{{ $material->marca }}">
                </div>
                <div class="col-sm-4">
                    <label for="costo" class="form-label">Costo</label>
                    <input type="number" step="0.01" class="form-control" id="costo" name="costo" value="{{ $material->costo }}">
                </div>
                <div class="col-sm-4">
                    <label for="unidad_medida" class="form-label">Unidad de Medida</label>
                    <input type="text" class="form-control" id="unidad_medida" name="unidad_medida" value="{{ $material->unidad_medida }}">
                </div>
                <div class="col-sm-4">
                    <label for="detalles_producto" class="form-label">Detalles del Producto</label>
                    <input type="text" class="form-control" id="detalles_producto" name="detalles_producto" value="{{ $material->detalles_producto }}">
                </div>
                <div class="col-sm-4">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock" readonly value="{{ $material->stock }}">
                </div>
                
</div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Actualizar Material">
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
