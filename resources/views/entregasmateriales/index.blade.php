@extends('adminlte::page')

@section('title', 'Entregas de materiales')

@section('content_header')
    <h3 class="mb-0">Entregas de materiales</h3>
@stop

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <x-adminlte-card title="Buscar docente" theme="primary" theme-mode="outline">
        <div class="row align-items-end">
            <div class="col-md-8">
                <form action="{{ route('entregasmateriales.index') }}" method="GET" class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <label for="buscar" class="form-label">Folio o nombre</label>
                        <input type="search" id="buscar" name="buscar" value="{{ $busqueda }}" class="form-control" placeholder="Escribe el folio o nombre del docente" autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary" title="Buscar docente"><span class="fas fa-search"></span></button>
                </form>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <label class="form-label d-block">Credencial</label>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEscaner">
                    <span class="fas fa-qrcode"></span> Escanear QR
                </button>
            </div>
        </div>

        @if($busqueda !== '')
            <hr>
            @forelse($docentes as $docente)
                <a class="btn btn-outline-primary text-start me-2 mb-2" href="{{ route('entregasmateriales.index', ['docente_id' => $docente->id]) }}">
                    {{ $docente->folio }} - {{ $docente->nombre }} {{ $docente->paterno }} {{ $docente->materno }}
                </a>
            @empty
                <p class="text-muted mb-0">No se encontraron docentes.</p>
            @endforelse
        @endif
    </x-adminlte-card>

    @if($docenteSeleccionado)
        <x-adminlte-card title="Entrega para {{ $docenteSeleccionado->nombre }} {{ $docenteSeleccionado->paterno }}" theme="success" theme-mode="outline">
            <p class="text-muted">Folio: {{ $docenteSeleccionado->folio }}</p>
            <form action="{{ route('entregasmateriales.store') }}" method="POST" enctype="multipart/form-data" target="_blank" onsubmit="window.setTimeout(function () { window.location.href = '{{ route('listadoentregas.index') }}'; }, 200);">
                @csrf
                <input type="hidden" name="docente_id" value="{{ $docenteSeleccionado->id }}">
                <div class="mb-3 position-relative">
                    <label for="buscarMaterial" class="form-label">Buscar material</label>
                    <input type="search" id="buscarMaterial" class="form-control" autocomplete="off" placeholder="Nombre, marca o detalles del producto">
                    <div id="resultadosMateriales" class="list-group position-absolute w-100" style="z-index: 10;"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead><tr><th>Material</th><th>Marca</th><th>Unidad</th><th>Existencia</th><th style="width: 150px;">Cantidad</th><th></th></tr></thead>
                        <tbody id="materialesSeleccionados"><tr><td colspan="6" class="text-center text-muted">Busca y selecciona los materiales a entregar.</td></tr></tbody>
                    </table>
                </div>
                <div class="mb-3">
                    <label for="evidencia" class="form-label">Fotografía de evidencia</label>
                    <input id="evidencia" name="evidencia" type="file" class="form-control" accept="image/jpeg,image/png,image/webp" capture="environment" required>
                    <img id="vistaPreviaEvidencia" class="img-fluid mt-2 d-none" style="max-height: 220px;" alt="Vista previa de la evidencia">
                    @error('evidencia')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea id="observaciones" name="observaciones" class="form-control" rows="3" maxlength="5000" placeholder="Agrega observaciones de la entrega">{{ old('observaciones') }}</textarea>
                </div>
                @error('materiales')<div class="alert alert-danger">{{ $message }}</div>@enderror
                @error('observaciones')<div class="alert alert-danger">{{ $message }}</div>@enderror
                @if($materiales->isNotEmpty())
                    <button type="submit" class="btn btn-success"><span class="fas fa-hand-holding"></span> Registrar entrega</button>
                @endif
            </form>
        </x-adminlte-card>
    @endif

    <div class="modal fade" id="modalEscaner" tabindex="-1" aria-labelledby="modalEscanerTitulo" aria-hidden="true">
        <div class="modal-dialog"><div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEscanerTitulo">Escanear credencial del docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body"><div id="lectorQr"></div></div>
        </div></div>
    </div>
@stop

