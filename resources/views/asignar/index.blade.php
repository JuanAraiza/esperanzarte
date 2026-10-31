@extends('adminlte::page')

@section('title', 'Asignar Talleres')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Asignar Talleres</h3>
        </div>
        <div class="col-sm-6" style="display: flex; justify-content: flex-end; align-items: center;">
            
        
        <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
 Asignar Taller
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Asignar Taller</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('asignar-taller.store') }}" method="POST">
        @csrf
        
      
      <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <label for="docente_id" class="form-label">Docente</label>
                <select class="form-select" name="docente_id" id="docente_id" required>
                    <option value="">Seleccionar</option>
                    @foreach ($docentes as $docente)
                        <option value="{{ $docente->id }}">{{ $docente->nombre }} {{ $docente->paterno }} {{ $docente->materno }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <label for="taller_id" class="form-label">Taller</label>
                <select class="form-select" name="taller_id" id="taller_id" required>
                    <option value="">Seleccionar</option>
                    @foreach ($talleres as $taller)
                        <option value="{{ $taller->id }}">{{ $taller->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <label for="centro_id" class="form-label">Centro</label>
                <select class="form-select" name="centro_id" id="centro_id" required>
                    <option value="">Seleccionar</option>
                    @foreach ($centros as $centro)
                        <option value="{{ $centro->id }}">{{ $centro->centro }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-6">
                <label for="costo" class="form-label">Costo Hora</label>
                <input type="number" class="form-control" name="costo" id="costo" step="0.01" required>
            </div>

            
            <div class="col-sm-12">
                &nbsp;<br>
                <p><strong>Dias y horarios por semana</strong></p>
            </div>
        <div class="row col-sm-12">
            <div class="col-sm-4">
                <label for="dia1" class="form-label">Dia</label>
                <select class="form-select" name="dia1" id="dia1" required>
                    <option value="">Seleccionar</option>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>   
            </div>
            <div class="col-sm-4">
                <label for="hora_inicio1" class="form-label">Hora Incio</label>
                <input type="time" class="form-control" name="hora_inicio1" id="hora_inicio1" required>
            </div>
            <div class="col-sm-4">
                <label for="hora_termino1" class="form-label">Hora Termino</label>
                <input type="time" class="form-control" name="hora_termino1" id="hora_termino1" >
            </div>

        </div>

        <div class="row col-sm-12">
            <div class="col-sm-4">
                <label for="dia2" class="form-label">Dia</label>
                <select class="form-select" name="dia2" id="dia2" >
                    <option value="">Seleccionar</option>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>   
            </div>
            <div class="col-sm-4">
                <label for="hora_inicio2" class="form-label">Hora Incio</label>
                <input type="time" class="form-control" name="hora_inicio2" id="hora_inicio2" >
            </div>
            <div class="col-sm-4">
                <label for="hora_termino2" class="form-label">Hora Termino</label>
                <input type="time" class="form-control" name="hora_termino2" id="hora_termino2" >
            </div>

        </div>

        <div class="row col-sm-12">
            <div class="col-sm-4">
                <label for="dia3" class="form-label">Dia</label>
                <select class="form-select" name="dia3" id="dia3" >
                    <option value="">Seleccionar</option>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>   
            </div>
            <div class="col-sm-4">
                <label for="hora_inicio3" class="form-label">Hora Incio</label>
                <input type="time" class="form-control" name="hora_inicio3" id="hora_inicio3" >
            </div>
            <div class="col-sm-4">
                <label for="hora_termino3" class="form-label">Hora Termino</label>
                <input type="time" class="form-control" name="hora_termino3" id="hora_termino3" >
            </div>

        </div>

        <div class="row col-sm-12">
            <div class="col-sm-4">
                <label for="dia4" class="form-label">Dia</label>
                <select class="form-select" name="dia4" id="dia4" >
                    <option value="">Seleccionar</option>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>   
            </div>
            <div class="col-sm-4">
                <label for="hora_inicio4" class="form-label">Hora Incio</label>
                <input type="time" class="form-control" name="hora_inicio4" id="hora_inicio4" >
            </div>
            <div class="col-sm-4">
                <label for="hora_termino4" class="form-label">Hora Termino</label>
                <input type="time" class="form-control" name="hora_termino4" id="hora_termino4" >
            </div>

        </div>

        <div class="row col-sm-12">
            <div class="col-sm-4">
                <label for="dia5" class="form-label">Dia</label>
                <select class="form-select" name="dia5" id="dia5" >
                    <option value="">Seleccionar</option>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>   
            </div>
            <div class="col-sm-4">
                <label for="hora_inicio5" class="form-label">Hora Incio</label>
                <input type="time" class="form-control" name="hora_inicio5" id="hora_inicio5" >
            </div>
            <div class="col-sm-4">
                <label for="hora_termino5" class="form-label">Hora Termino</label>
                <input type="time" class="form-control" name="hora_termino5" id="hora_termino5" >
            </div>

        </div>


        </div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Guardar Asignación">
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

                <table id="tablaTalleresAsign" class="table table-striped table-bordered " style="width:100%">
                    <thead>
                        <tr>
                            <th>Centro</th>
                            <th>Docente</th>
                            <th>Taller</th>
                            <th>Horario</th>
                            <th>Costo</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clasesAsignadas as $clases)
                            <tr>
                                <td> @php
                                    $centro = \DB::table('centros')->where('id', $clases->centro)->first();   
                                @endphp 
                                {{ $centro->centro ?? 'Centro no disponible' }}</td>
                                <td> @php
                                    $docente = \DB::table('docentes')->where('id', $clases->docente)->first();   
                                @endphp 
                                {{ $docente ? trim(($docente->nombre ?? '') . ' ' . ($docente->paterno ?? '') . ' ' . ($docente->materno ?? '')) : 'Docente no disponible' }}</td>
                                <td> @php
                                    $taller = \DB::table('tallers')->where('id', $clases->taller)->first();   
                                @endphp 
                                {{ $taller->nombre ?? 'Taller no disponible' }}</td>
                                <td>
                                    
                                    @php
                                        $horarios = \DB::table('clase_horarios')->where('clase_asignada', $clases->id)->get();
                                    @endphp
                                    @foreach ($horarios as $horario)
                                        {{ $horario->dia }}: {{ date('H:i', strtotime($horario->hora_inicio)) }} - {{ date('H:i', strtotime($horario->hora_fin)) }}<br>
                                    @endforeach
                                </td>
                                <td>$ {{ $clases->costo }}</td>
                                <td>
                                    <a  data-bs-toggle="modal" data-bs-target="#modalEditClaseAsig{{ $clases->id }}" class="btn btn-warning"><span class="fas fa-edit"></span></a>
                                </td>
                                <td>
                                   <form  class="delete-form" action="{{ route('asignar-taller.destroy', $clases->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="clase" value="{{ $clases->id }}"  >
                                                        <input name="deshabilitado" value="1" type="hidden">
                                                    <button class="btn btn-danger">  <span class="fas fa-trash"></span></button>
                                        </form>
                                </td>
                            </tr>



                    <!-- Modal Editar Clase Asignada -->
<div class="modal fade" id="modalEditClaseAsig{{ $clases->id }}" tabindex="-1" aria-labelledby="modalEditClaseAsigLabel{{ $clases->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">        
        <h5 class="modal-title" id="modalEditClaseAsigLabel{{ $clases->id }}">Editar Clase Asignada {{ $clases->id }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('asignar-taller.update', $clases->id) }}" method="post">
          @csrf
          @method('PUT')
          <input type="hidden" name="id_clase" value="{{ $clases->id }}">
            <div class="row">

            
            <div class="col-sm-6">
                <label for="docente_id" class="form-label">Docente</label>
                <select class="form-select" name="docente_id" id="docente_id" required>
                    <option value="">Seleccionar</option>
                    @foreach ($docentes as $docente)
                        @if ($docente->id == $clases->docente)
                            <option value="{{ $docente->id }}" selected>{{ $docente->nombre }} {{ $docente->paterno }} {{ $docente->materno }}</option>
                        @else
                            <option value="{{ $docente->id }}">{{ $docente->nombre }} {{ $docente->paterno }} {{ $docente->materno }}</option>
                        @endif
                        
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <label for="taller_id" class="form-label">Taller</label>
                <select class="form-select" name="taller_id" id="taller_id" required>
                    <option value="">Seleccionar</option>
                    @foreach ($talleres as $taller)
                        <option value="{{ $taller->id }}" @if ($taller->id == $clases->taller) selected @endif >{{ $taller->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <label for="centro_id" class="form-label">Centro</label>
                <select class="form-select" name="centro_id" id="centro_id" required>
                    <option value="">Seleccionar</option>
                    @foreach ($centros as $centro)
                        @if ($centro->id == $clases->centro)
                            <option value="{{ $centro->id }}" selected>{{ $centro->centro }}</option>
                        @else
                            <option value="{{ $centro->id }}">{{ $centro->centro }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <label for="costo" class="form-label">Costo Hora</label>
                <input type="number" class="form-control" name="costo" id="costo" step="0.01" value="{{ $clases->costo }}" required>
            </div>

            
            <div class="col-sm-12">
                &nbsp;<br>
                <p><strong>Dias y horarios por semana</strong></p>
            </div>

        @php
            $horarios = \DB::table('clase_horarios')->where('clase_asignada', $clases->id)->get();
        @endphp


        

        <div class="row col-sm-12">
            <div class="col-sm-4">
                <label for="dia1" class="form-label">Dia</label>
                <select class="form-select" name="dia1" id="dia1" required>
                    <option value="">Seleccionar</option>
                    <option value="Lunes" {{ isset($horarios[0]) && $horarios[0]->dia == 'Lunes' ? 'selected' : '' }}>Lunes</option>
                    <option value="Martes" {{ isset($horarios[0]) && $horarios[0]->dia == 'Martes' ? 'selected' : '' }}>Martes</option>
                    <option value="Miércoles" {{ isset($horarios[0]) && $horarios[0]->dia == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                    <option value="Jueves" {{ isset($horarios[0]) && $horarios[0]->dia == 'Jueves' ? 'selected' : '' }}>Jueves</option>
                    <option value="Viernes" {{ isset($horarios[0]) && $horarios[0]->dia == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                    <option value="Sábado" {{ isset($horarios[0]) && $horarios[0]->dia == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                    <option value="Domingo" {{ isset($horarios[0]) && $horarios[0]->dia == 'Domingo' ? 'selected' : '' }}>Domingo</option>
                </select>
            </div>
            <div class="col-sm-4">
                <label for="hora_inicio1" class="form-label">Hora Incio</label>
                <input type="time" class="form-control" name="hora_inicio1" id="hora_inicio1" value="{{ isset($horarios[0]) ? $horarios[0]->hora_inicio : '' }}" required>
            </div>
            <div class="col-sm-4">
                <label for="hora_termino1" class="form-label">Hora Termino</label>
                <input type="time" class="form-control" name="hora_termino1" id="hora_termino1" value="{{ isset($horarios[0]) ? $horarios[0]->hora_fin : '' }}" required>
            </div>

        </div>

        <div class="row col-sm-12">
            <div class="col-sm-4">
                <label for="dia2" class="form-label">Dia</label>
                <select class="form-select" name="dia2" id="dia2" >
                    <option value="">Seleccionar</option>
                    <option value="Lunes" {{ isset($horarios[1]) && $horarios[1]->dia == 'Lunes' ? 'selected' : '' }}>Lunes</option>
                    <option value="Martes" {{ isset($horarios[1]) && $horarios[1]->dia == 'Martes' ? 'selected' : '' }}>Martes</option>
                    <option value="Miércoles" {{ isset($horarios[1]) && $horarios[1]->dia == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                    <option value="Jueves" {{ isset($horarios[1]) && $horarios[1]->dia == 'Jueves' ? 'selected' : '' }}>Jueves</option>
                    <option value="Viernes" {{ isset($horarios[1]) && $horarios[1]->dia == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                    <option value="Sábado" {{ isset($horarios[1]) && $horarios[1]->dia == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                    <option value="Domingo" {{ isset($horarios[1]) && $horarios[1]->dia == 'Domingo' ? 'selected' : '' }}>Domingo</option>
                </select>   
            </div>
            <div class="col-sm-4">
                <label for="hora_inicio2" class="form-label">Hora Incio</label>
                <input type="time" class="form-control" name="hora_inicio2" id="hora_inicio2" value="{{ isset($horarios[1]) ? $horarios[1]->hora_inicio : '' }}">
            </div>
            <div class="col-sm-4">
                <label for="hora_termino2" class="form-label">Hora Termino</label>
                <input type="time" class="form-control" name="hora_termino2" id="hora_termino2" value="{{ isset($horarios[1]) ? $horarios[1]->hora_fin : '' }}">
            </div>

        </div>

        <div class="row col-sm-12">
            <div class="col-sm-4">
                <label for="dia3" class="form-label">Dia</label>
                <select class="form-select" name="dia3" id="dia3" >
                    <option value="">Seleccionar</option>
                    <option value="Lunes" {{ isset($horarios[2]) && $horarios[2]->dia == 'Lunes' ? 'selected' : '' }}>Lunes</option>
                    <option value="Martes" {{ isset($horarios[2]) && $horarios[2]->dia == 'Martes' ? 'selected' : '' }}>Martes</option>
                    <option value="Miércoles" {{ isset($horarios[2]) && $horarios[2]->dia == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                    <option value="Jueves" {{ isset($horarios[2]) && $horarios[2]->dia == 'Jueves' ? 'selected' : '' }}>Jueves</option>
                    <option value="Viernes" {{ isset($horarios[2]) && $horarios[2]->dia == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                    <option value="Sábado" {{ isset($horarios[2]) && $horarios[2]->dia == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                    <option value="Domingo" {{ isset($horarios[2]) && $horarios[2]->dia == 'Domingo' ? 'selected' : '' }}>Domingo</option>
                </select>   
            </div>
            <div class="col-sm-4">
                <label for="hora_inicio3" class="form-label">Hora Incio</label>
                <input type="time" class="form-control" name="hora_inicio3" id="hora_inicio3" value="{{ isset($horarios[2]) ? $horarios[2]->hora_inicio : '' }}">
            </div>
            <div class="col-sm-4">
                <label for="hora_termino3" class="form-label">Hora Termino</label>
                <input type="time" class="form-control" name="hora_termino3" id="hora_termino3" value="{{ isset($horarios[2]) ? $horarios[2]->hora_fin : '' }}">
            </div>

        </div>

        <div class="row col-sm-12">
            <div class="col-sm-4">
                <label for="dia4" class="form-label">Dia</label>
                <select class="form-select" name="dia4" id="dia4" >
                    <option value="">Seleccionar</option>
                    <option value="Lunes" {{ isset($horarios[3]) && $horarios[3]->dia == 'Lunes' ? 'selected' : '' }}>Lunes</option>
                    <option value="Martes" {{ isset($horarios[3]) && $horarios[3]->dia == 'Martes' ? 'selected' : '' }}>Martes</option>
                    <option value="Miércoles" {{ isset($horarios[3]) && $horarios[3]->dia == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                    <option value="Jueves" {{ isset($horarios[3]) && $horarios[3]->dia == 'Jueves' ? 'selected' : '' }}>Jueves</option>
                    <option value="Viernes" {{ isset($horarios[3]) && $horarios[3]->dia == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                    <option value="Sábado" {{ isset($horarios[3]) && $horarios[3]->dia == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                    <option value="Domingo" {{ isset($horarios[3]) && $horarios[3]->dia == 'Domingo' ? 'selected' : '' }}>Domingo</option>
                </select>   
            </div>
            <div class="col-sm-4">
                <label for="hora_inicio4" class="form-label">Hora Incio</label>
                <input type="time" class="form-control" name="hora_inicio4" id="hora_inicio4" value="{{ isset($horarios[3]) ? $horarios[3]->hora_inicio : '' }}">
            </div>
            <div class="col-sm-4">
                <label for="hora_termino4" class="form-label">Hora Termino</label>
                <input type="time" class="form-control" name="hora_termino4" id="hora_termino4" value="{{ isset($horarios[3]) ? $horarios[3]->hora_fin : '' }}">
            </div>

        </div>

        <div class="row col-sm-12">
            <div class="col-sm-4">
                <label for="dia5" class="form-label">Dia</label>
                <select class="form-select" name="dia5" id="dia5" >
                    <option value="">Seleccionar</option>
                    <option value="Lunes" {{ isset($horarios[4]) && $horarios[4]->dia == 'Lunes' ? 'selected' : '' }}>Lunes</option>
                    <option value="Martes" {{ isset($horarios[4]) && $horarios[4]->dia == 'Martes' ? 'selected' : '' }}>Martes</option>
                    <option value="Miércoles" {{ isset($horarios[4]) && $horarios[4]->dia == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                    <option value="Jueves" {{ isset($horarios[4]) && $horarios[4]->dia == 'Jueves' ? 'selected' : '' }}>Jueves</option>
                    <option value="Viernes" {{ isset($horarios[4]) && $horarios[4]->dia == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                    <option value="Sábado" {{ isset($horarios[4]) && $horarios[4]->dia == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                    <option value="Domingo" {{ isset($horarios[4]) && $horarios[4]->dia == 'Domingo' ? 'selected' : '' }}>Domingo</option>
                </select>   
            </div>
            <div class="col-sm-4">
                <label for="hora_inicio5" class="form-label">Hora Incio</label>
                <input type="time" class="form-control" name="hora_inicio5" id="hora_inicio5" value="{{ isset($horarios[4]) ? $horarios[4]->hora_inicio : '' }}">
            </div>
            <div class="col-sm-4">
                <label for="hora_termino5" class="form-label">Hora Termino</label>
                <input type="time" class="form-control" name="hora_termino5" id="hora_termino5" value="{{ isset($horarios[4]) ? $horarios[4]->hora_fin : '' }}">
            </div>

        </div>



      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Actualizar Taller">
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


var table = $('#tablaTalleresAsign').DataTable({
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
