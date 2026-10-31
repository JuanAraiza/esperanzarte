@extends('adminlte::page')

@section('title', 'Alumnos')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Alumnos</h3>
        </div>
        <div class="col-sm-6" style="display: flex; justify-content: flex-end; align-items: center;">
            
        
        <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
 Nuevo Alumno
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="position: relative;">
      <div id="loadingOverlay" class="position-absolute top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" style="background: rgba(255,255,255,0.95); z-index: 1055; border-radius: inherit;">
        <div class="text-center px-4">
          <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
          <h5 class="mb-2">Registrando alumno</h5>
          <p id="loadingStatus" class="mb-3 text-muted">Preparando datos...</p>
          <div class="progress mx-auto" style="height: 8px; width: 260px;">
            <div id="loadingProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 15%"></div>
          </div>
          <small class="text-muted d-block mt-2">Esto puede tardar unos segundos.</small>
        </div>
      </div>
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Alumno</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formAlumno" action="{{ route('alumnos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
      
      <div class="modal-body">
        <div class="row">
            
            <div class="col-sm-4">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="col-sm-4">
                <label for="paterno" class="form-label">A. Paterno</label>
                <input type="text" class="form-control" id="paterno" name="paterno" required>
            </div>
            <div class="col-sm-4">
                <label for="materno" class="form-label">A. Materno</label>
                <input type="text" class="form-control" id="materno" name="materno" required>
            </div>
            <div class="col-sm-4">
                <label for="curp" class="form-label">CURP</label>
                <input type="text" class="form-control" id="curp" name="curp" required>
            </div>
            <div class="col-sm-4">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>
            <div class="col-sm-4">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-select" id="sexo" name="sexo" required>
                    <option value="">Seleccionar</option>
                    <option value="HOMBRE">HOMBRE</option>
                    <option value="MUJER">MUJER</option>
                </select>
            </div>
            <div class="col-sm-4">
                <label for="domicilio" class="form-label">Calle</label>
                <input type="text" class="form-control" id="domicilio" name="domicilio" required>
            </div>
            <div class="col-sm-4">
                <label for="num_ext" class="form-label">Número Exterior</label>
                <input type="text" class="form-control" id="num_ext" name="num_ext" required>
            </div>
            <div class="col-sm-4">
                <label for="ur" class="form-label">Ubicacion</label>
                <select class="form-select" id="ur" name="ur" required>
                    <option value="">Seleccionar</option>
                    <option value="COLONIA">COLONIA</option>
                    <option value="LOCALIDAD">LOCALIDAD</option>
                </select>
            </div>
            <div class="col-sm-4">
                <label for="colonia" class="form-label">Colonia</label>
                <select class="form-select" id="colonia" name="colonia" disabled>
                    <option value="">Seleccionar</option>
                    @foreach ($colonias as $colonia)
                        <option value="{{ $colonia->id }}">{{ $colonia->nombre }}</option>
                    @endforeach
                </select>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="usar_colonia_manual" name="usar_colonia_manual" value="1">
                    <label class="form-check-label" for="usar_colonia_manual">Escribir colonia</label>
                </div>
                <input type="text" class="form-control mt-2" id="colonia_manual" name="colonia_manual" placeholder="Escribe la colonia" disabled>
            </div>
            <div class="col-sm-4">
                <label for="localidad" class="form-label">Localidad</label>
                <select class="form-select" id="localidad" name="localidad" disabled>
                    <option value="">Seleccionar</option>
                    @foreach ($localidades as $localidad)
                        <option value="{{ $localidad->id }}">{{ $localidad->nombre }}</option>
                    @endforeach
                </select>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="usar_localidad_manual" name="usar_localidad_manual" value="1">
                    <label class="form-check-label" for="usar_localidad_manual">Escribir localidad</label>
                </div>
                <input type="text" class="form-control mt-2" id="localidad_manual" name="localidad_manual" placeholder="Escribe la localidad" disabled>
            </div>

<div class="col-sm-4">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Seleccionar</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado }}">{{ $estado }}</option>
                    @endforeach
                </select>
                <label for="municipio" class="form-label">Municipio</label>
                <select class="form-select" id="municipio" name="municipio" disabled>
                    <option value="">Seleccionar</option>
                </select>
            </div>


<div class="col-sm-4">
                <label for="cod_postal" class="form-label">Cod. Postal</label>
                <input type="text" class="form-control" id="cod_postal" name="cod_postal" required>
            </div>

            <div class="col-sm-4">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>

            <div class="col-sm-4">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>

             <div class="col-sm-4 relative">
                <label for="image1" class="form-label">Foto Cara</label>
                <label class="bg-white px-4 py-2 rounded-lg cursor-pointer" style="cursor: pointer;">
                    <img id="imgPreview1" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg" alt="">
                    <input type="file" class="hidden" style="display: none;" name="image1" accept="image/*" onchange="preview_image(event, '#imgPreview1')">
                </label>
            </div>

            <div class="col-sm-4 relative">
                <label for="image2" class="form-label">INE Frente</label>
                <label class="bg-white px-4 py-2 rounded-lg cursor-pointer" style="cursor: pointer;">
                    <img id="imgPreview2" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg" alt="">
                    <input type="file" class="hidden" style="display: none;" name="image2" accept="image/*" onchange="preview_image(event, '#imgPreview2')">
                </label>
            </div>

            <div class="col-sm-4 relative">
                <label for="image3" class="form-label">INE Reverso</label>
                <label class="bg-white px-4 py-2 rounded-lg cursor-pointer" style="cursor: pointer;">
                    <img id="imgPreview3" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg" alt="">
                    <input type="file" class="hidden" style="display: none;" name="image3" accept="image/*" onchange="preview_image(event, '#imgPreview3')">
                </label>
            </div>


        </div>
      </div>
      <div class="modal-footer">
        <input id="btnGuardarAlumno" type="submit" class="btn btn-primary" value="Guardar Alumno">
      </div>
      </form>
    </div>
  </div>
</div>


        </div>
    </div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const ubicacion = document.getElementById('ur');
    const coloniaSelect = document.getElementById('colonia');
    const localidadSelect = document.getElementById('localidad');
    const coloniaManual = document.getElementById('colonia_manual');
    const localidadManual = document.getElementById('localidad_manual');
    const usarColoniaManual = document.getElementById('usar_colonia_manual');
    const usarLocalidadManual = document.getElementById('usar_localidad_manual');
    const estadoSelect = document.getElementById('estado');
    const municipioSelect = document.getElementById('municipio');
    const municipiosPorEstado = @json($municipios);
    const formAlumno = document.getElementById('formAlumno');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const loadingStatus = document.getElementById('loadingStatus');
    const loadingProgressBar = document.getElementById('loadingProgressBar');
    const btnGuardarAlumno = document.getElementById('btnGuardarAlumno');

    function actualizarSelect2(select) {
        if (window.jQuery && window.jQuery.fn.select2) {
            window.jQuery(select).trigger('change.select2');
        }
    }

    function actualizarCampos() {
        const valor = ubicacion.value;

        const coloniaActivo = valor === 'COLONIA';
        const localidadActivo = valor === 'LOCALIDAD';

        coloniaSelect.disabled = !coloniaActivo;
        localidadSelect.disabled = !localidadActivo;

        const coloniaManualActivo = coloniaActivo && usarColoniaManual.checked;
        const localidadManualActivo = localidadActivo && usarLocalidadManual.checked;

        coloniaManual.disabled = !coloniaManualActivo;
        localidadManual.disabled = !localidadManualActivo;

        coloniaSelect.required = coloniaActivo;
        localidadSelect.required = localidadActivo;
        coloniaManual.required = coloniaManualActivo;
        localidadManual.required = localidadManualActivo;

        actualizarSelect2(coloniaSelect);
        actualizarSelect2(localidadSelect);
    }

    function actualizarMunicipios() {
        const estadoSeleccionado = estadoSelect.value;
        municipioSelect.innerHTML = '<option value="">Seleccionar</option>';
        municipioSelect.disabled = !estadoSeleccionado;

        if (estadoSeleccionado && municipiosPorEstado[estadoSeleccionado]) {
            municipiosPorEstado[estadoSeleccionado].forEach(function (municipio) {
                const option = document.createElement('option');
                option.value = municipio;
                option.textContent = municipio;
                municipioSelect.appendChild(option);
            });
        }

        actualizarSelect2(municipioSelect);

    }

    [ubicacion, usarColoniaManual, usarLocalidadManual].forEach(function (element) {
        element.addEventListener('change', actualizarCampos);
    });

    estadoSelect.addEventListener('change', actualizarMunicipios);

    function mostrarLoading() {
        loadingOverlay.classList.remove('d-none');
        loadingOverlay.classList.add('d-flex');
        btnGuardarAlumno.disabled = true;
    }

    function actualizarEstadoCarga(paso, porcentaje) {
        const pasos = [
            'Preparando datos...',
            'Guardando imágenes...',
            'Escaneando INE con OCR...',
            'Guardando alumno...',
            'Finalizando registro...'
        ];

        if (paso < pasos.length) {
            loadingStatus.textContent = pasos[paso];
        }

        loadingProgressBar.style.width = porcentaje + '%';
    }

    if (formAlumno) {
        formAlumno.addEventListener('submit', function () {
            mostrarLoading();
            const tieneOcr = document.querySelector('input[name="image2"]') && document.querySelector('input[name="image2"]').files.length > 0;
            const pasos = tieneOcr ? [0, 1, 2, 3, 4] : [0, 1, 3, 4];
            let indice = 0;

            actualizarEstadoCarga(pasos[0], 15);

            const intervalo = setInterval(function () {
                indice++;
                if (indice < pasos.length) {
                    const paso = pasos[indice];
                    const porcentaje = paso === 0 ? 15 : paso === 1 ? 35 : paso === 2 ? 70 : paso === 3 ? 90 : 100;
                    actualizarEstadoCarga(paso, porcentaje);
                } else {
                    clearInterval(intervalo);
                }
            }, 1200);
        });
    }

    actualizarCampos();
    actualizarMunicipios();
});
</script>