@push('js')
    <script src="{{ asset('vendor/html5-qrcode/html5-qrcode.min.js') }}"></script>
    <script id="materialesData" type="application/json">@json($materiales)</script>
    <script>
        let escanerQr = null;
        let qrDetectado = false;
        const modalEscaner = document.getElementById('modalEscaner');

        modalEscaner.addEventListener('shown.bs.modal', function () {
            qrDetectado = false;
            escanerQr = new Html5QrcodeScanner('lectorQr', {
                fps: 12,
                qrbox: { width: 250, height: 250 },
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
            });
            escanerQr.render(function (contenido) {
                if (qrDetectado) return;
                qrDetectado = true;
                escanerQr.clear().finally(function () {
                    window.location.href = "{{ route('entregasmateriales.index') }}?qr=" + encodeURIComponent(contenido);
                });
            });
        });

        modalEscaner.addEventListener('hidden.bs.modal', function () {
            if (escanerQr) {
                escanerQr.clear().catch(function () {}).finally(function () { escanerQr = null; });
            }
        });

        const materiales = JSON.parse(document.getElementById('materialesData')?.textContent || '[]');
        const seleccionados = new Map();
        const buscarMaterial = document.getElementById('buscarMaterial');
        const resultadosMateriales = document.getElementById('resultadosMateriales');
        const tablaSeleccionados = document.getElementById('materialesSeleccionados');

        function escaparHtml(valor) {
            const elemento = document.createElement('div');
            elemento.textContent = valor || '';
            return elemento.innerHTML;
        }

        function renderSeleccionados() {
            tablaSeleccionados.innerHTML = '';
            if (seleccionados.size === 0) {
                tablaSeleccionados.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Busca y selecciona los materiales a entregar.</td></tr>';
                return;
            }

            let indice = 0;
            seleccionados.forEach(function (material) {
                const fila = document.createElement('tr');
                fila.innerHTML = '<td>' + escaparHtml(material.material) + '<input type="hidden" name="materiales[' + indice + '][id]" value="' + material.id + '"></td>' +
                    '<td>' + escaparHtml(material.marca) + '</td><td>' + escaparHtml(material.unidad_medida) + '</td><td>' + material.stock + '</td>' +
                    '<td><input type="number" class="form-control" name="materiales[' + indice + '][cantidad]" value="1" min="1" max="' + material.stock + '" required></td>' +
                    '<td><button type="button" class="btn btn-outline-danger btn-sm" data-id="' + material.id + '" title="Quitar material"><span class="fas fa-times"></span></button></td>';
                tablaSeleccionados.appendChild(fila);
                indice++;
            });
        }

        buscarMaterial?.addEventListener('input', function () {
            const termino = buscarMaterial.value.trim().toLowerCase();
            resultadosMateriales.innerHTML = '';
            if (termino.length === 0) return;

            materiales.filter(function (material) {
                return !seleccionados.has(String(material.id)) && Number(material.stock) > 0 && [material.material, material.marca, material.detalles_producto]
                    .some(function (valor) { return String(valor || '').toLowerCase().includes(termino); });
            }).slice(0, 8).forEach(function (material) {
                const opcion = document.createElement('button');
                opcion.type = 'button';
                opcion.className = 'list-group-item list-group-item-action';
                opcion.innerHTML = '<strong>' + escaparHtml(material.material) + '</strong> - ' + escaparHtml(material.marca) + ' <small class="text-muted">Existencia: ' + material.stock + '</small>';
                opcion.addEventListener('click', function () {
                    seleccionados.set(String(material.id), material);
                    renderSeleccionados();
                    buscarMaterial.value = '';
                    resultadosMateriales.innerHTML = '';
                });
                resultadosMateriales.appendChild(opcion);
            });
        });

        tablaSeleccionados?.addEventListener('click', function (evento) {
            const boton = evento.target.closest('button[data-id]');
            if (!boton) return;
            seleccionados.delete(boton.dataset.id);
            renderSeleccionados();
        });

        document.getElementById('evidencia')?.addEventListener('change', function (evento) {
            const archivo = evento.target.files[0];
            const vistaPrevia = document.getElementById('vistaPreviaEvidencia');
            if (!archivo) return;
            vistaPrevia.src = URL.createObjectURL(archivo);
            vistaPrevia.classList.remove('d-none');
        });
    </script>
@endpush
