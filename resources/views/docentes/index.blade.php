@extends('adminlte::page')

@section('title', 'Docentes')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Docentes</h3>
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
            Nuevo Docente
        </button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="position: relative;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Docente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('docentes.store') }}" method="POST" enctype="multipart/form-data">
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
                <label for="paterno" class="form-label">Apellido Paterno</label>
                <input type="text" class="form-control" id="paterno" name="paterno" required>
            </div>
            <div class="form-group col-sm-6">
                <label for="materno" class="form-label">Apellido Materno</label>
                <input type="text" class="form-control" id="materno" name="materno" >
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
            
            <div class="form-group col-sm-6">
                <label for="rfc" class="form-label">RFC</label>
                <input type="text" class="form-control" id="rfc" name="rfc" >
            </div>

            
           
            
            
            <div class="form-group col-md-6">
                <label for="curp" class="form-label">CURP</label>
                <input type="text" class="form-control" id="curp" name="curp" >
            </div>
            <div class="form-group col-sm-6">
                <label for="clabe" class="form-label">CLABE</label>
                <input type="text" class="form-control" id="clabe" name="clabe" >
            </div>
            <div class="form-group col-sm-6">
                <label for="banco" class="form-label">BANCO</label>
                <input type="text" class="form-control" id="banco" name="banco" >
            </div>

            

</div>
            

 <div class="col-sm-12 row">

        <div class="col-sm-12">
            <label for="direccion_fiscal" class="form-label">Dirección Fiscal Completo</label>
            <input type="text" class="form-control" id="direccion_fiscal" name="direccion_fiscal" >
        </div>

<div class="form-group col-sm-4">
                <label for="regimen"  class="form-label">Genero</label>
                <select class="form-select" id="genero" name="genero">
                    <option value="">Seleccionar</option>
                    <option value="HOMBRE">HOMBRE</option>
                    <option value="MUJER">MUJER</option>
                </select>
            </div>
        
        <div class="col-sm-4">
                <label for="folio_ine" class="form-label">Folio Reverso INE</label>
                <input type="text" class="form-control" id="folio_ine" name="folio_ine" >
        </div>

        <div class="col-sm-4">
                <label for="taller" class="form-label">Taller</label>
                <select class="form-control" id="taller" name="taller">
                    @foreach ($talleres as $taller)
                        <option value="{{ $taller->id }}">{{ $taller->nombre }}</option>
                    @endforeach
                </select>
        </div>

        <div class="col-sm-4">
                <label for="inicio_c" class="form-label">Inicio Contrato</label>
                <input type="date" class="form-control" id="inicio_c" name="inicio_c" >
        </div>

        <div class="col-sm-4">
                <label for="termino_c" class="form-label">Término Contrato</label>
                <input type="date" class="form-control" id="termino_c" name="termino_c" >
        </div>

        <div class="col-sm-4">
                <label for="horas_t" class="form-label">Horas Impartidas a la semana</label>
                <input type="text" class="form-control" id="horas_t" name="horas_t">
        </div>

        <div class="col-sm-12">
            <label for="dias_asueto" class="form-label">Días de Asueto</label>
            <input type="text" class="form-control" id="dias_asueto" name="dias_asueto" >
        </div>
        