@stop

@section('content')

    <x-adminlte-card theme="primary" theme-mode="outline">

                <table id="tablaAlumnos" class="table table-striped table-bordered " style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Folio</th>
                            <th>Nombre</th>
                            <th>A. Paterno</th>
                            <th>A. Materno</th>
                            <th>CURP</th>
                            <th>Localidad / Colonia</th>
                            
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alumnos as $alumno)
                            <tr>
                                <td>
                                    <a  data-bs-toggle="modal" data-bs-target="#modalImagenes{{ $alumno->id }}" class="btn btn-info"><i class="fa-regular fa-image"></i></a>
                                <!-- Modal Imagenes -->

                                <div class="modal fade" id="modalImagenes{{ $alumno->id }}" tabindex="-1" aria-labelledby="modalImagenesLabel{{ $alumno->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">        
        <h5 class="modal-title" id="modalImagenesLabel{{ $alumno->id }}">Imagenes Alumno {{ $alumno->nombre }} {{ $alumno->paterno }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
         

             <div class="col-sm-12 relative">
                <label for="image1" class="form-label">Foto Cara</label>
                
                <img id="imgPreview1" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" @if($alumno->foto) src="{{ Storage::url($alumno->foto) }}" @else src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg" @endif alt="">
              
            </div>

            <div class="col-sm-12 relative">
                <label for="image2" class="form-label">INE Frente</label>
                
                    <img id="imgPreview2" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" @if($alumno->inef) src="{{ Storage::url($alumno->inef) }}" @else src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg" @endif alt="">
                  
            </div>

            <div class="col-sm-12 relative">
                <label for="image3" class="form-label">INE Reverso</label>
                
                    <img id="imgPreview3" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" @if($alumno->inea) src="{{ Storage::url($alumno->inea) }}" @else src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg" @endif alt="">
                  
                
            </div>

</div>
      </div>
      <div class="modal-footer">
    
      </div>
        </form>
    </div>
  </div>   
</div>
</div>

                                <!-- Fin Modal Imagenes -->
                                
                                </td>
                                <td>{{ $alumno->folio }}</td>
                                <td>{{ $alumno->nombre }}</td>
                                <td>{{ $alumno->paterno }}</td>
                                <td>{{ $alumno->materno }}</td>
                                <td>{{ $alumno->curp }}</td>
                                <td>{{ $alumno->localidad }} / {{ $alumno->colonia }}</td>
                                <td>


<a type="button" class="btn btn-default" data-toggle="modal" data-target="#ModalAsistencias{{ $alumno->id }}"><i class="fa-solid fa-list-check"></i></a>

<!-- Modal -->
<div class="modal fade" id="ModalAsistencias{{ $alumno->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-lg">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Asistencias</h3>
        
      </div>
      <div class="modal-body">
<table class="table table-hover" style="text-transform: uppercase;" >
  <tr>
    <td>Docente</td>
    <td>Centro</td>
    <td>Taller</td>
    <td>Dia</td>
    <td>Hora</td>
    <td>Ubicacion</td>
  </tr>
@php
    $historial = $historialAsistencias[$alumno->id] ?? collect();
@endphp

@forelse ($historial as $rowhist)
    <tr>
        <td>{{ $rowhist->docente_nombre ?: 'N/D' }}</td>
        <td>{{ $rowhist->centro_nombre ?: 'N/D' }}</td>
        <td>{{ $rowhist->taller_nombre ?: 'N/D' }}</td>
        <td>{{ $rowhist->dia }}</td>
        <td>{{ $rowhist->hora }}</td>
        <td>
            @if (!empty($rowhist->latitud) && !empty($rowhist->longitud))
                <a href="https://www.google.com/maps?q={{ $rowhist->latitud }},{{ $rowhist->longitud }}" target="_blank" class="btn btn-info"><i class="fa-solid fa-location-dot"></i></a>
            @else
                N/D
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted">Sin asistencias registradas</td>
    </tr>
@endforelse


</table>      



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
       
      </div>
    </div>
  </div>
</div>



                                </td>
                                <td>
                                <a  data-bs-toggle="modal" data-bs-target="#modalVerAlumno{{ $alumno->id }}" class="btn btn-success"><span class="fas fa-search"></span></a>
                                    <!--  Ver Info -->

                                     <!-- Modal Editar Centro -->
<div class="modal fade" id="modalVerAlumno{{ $alumno->id }}" tabindex="-1" aria-labelledby="modalVerAlumnoLabel{{ $alumno->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">        
        <h5 class="modal-title" id="modalVerAlumnoLabel{{ $alumno->id }}">Ver Alumno {{ $alumno->nombre }} {{ $alumno->paterno }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
          <input type="hidden" name="id" value="{{ $alumno->id }}">
            <div class="row">
                 <div class="col-sm-4">
                <label for="nombre" class="form-label">Nombre</label>
                <p>{{ $alumno->nombre }}</p>
            </div>
            <div class="col-sm-4">
                <label for="paterno" class="form-label">A. Paterno</label>
                <p>{{ $alumno->paterno }}</p>
            </div>
            <div class="col-sm-4">
                <label for="materno" class="form-label">A. Materno</label>
                <p>{{ $alumno->materno }}</p>
            </div>
            <div class="col-sm-4">
                <label for="curp" class="form-label">CURP</label>
                <p>{{ $alumno->curp }}</p>
            </div>
            <div class="col-sm-4">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <p>{{ $alumno->fecha_nacimiento }}</p>
            </div>
            <div class="col-sm-4">
                <label for="sexo" class="form-label">Genero</label>
                <p>{{ $alumno->sexo }}</p>
            </div>
            <div class="col-sm-4">
                <label for="domicilio" class="form-label">Calle</label>
                <p>{{ $alumno->domicilio }}</p>
            </div>
            <div class="col-sm-4">
                <label for="num_ext" class="form-label">Número Exterior</label>
                <p>{{ $alumno->num_ext }}</p>
            </div>
            
            <div class="col-sm-4">
                <label for="colonia" class="form-label">Colonia</label>
                <p>{{ $alumno->colonia }}</p>
            </div>
            <div class="col-sm-4">
                <label for="localidad" class="form-label">Localidad</label>
                <p>{{ $alumno->localidad }}</p>
            </div>

<div class="col-sm-4">
                <label for="estado" class="form-label">Estado</label>
                <p>{{ $alumno->estado }}</p>
                    </div>
                    <div class="col-sm-4">
                <label for="municipio" class="form-label">Municipio</label>
                <p>{{ $alumno->municipio }}</p>
            </div>


<div class="col-sm-4">
                <label for="cod_postal" class="form-label">Cod. Postal</label>
                <p>{{ $alumno->cod_postal }}</p>
            </div>

            <div class="col-sm-4">
                <label for="telefono" class="form-label">Teléfono</label>
                <p>{{ $alumno->telefono }}</p>
            </div>

            <div class="col-sm-4">
                <label for="correo" class="form-label">Correo</label>
                <p>{{ $alumno->correo }}</p>
            </div>

             

            
            

</div>
      </div>
      
      
    </div>
  </div>   
</div>
</div>


                                    <!-- Fin Ver Info -->
                                </td>
                                
                               <td>
                                    <a  data-bs-toggle="modal" data-bs-target="#modalEditAlumno{{ $alumno->id }}" class="btn btn-warning"><span class="fas fa-edit"></span></a>
                                </td>
                                <td>
                                    <form  class="delete-form" action="{{ route('alumnos.destroy', $alumno->id) }}" method="post">        
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="alumno" value="{{ $alumno->id }}"  >
                                            <input name="deshabilitado" value="1" type="hidden">
                                            <button class="btn btn-danger"><span class="fas fa-trash"></span></button>
                                    </form>
                                </td>
                                <td><a target="_blank" href="{{ route('alumno.credencialqr', $alumno->id) }}" class="btn btn-info"><span class="fas fa-qrcode"></span></a></td>
                            </tr>



                    <!-- Modal Editar Centro -->
<div class="modal fade" id="modalEditAlumno{{ $alumno->id }}" tabindex="-1" aria-labelledby="modalEditAlumnoLabel{{ $alumno->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">        
        <h5 class="modal-title" id="modalEditAlumnoLabel{{ $alumno->id }}">Editar Alumno {{ $alumno->nombre }} {{ $alumno->paterno }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('alumnos.update', $alumno->id) }}" method="post">
          @csrf
          @method('PUT')
          <input type="hidden" name="id" value="{{ $alumno->id }}">
            <div class="row">
                 <div class="col-sm-4">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $alumno->nombre }}" required>
            </div>
            <div class="col-sm-4">
                <label for="paterno" class="form-label">A. Paterno</label>
                <input type="text" class="form-control" id="paterno" name="paterno" value="{{ $alumno->paterno }}" required>
            </div>
            <div class="col-sm-4">
                <label for="materno" class="form-label">A. Materno</label>
                <input type="text" class="form-control" id="materno" name="materno" value="{{ $alumno->materno }}" required>
            </div>
            <div class="col-sm-4">
                <label for="curp" class="form-label">CURP</label>
                <input type="text" class="form-control" id="curp" name="curp" value="{{ $alumno->curp }}" required>
            </div>
            <div class="col-sm-4">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ $alumno->fecha_nacimiento }}" required>
            </div>
            <div class="col-sm-4">
                <label for="sexo" class="form-label">Genero</label>
                <select class="form-select" id="sexo" name="sexo" required>
                    <option value="">Seleccionar</option>
                    <option @if($alumno->sexo == 'HOMBRE') selected @endif value="HOMBRE">HOMBRE</option>
                    <option @if($alumno->sexo == 'MUJER') selected @endif value="MUJER">MUJER</option>
                </select>
            </div>
            <div class="col-sm-4">
                <label for="domicilio" class="form-label">Calle</label>
                <input type="text" class="form-control" id="domicilio" name="domicilio" value="{{ $alumno->domicilio }}" required>
            </div>
            <div class="col-sm-4">
                <label for="num_ext" class="form-label">Número Exterior</label>
                <input type="text" class="form-control" id="num_ext" name="num_ext" value="{{ $alumno->num_ext }}" required>
            </div>
            <div class="col-sm-4">
                <label for="ur" class="form-label">Ubicacion</label>
                <select class="form-select" id="ur" name="ur" required>
                    <option value="">Seleccionar</option>
                    <option @if($alumno->ur == 'COLONIA') selected @endif value="COLONIA">COLONIA</option>
                    <option @if($alumno->ur == 'LOCALIDAD') selected @endif value="LOCALIDAD">LOCALIDAD</option>
                </select>
            </div>
            <div class="col-sm-4">
                <label for="colonia" class="form-label">Colonia</label>
                <select class="form-select" id="colonia" name="colonia" disabled>
                    <option value="">Seleccionar</option>
                    @foreach ($colonias as $colonia)
                        <option @if($alumno->colonia == $colonia->nombre) selected @endif value="{{ $colonia->nombre }}">{{ $colonia->nombre }}</option>
                    @endforeach
                </select>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="usar_colonia_manual" name="usar_colonia_manual" value="1">
                    <label class="form-check-label" for="usar_colonia_manual">Escribir colonia</label>
                </div>
                <input type="text" class="form-control mt-2" id="colonia_manual" name="colonia_manual" placeholder="Escribe la colonia" disabled>
            </div>
            <div class="col-sm-4">
                <label for="localidad" class="form-label">Localidad</label>
                <select class="form-select" id="localidad" name="localidad" disabled>
                    <option value="">Seleccionar</option>
                    @foreach ($localidades as $localidad)
                        <option @if($alumno->localidad == $localidad->nombre) selected @endif value="{{ $localidad->nombre }}">{{ $localidad->nombre }}</option>
                    @endforeach
                </select>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="usar_localidad_manual" name="usar_localidad_manual" value="1">
                    <label class="form-check-label" for="usar_localidad_manual">Escribir localidad</label>
                </div>
                <input type="text" class="form-control mt-2" id="localidad_manual" name="localidad_manual" placeholder="Escribe la localidad" disabled>
            </div>

