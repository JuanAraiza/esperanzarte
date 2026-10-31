@extends('adminlte::page')

@section('title', 'Reportes por Docente')

@section('content_header')
    <h3 class="mb-0">Reportes por Docente</h3>
@stop

@section('content')
    <x-adminlte-card theme="primary" theme-mode="outline" title="Filtros de consulta">
        <form method="GET" action="{{ route('reportes.docentes') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="docente_id">Docente</label>
                        <select name="docente_id" id="docente_id" class="form-control" required>
                            <option value="">Selecciona un docente</option>
                            @foreach ($docentes as $docente)
                                <option value="{{ $docente->id }}" {{ (string) old('docente_id', $filtros['docente_id'] ?? '') === (string) $docente->id ? 'selected' : '' }}>
                                    {{ trim($docente->nombre.' '.$docente->paterno.' '.$docente->materno) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha inicio</label>
                        <input
                            type="date"
                            id="fecha_inicio"
                            name="fecha_inicio"
                            class="form-control"
                            value="{{ old('fecha_inicio', $filtros['fecha_inicio'] ?? '') }}"
                            required
                        >
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_fin">Fecha fin</label>
                        <input
                            type="date"
                            id="fecha_fin"
                            name="fecha_fin"
                            class="form-control"
                            value="{{ old('fecha_fin', $filtros['fecha_fin'] ?? '') }}"
                            required
                        >
                    </div>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">Consultar</button>
                </div>
            </div>
        </form>
    </x-adminlte-card>

    @if ($puedeConsultar)
        <div class="d-flex justify-content-end mb-3">
            <a
                href="{{ route('reportes.docentes.pdf', ['docente_id' => $filtros['docente_id'], 'fecha_inicio' => $filtros['fecha_inicio'], 'fecha_fin' => $filtros['fecha_fin']]) }}"
                target="_blank"
                class="btn btn-danger"
            >
                <i class="fas fa-file-pdf mr-1"></i> Imprimir oficio en PDF
            </a>
        </div>

        <div class="row">
            <div class="col-md-4">
                <x-adminlte-small-box title="{{ $resumen['sesiones'] }}" text="Sesiones impartidas" icon="fas fa-calendar-days" theme="info" />
            </div>
            <div class="col-md-4">
                <x-adminlte-small-box title="{{ $resumen['talleres_distintos'] }}" text="Talleres distintos" icon="fas fa-chalkboard-user" theme="success" />
            </div>
            <div class="col-md-4">
                <x-adminlte-small-box title="{{ $resumen['alumnos_asistentes'] }}" text="Asistencias de alumnos" icon="fas fa-user-check" theme="warning" />
            </div>
        </div>

        <x-adminlte-card theme="secondary" theme-mode="outline" title="Detalle de sesiones por rango de fechas">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Docente</th>
                            <th>Taller</th>
                            <th>Centro</th>
                            <th>Alumnos que asistieron</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reporte as $fila)
                            @php
                                $llaveSesion = $fila->dia.'|'.$fila->clase_asignada_id;
                                $asistentesSesion = $asistentesPorSesion[$llaveSesion] ?? collect();
                                $modalId = 'modalAsistentes'.md5($llaveSesion);
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($fila->dia)->format('d/m/Y') }}</td>
                                <td>{{ $fila->docente_nombre }}</td>
                                <td>{{ $fila->taller_nombre }}</td>
                                <td>{{ $fila->centro_nombre }}</td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-success btn-block"
                                        data-bs-toggle="modal"
                                        data-bs-target="#{{ $modalId }}"
                                        title="Ver alumnos asistentes"
                                    >
                                        {{ $fila->total_alumnos }}
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Alumnos asistentes</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-2">
                                                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fila->dia)->format('d/m/Y') }}
                                                <br>
                                                <strong>Taller:</strong> {{ $fila->taller_nombre }}
                                                <br>
                                                <strong>Centro:</strong> {{ $fila->centro_nombre }}
                                            </p>

                                            <div class="row table-responsive">
                                                         <div class="row col-12"><hr class="my-2"></div>
                                                        @forelse ($asistentesSesion as $asistente)
                                                        <div class="row col-12">
                                                            <div class="col-md-4"><strong>Folio:</strong> {{ $asistente->folio }}</div>
                                                            <div class="col-md-4"><strong>Alumno:</strong> {{ $asistente->nombre_completo }}</div>
                                                            <div class="col-md-4"><strong>Direccion:</strong> {{ $asistente->direccion !== '' ? $asistente->direccion : 'Sin direccion registrada' }}</div>
                                                        </div>
                                                        <div class="row col-12"><hr class="my-2"></div>
                                                            @empty
                                                            <div class="col-12">No hay alumnos registrados para esta sesión.</div>
                                                        @endforelse

                                                  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay asistencias registradas para ese docente en el rango seleccionado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-adminlte-card>
    @else
        <x-adminlte-card theme="light" theme-mode="outline">
            <p class="mb-0">Selecciona un docente y un rango de fechas para generar el reporte.</p>
        </x-adminlte-card>
    @endif
@stop