</div>

            



        </div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Guardar Docente ">
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
                            <th>Contrato</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($docentes as $docente)
                            <tr>
                                <td><div class="card-heart p-0" style="height:150px; width: 120px;">
                                    @if($docente->foto!='docentes/default.png')
                                    <img src="{{ Storage::url($docente->foto) }}"  style="object-fit: cover; width:100%; height:100%;">
                                    @else
                                    <img src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg"  style="object-fit: cover; width:100%; height:100%;">
                                    @endif
                                </div></td>

                                <td>{{ $docente->folio }}</td>
                                <td>{{ $docente->nombre }} {{ $docente->paterno }} {{ $docente->materno }}</td>
                                <td>{{ $docente->correo }}</td>
                                <td>{{ $docente->telefono }}</td>
                                <td>{{ $docente->rfc }}</td>
                                <td>{{ $docente->regimen }}</td>
                                <td>{{ $docente->curp }}</td>
                                <td>
                                    <a target="_blank" href="{{ route('docentes.descargarContratoWord', $docente->id) }}" class="btn btn-success"><span class="fas fa-file-word"></span></a>
                                </td>
                                <td>
                                    <a  data-bs-toggle="modal" data-bs-target="#modalExpedienteDocente{{ $docente->id }}" class="btn btn-primary"><span class="fas fa-upload"></span></a>
                                </td>

                                <td>
                                    <a  data-bs-toggle="modal" data-bs-target="#modalEditDocente{{ $docente->id }}" class="btn btn-warning"><span class="fas fa-edit"></span></a>
                                </td>
                                <td>
                                    <form  class="delete-form" action="{{ route('docentes.destroy', $docente->id) }}" method="post">        
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="docente" value="{{ $docente->id }}"  >
                                            <input name="deshabilitado" value="1" type="hidden">
                                            <button class="btn btn-danger"><span class="fas fa-trash"></span></button>
                                    </form>
                                </td>
                                <td><a target="_blank" href="{{ route('docentes.credencialqr', $docente->id) }}" class="btn btn-info"><span class="fas fa-qrcode"></span></a></td>
                                <td>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalReporteDocente{{ $docente->id }}">
                                        <span class="fas fa-file-pdf"></span> Reporte
                                    </button>
                                </td>
                                <td>

                                <a  data-bs-toggle="modal" data-bs-target="#modalHistorialReportesDocente{{ $docente->id }}" class="btn btn-success"><i class="fa-solid fa-clock-rotate-left"></i></a>

                                </td>
                                <td>
                                    <a href="{{ route('listadoentregas.index', ['docente_id' => $docente->id]) }}" class="btn btn-info" title="Ver entregas de material">
                                        <span class="fas fa-boxes"></span>
                                    </a>
                                </td>
                            </tr>


                            <!-- Modal Historial Reportes Docente -->

                            <div class="modal fade" id="modalHistorialReportesDocente{{ $docente->id }}" tabindex="-1" aria-labelledby="modalHistorialReportesDocenteLabel{{ $docente->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="modalHistorialReportesDocenteLabel{{ $docente->id }}">Historial de Reportes de {{ $docente->nombre }} {{ $docente->paterno }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    
                                    <p class="text-muted">Aquí se mostrará el historial de reportes generados para este docente.</p>
                                    <!-- Aquí puedes agregar el contenido dinámico del historial de reportes -->
                                    @php
                                        $historialReportes = \App\Models\Reportes_Observaciones::where('docente', $docente->id)->get();
                                    @endphp
                                    @if($historialReportes->isEmpty())
                                        <p class="text-muted">No hay reportes disponibles para este docente.</p>
                                    @else
                                        <ul>
                                            @foreach($historialReportes as $reporte)
                                                <li class="mb-2">
                                                    <a href="{{ route('docentes.reporte', [
                                                        'docente' => $docente->id,
                                                        'fecha_inicio' => $reporte->fecha_ini,
                                                        'fecha_fin' => $reporte->fecha_fin,
                                                        'reporte' => $reporte->id,
                                                    ]) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <span class="fas fa-file-pdf"></span>
                                                        Reporte del {{ $reporte->created_at->format('d/m/Y') }}
                                                        ({{ $reporte->fecha_ini }} a {{ $reporte->fecha_fin }})
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Modal Reporte Docente -->
                            <div class="modal fade" id="modalReporteDocente{{ $docente->id }}" tabindex="-1" aria-labelledby="modalReporteDocenteLabel{{ $docente->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="modalReporteDocenteLabel{{ $docente->id }}">Reporte de {{ $docente->nombre }} {{ $docente->paterno }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <form action="{{ route('docentes.reporte.observaciones.form', ['docente' => $docente->id]) }}" method="GET">
                                    <div class="modal-body">
                                        <p class="text-muted">Selecciona el rango de fechas para consultar las asistencias registradas por los alumnos en los talleres de este docente.</p>
                                        <div class="mb-3">
                                            <label for="fecha_inicio_{{ $docente->id }}" class="form-label">Fecha Inicio</label>
                                            <input type="date" class="form-control" id="fecha_inicio_{{ $docente->id }}" name="fecha_inicio" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="fecha_fin_{{ $docente->id }}" class="form-label">Fecha Fin</label>
                                            <input type="date" class="form-control" id="fecha_fin_{{ $docente->id }}" name="fecha_fin" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-secondary"><span class="fas fa-arrow-right"></span> Continuar</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>

                            
                    <!-- Modal Editar Centro -->
