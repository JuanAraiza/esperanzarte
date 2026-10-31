@extends('adminlte::page')

@section('title', 'Panel de Control')

@section('content_header')
    <h3 class="mb-0">Panel de Control</h3>
@stop

@section('content')
    <style>
        .kpi-box {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 14px;
            background: #f8fafc;
            height: 100%;
        }

        .kpi-title {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .kpi-value {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            line-height: 1;
        }

        .chart-card {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            padding: 12px;
            height: 100%;
        }

        .chart-title {
            font-weight: 700;
            margin-bottom: 8px;
            color: #111827;
        }

        .chart-wrap {
            position: relative;
            width: 100%;
            height: 220px;
        }

        .chart-wrap.chart-wrap-line {
            height: 200px;
        }

        .chart-wrap.chart-wrap-docentes {
            height: 210px;
        }

        .map-wrap {
            height: 420px;
        }

        .filter-card {
            border-radius: 12px;
            border: 1px solid #dbeafe;
            background: #eff6ff;
            padding: 12px;
        }

        .preset-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
    </style>

    @php
        $hoyFiltro = \Carbon\Carbon::today();
        $q7Inicio = $hoyFiltro->copy()->subDays(6)->toDateString();
        $q30Inicio = $hoyFiltro->copy()->subDays(29)->toDateString();
        $q90Inicio = $hoyFiltro->copy()->subDays(89)->toDateString();
        $anioInicio = $hoyFiltro->copy()->startOfYear()->toDateString();
        $hoyStr = $hoyFiltro->toDateString();
    @endphp

    <div class="filter-card mb-3">
        <form method="GET" action="{{ route('home') }}" class="row align-items-end">
            <div class="col-md-3 mb-2">
                <label for="start_date" class="mb-1"><strong>Fecha inicio</strong></label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-3 mb-2">
                <label for="end_date" class="mb-1"><strong>Fecha fin</strong></label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-6 mb-2 d-flex" style="gap: 8px;">
                <button type="submit" class="btn btn-primary">Aplicar filtro</button>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Último mes</a>
            </div>
            <div class="col-12 mt-1">
                <div class="preset-wrap">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('home', ['start_date' => $q7Inicio, 'end_date' => $hoyStr]) }}">Últimos 7 días</a>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('home', ['start_date' => $q30Inicio, 'end_date' => $hoyStr]) }}">Últimos 30 días</a>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('home', ['start_date' => $q90Inicio, 'end_date' => $hoyStr]) }}">Últimos 90 días</a>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('home', ['start_date' => $anioInicio, 'end_date' => $hoyStr]) }}">Año actual</a>
                </div>
            </div>
        </form>
    </div>

    <div class="row mb-3">
        <div class="col-md-4 mb-2">
            <div class="kpi-box">
                <div class="kpi-title">Total de asistencias en el rango</div>
                <div class="kpi-value">{{ number_format($totalAsistencias) }}</div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="kpi-box">
                <div class="kpi-title">Rango consultado</div>
                <div class="kpi-value" style="font-size: 18px; font-weight: 600;">
                    {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="kpi-box">
                <div class="kpi-title">Días en rango</div>
                <div class="kpi-value">{{ count($lineLabels) }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="chart-card">
                <div class="chart-title">Asistencias por día</div>
                <div class="chart-wrap chart-wrap-line">
                    <canvas id="chartLineAsistencias"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="chart-card">
                <div class="chart-title">Talleres con más asistencias</div>
                <div class="chart-wrap">
                    <canvas id="chartTalleres"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="chart-card">
                <div class="chart-title">Centros con más asistencias</div>
                <div class="chart-wrap">
                    <canvas id="chartCentros"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="chart-card">
                <div class="chart-title">Top 10 Edades con más asistencias</div>
                <div class="chart-wrap">
                    <canvas id="chartEdades"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="chart-card">
                <div class="chart-title">Género de alumnos que asisten</div>
                <div class="chart-wrap">
                    <canvas id="chartGenero"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="chart-card">
                <div class="chart-title">Ubicación de Centros Esperanzarte</div>
                <div id="mapaAsistencias" class="map-wrap"></div>
                <div id="mapaSinDatos" class="text-muted py-4 text-center d-none">No hay centros registrados con ubicación disponible.</div>
            </div>
        </div>
    </div>
@stop

@push('js')

    <script id="chartsData" type="application/json">{!! json_encode([
        'lineLabels' => $lineLabels,
        'lineValues' => $lineValues,
        'talleresLabels' => $topTalleresLabels,
        'talleresValues' => $topTalleresValues,
        'centrosLabels' => $topCentrosLabels,
        'centrosValues' => $topCentrosValues,
        'edadesLabels' => $topEdadesLabels,
        'edadesValues' => $topEdadesValues,
        'generoLabels' => $generoLabels,
        'generoValues' => $generoValues,
        'mapaCentros' => $mapaCentros,
    ], JSON_UNESCAPED_UNICODE) !!}</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
    
        const chartsData = JSON.parse(document.getElementById('chartsData')?.textContent || '{}');
        
        const lineLabels = chartsData.lineLabels || [];
        const lineValues = chartsData.lineValues || [];
        
        const talleresLabels = chartsData.talleresLabels || [];
        const talleresValues = chartsData.talleresValues || [];
        
        const centrosLabels = chartsData.centrosLabels || [];
        const centrosValues = chartsData.centrosValues || [];
        
        const edadesLabels = chartsData.edadesLabels || [];
        const edadesValues = chartsData.edadesValues || [];
        const generoLabels = chartsData.generoLabels || [];
        const generoValues = chartsData.generoValues || [];

        const commonBarOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            },
            plugins: {
                legend: { display: false }
            }
        };

        new Chart(document.getElementById('chartLineAsistencias'), {
            type: 'line',
            data: {
                labels: lineLabels,
                datasets: [{
                    label: 'Asistencias',
                    data: lineValues,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.18)',
                    fill: true,
                    tension: 0.25,
                    pointRadius: 2,
                    pointHoverRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });

        new Chart(document.getElementById('chartTalleres'), {
            type: 'bar',
            data: {
                labels: talleresLabels,
                datasets: [{
                    data: talleresValues,
                    backgroundColor: '#0ea5e9',
                    borderRadius: 8,
                }]
            },
            options: commonBarOptions,
        });

        new Chart(document.getElementById('chartCentros'), {
            type: 'bar',
            data: {
                labels: centrosLabels,
                datasets: [{
                    data: centrosValues,
                    backgroundColor: '#10b981',
                    borderRadius: 8,
                }]
            },
            options: commonBarOptions,
        });

        new Chart(document.getElementById('chartEdades'), {
            type: 'bar',
            data: {
                labels: edadesLabels,
                datasets: [{
                    data: edadesValues,
                    backgroundColor: '#8b5cf6',
                    borderRadius: 8,
                }]
            },
            options: commonBarOptions,
        });

        new Chart(document.getElementById('chartGenero'), {
            type: 'doughnut',
            data: {
                labels: generoLabels,
                datasets: [{
                    data: generoValues,
                    backgroundColor: ['#3b82f6', '#ec4899'],
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            },
        });

        // Inicializar mapa de centros
        const mapaElemento = document.getElementById('mapaAsistencias');
        const mapaSinDatos = document.getElementById('mapaSinDatos');
        const centros = chartsData.mapaCentros || [];

        if (mapaElemento && mapaSinDatos) {
            if (centros.length > 0) {
                // Cargar Leaflet dinámicamente
                const scriptLeaflet = document.createElement('script');
                scriptLeaflet.src = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js';
                scriptLeaflet.onload = function() {
                    const linkLeaflet = document.createElement('link');
                    linkLeaflet.rel = 'stylesheet';
                    linkLeaflet.href = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css';
                    document.head.appendChild(linkLeaflet);

                    setTimeout(() => {
                        const L = window.L;
                        const mapa = L.map(mapaElemento);
                        const marcadores = [];

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 24,
                            attribution: '&copy; OpenStreetMap contributors',
                        }).addTo(mapa);

                        centros.forEach((centro) => {
                             console.log('antes url');
                             console.log(centro.id);
                            var url =  "{{ route('centros.reporte', 'estado') }}";
                            url = url.replace('estado',centro.id);
                            console.log(url);
                            const marcador = L.marker([centro.latitud, centro.longitud])
                                .bindPopup('<strong>' + centro.centro + '</strong><br>' + centro.direccion + '<br>Teléfono: ' + centro.telefono + '<br><a href="' + url + '" class="btn btn-info"><i class="fa-solid fa-chart-pie"></i></a>')
                                .addTo(mapa);
                            marcadores.push(marcador);
                        });

                        if (marcadores.length > 0) {
                            mapa.fitBounds(L.featureGroup(marcadores).getBounds(), { padding: [30, 30], maxZoom: 24 });
                        }
                    }, 100);
                };
                document.head.appendChild(scriptLeaflet);
            } else {
                mapaElemento.classList.add('d-none');
                mapaSinDatos.classList.remove('d-none');
            }
        }
    </script>
@endpush
