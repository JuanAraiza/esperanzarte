@extends('adminlte::page')

@section('title', 'Observaciones del reporte')

@section('content_header')
    <h3>Preparar reporte de {{ $docente->nombre }} {{ $docente->paterno }}</h3>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <strong>Vista previa del reporte</strong>
            <span class="float-right">Del {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</span>
        </div>
        <form action="{{ route('docentes.reporte.observaciones', ['docente' => $docente->id]) }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="fecha_inicio" value="{{ $fechaInicio }}">
            <input type="hidden" name="fecha_fin" value="{{ $fechaFin }}">
            <div class="card-body p-0">
                @if($talleres->isEmpty())
                    <div class="alert alert-warning m-3">No hay talleres impartidos en el rango seleccionado.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Taller</th>
                                    <th>Centro cultural</th>
                                    <th class="text-center">Total de horas</th>
                                    <th style="min-width: 320px;">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($talleres as $indice => $taller)
                                    <tr>
                                        <td>{{ $taller['taller_nombre'] }}<input type="hidden" name="talleres[{{ $indice }}][taller]" value="{{ $taller['taller'] }}"></td>
                                        <td>{{ $taller['centro_nombre'] }}<input type="hidden" name="talleres[{{ $indice }}][centro]" value="{{ $taller['centro'] }}"></td>
                                        <td class="text-center">{{ $taller['total_horas'] }}<input type="hidden" name="talleres[{{ $indice }}][total_horas]" value="{{ $taller['total_horas'] }}"></td>
                                        <td><textarea class="form-control" name="talleres[{{ $indice }}][observaciones]" rows="3" maxlength="5000" placeholder="Escribe las observaciones del taller"></textarea></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('docentes.index') }}" class="btn btn-outline-secondary"><span class="fas fa-arrow-left"></span> Regresar</a>
                @if($talleres->isNotEmpty())
                    <button type="submit" class="btn btn-primary"><span class="fas fa-save"></span> Guardar y generar PDF</button>
                @endif
            </div>
        </form>
    </div>
@stop