<div class="modal fade" id="modalEditDocente{{ $docente->id }}" tabindex="-1" aria-labelledby="modalEditDocenteLabel{{ $docente->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">        
        <h5 class="modal-title" id="modalEditDocenteLabel{{ $docente->id }}">Editar Docente {{ $docente->nombre }} {{ $docente->paterno }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('docentes.update', $docente->id) }}" method="post" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" name="id_docente" value="{{ $docente->id }}">
            <div class="row">
                 
            
            <div class="col-sm-6 relative">
            <label for="nombre" class="form-label">Foto</label>
            <label class="bg-white px-4 py-2 rounded-lg cursor-pointer" style="cursor: pointer;">

@if($docente->foto!='docentes/default.png')
                                     <img id="imgPreview" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" src="{{ Storage::url($docente->foto) }}" alt="">
                                    @else

    <img id="imgPreview" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg" alt="">
                                    @endif




        <input type="file" class="hidden" style="display: none;" name="image" accept="image/*" onchange="preview_image(event, '#imgPreview')">
    </label>

</div>

 <div class="col-sm-6 row">
            
            <div class="col-sm-6">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $docente->nombre }}" required>
                </div>
                <div class="col-sm-6">
                <label for="paterno" class="form-label">Apellido Paterno</label>
                <input type="text" class="form-control" id="paterno" name="paterno" value="{{ $docente->paterno }}" required>
            </div>
            <div class="col-sm-6">
                <label for="materno" class="form-label">Apellido Materno</label>
                <input type="text" class="form-control" id="materno" name="materno" value="{{ $docente->materno }}" >
            </div>
            <div class="col-sm-6">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" value="{{ $docente->correo }}" >
            </div>
            <div class="col-sm-6">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $docente->telefono }}" >
            </div>
            <div class="col-sm-6">
                <label for="rfc" class="form-label">RFC</label>
                <input type="text" class="form-control" id="rfc" name="rfc" value="{{ $docente->rfc }}">
            </div>
            <div class="form-group col-sm-6">
                <label for="regimen"  class="form-label">Régimen</label>
                <select class="form-select" id="regimen" name="regimen">
                    <option value="">Seleccionar</option>
                    <option value="RESICO" {{ $docente->regimen == 'RESICO' ? 'selected' : '' }}>RESICO</option>
                    <option value="RAEP" {{ $docente->regimen == 'RAEP' ? 'selected' : '' }}>RAEP</option>
                </select>
            </div>
            <div class="col-sm-6">
                <label for="curp" class="form-label">CURP</label>
                <input type="text" class="form-control" id="curp" name="curp" value="{{ $docente->curp }}">
            </div>
            <div class="col-sm-6">
                <label for="clabe" class="form-label">CLABE</label>
                <input type="text" class="form-control" id="clabe" name="clabe" value="{{ $docente->clabe }}">
            </div>
            <div class="col-sm-6">
                <label for="banco" class="form-label">Banco</label>
                <input type="text" class="form-control" id="banco" name="banco" value="{{ $docente->banco }}">
            </div>
            
            


</div>


 <div class="col-sm-12 row">

        <div class="col-sm-12">
            <label for="direccion_fiscal" class="form-label">Dirección Fiscal Completo</label>
            <input type="text" class="form-control" id="direccion_fiscal" name="direccion_fiscal" value="{{ $docente->direccion_fiscal }}">
        </div>