<div class="col-sm-4">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Seleccionar</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado }}" {{ $alumno->estado == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
                <label for="municipio" class="form-label">Municipio</label>
                <select class="form-select" id="municipio" name="municipio" disabled>
                    <option @if($alumno->municipio == '') selected @endif value="">Seleccionar</option>
                </select>
            </div>


<div class="col-sm-4">
                <label for="cod_postal" class="form-label">Cod. Postal</label>
                <input type="text" class="form-control" id="cod_postal" name="cod_postal" value="{{ $alumno->cod_postal }}" required>
            </div>

            <div class="col-sm-4">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $alumno->telefono }}" required>
            </div>

            <div class="col-sm-4">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" value="{{ $alumno->correo }}" required>
            </div>

             <div class="col-sm-4 relative">
                <label for="image1" class="form-label">Foto Cara</label>
                <label class="bg-white px-4 py-2 rounded-lg cursor-pointer" style="cursor: pointer;">
                    <img id="imgPreview1" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" @if($alumno->foto) src="{{ Storage::url($alumno->foto) }}" @else src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg" @endif alt="">
                    <input type="file" class="hidden" style="display: none;" name="image1" accept="image/*" onchange="preview_image(event, '#imgPreview1')">
                </label>
            </div>

            <div class="col-sm-4 relative">
                <label for="image2" class="form-label">INE Frente</label>
                <label class="bg-white px-4 py-2 rounded-lg cursor-pointer" style="cursor: pointer;">
                    <img id="imgPreview2" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" @if($alumno->inef) src="{{ Storage::url($alumno->inef) }}" @else src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg" @endif alt="">
                    <input type="file" class="hidden" style="display: none;" name="image2" accept="image/*" onchange="preview_image(event, '#imgPreview2')">
                </label>
            </div>

            <div class="col-sm-4 relative">
                <label for="image3" class="form-label">INE Reverso</label>
                <label class="bg-white px-4 py-2 rounded-lg cursor-pointer" style="cursor: pointer;">
                    <img id="imgPreview3" style="object-fit: cover;width: 100%; height:100%;" class="img-fluid object-fit-cover border rounded" @if($alumno->inea) src="{{ Storage::url($alumno->inea) }}" @else src="https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_w.jpeg" @endif alt="">
                    <input type="file" class="hidden" style="display: none;" name="image3" accept="image/*" onchange="preview_image(event, '#imgPreview3')">
                </label>
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


var table = $('#tablaAlumnos').DataTable({
        /*order: [
            [0, 'desc'],
        
        ],*/
         "ordering": false,
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
