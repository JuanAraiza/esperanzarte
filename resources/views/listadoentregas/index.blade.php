@extends('adminlte::page')

@section('title', 'Listado Entregas de materiales')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Listado Entregas de materiales</h3>
        @if($docenteId)
            <a href="{{ route('listadoentregas.index') }}" class="btn btn-outline-secondary btn-sm">Ver todas</a>
        @endif
    </div>
@stop

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <x-adminlte-card title="Listado de Entregas" theme="primary" theme-mode="outline">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Docente</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        
                        <th>Observaciones</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entregas as $entrega)
                        <tr>
                            <td>{{ $entrega->folio }}</td>
                            <td>
                            @php
                                $docente = \App\Models\docente::find($entrega->docente);
                            @endphp
                            
                            {{ $docente ? $docente->nombre : 'Desconocido' }}</td>
                            <td>{{ $entrega->fecha }}</td>
                            <td>{{ $entrega->hora }}</td>
                           
                            <td>{{ $entrega->observaciones }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#evidenciaModal{{ $entrega->id }}">
                                    <i class="fa-solid fa-camera"></i>
                                </button>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#materialesModal{{ $entrega->id }}">
                                    Materiales
                                </button>
                            </td>

                            <!-- Evidencia Modal -->
                            <div class="modal fade" id="evidenciaModal{{ $entrega->id }}" tabindex="-1" aria-labelledby="evidenciaModalLabel{{ $entrega->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="evidenciaModalLabel{{ $entrega->id }}">Evidencia</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                   
                                    @if($entrega->evidencia)
                                        <img src="{{ asset('storage/' . $entrega->evidencia) }}" class="img-fluid" alt="Evidencia">
                                    @else
                                        <span class="text-muted">No hay evidencia</span>
                                    @endif
                                  </div>
                                </div>
                              </div>
                            </div>
                            

<!-- Modal -->
<div class="modal fade" id="materialesModal{{ $entrega->id }}" tabindex="-1" aria-labelledby="materialesModalLabel{{ $entrega->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="materialesModalLabel{{ $entrega->id }}">Materiales Entregados</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @php
            $materiales = \App\Models\MaterialesEntregado::where('entrega_material', $entrega->id)->get();
        @endphp
        @if($materiales->isEmpty())
            <span class="text-muted">No hay materiales</span>
        @else
            <div class="list-group">
                <div class="list-group-item"> 
                    <div class="d-flex justify-content-between">
                        <span>Material</span>
                        <span>Cantidad</span>
                    </div>
                    </div>
                @foreach($materiales as $materialEntregado)
                    @php
                        $material = \App\Models\Material::find($materialEntregado->material);
                    @endphp
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span>{{ $material ? $material->material : 'Desconocido' }}</span>
                            <span>{{ $materialEntregado->cantidad }}</span>
                        </div>
                    </div>
                @endforeach
            
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
                           

<td class="text-center"><a href="{{ route('listadoentregas.pdf', ['entrega' => $entrega->id]) }}" target="_blank" class="btn btn-warning" title="Reimprimir formato de entrega">
                                    <span class="fas fa-file-pdf"></span>
                                </a>
</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">No hay entregas registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>
@stop   