<div class="form-group col-sm-4">
                <label for="genero"  class="form-label">Genero</label>
                <select class="form-select" id="genero" name="genero">
                    <option value="">Seleccionar</option>
                    <option value="HOMBRE" {{ $docente->genero == 'HOMBRE' ? 'selected' : '' }}>HOMBRE</option>
                    <option value="MUJER" {{ $docente->genero == 'MUJER' ? 'selected' : '' }}>MUJER</option>
                </select>
            </div>
        
        <div class="col-sm-4">
                <label for="folio_ine" class="form-label">Folio Reverso INE</label>
                <input type="text" class="form-control" id="folio_ine" name="folio_ine" value="{{ $docente->folio_ine }}">
        </div>

        <div class="col-sm-4">
                <label for="taller" class="form-label">Taller</label>
                <select class="form-control" id="taller" name="taller">
                    @foreach ($talleres as $taller)
                        <option value="{{ $taller->id }}" {{ $docente->taller == $taller->id ? 'selected' : '' }}>{{ $taller->nombre }}</option>
                    @endforeach
                </select>
        </div>

        <div class="col-sm-4">
                <label for="inicio_c" class="form-label">Inicio Contrato</label>
                <input type="date" class="form-control" id="inicio_c" name="inicio_c" value="{{ $docente->inicio_c }}">
        </div>

        <div class="col-sm-4">
                <label for="termino_c" class="form-label">Término Contrato</label>
                <input type="date" class="form-control" id="termino_c" name="termino_c" value="{{ $docente->termino_c }}">
        </div>

        <div class="col-sm-4">
                <label for="horas_t" class="form-label">Horas Impartidas a la semana</label>
                <input type="text" class="form-control" id="horas_t" name="horas_t" value="{{ $docente->horas_t }}">
        </div>

        
        <div class="col-sm-12">
            <label for="dias_asueto" class="form-label">Días de Asueto</label>
            <input type="text" class="form-control" id="dias_asueto" name="dias_asueto" value="{{ $docente->dias_asueto }}">
        </div>



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
                    

<!-- Modal Expediente Docente -->
<div class="modal fade" id="modalExpedienteDocente{{ $docente->id }}" tabindex="-1" aria-labelledby="modalExpedienteDocenteLabel{{ $docente->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalExpedienteDocenteLabel{{ $docente->id }}">Expediente de {{ $docente->nombre }} {{ $docente->paterno }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>        
        <div class="modal-body">    


            
            <form action="{{ route('docentes.guardarExpediente') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="docente" value="{{ $docente->id }}">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Tipo Documento</label>
                    <select class="form-select" id="titulo" name="titulo" required>
                        <option value="">Selecciona un tipo de documento</option>
                        <option value="CURP">CURP</option>
                        <option value="RFC">RFC</option>
                        <option value="Constancia de Situación Fiscal">Constancia de Situación Fiscal</option>
                        <option value="Comprobante de Domicilio">Comprobante de Domicilio</option>
                        <option value="INE">INE</option>
                        <option value="Acta de Nacimiento">Acta de Nacimiento</option>
                        <option value="Título Profesional">Título Profesional</option>
                        <option value="Cédula Profesional">Cédula Profesional</option>
                    </select> 
                </div>
                <div class="mb-3">
                    <label for="archivo" class="form-label">Archivo</label>
                    <input type="file" class="form-control" id="archivo" name="archivo" required>
                </div>
                <div class="mb-3">
                <button type="submit" class="btn btn-primary btn-lg btn-block">Subir Documento</button>
                </div>
            </form>

            <hr>

            <h5>Documentos Subidos</h5>
            <ul class="list-group">
                @php
                    $expedientes = \App\Models\expedientes_docente::where('docente', $docente->id)->where('deshabilitado', 0)->get();
                @endphp
                @foreach ($expedientes as $expediente)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $expediente->titulo }}
                        <a href="{{ Storage::url($expediente->archivo) }}" target="_blank" class="btn btn-sm btn-info">Ver</a>
                        <form  class="delete-form" action="{{ route('docentes.destroyExpediente', $expediente->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="docente" value="{{ $docente->id }}"  >
                                            <input name="deshabilitado" value="1" type="hidden">
                                            <button class="btn btn-danger"><span class="fas fa-trash"></span></button>
                                    </form>
                    </li>
                @endforeach
            </ul>   




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
