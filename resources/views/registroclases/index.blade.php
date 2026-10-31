@extends('adminlte::page')

@section('title', 'Registro Asistencia')
@section('classes_body', 'sidebar-collapse')

@section('content_header')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="mb-0">Registro de Asistencia</h3>
            <small>Escanea el QR del alumno para registrar asistencia y ubicación del dispositivo.</small>
        </div>
    </div>
@stop

@section('content')
    <style>
        .panel-card {
            border-radius: 14px;
            overflow: hidden;
        }

        #reader {
            width: 100%;
            min-height: 420px;
        }

        .camera-hint {
            margin-top: 10px;
            background: #111827;
            color: #e5e7eb;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13px;
        }

        .status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 10px;
        }

        .form-select-lite {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 8px 10px;
            width: 100%;
            background: #fff;
            font-size: 14px;
        }

        .horario-list {
            margin: 0;
            padding-left: 18px;
            font-size: 13px;
            color: #374151;
        }

        .horario-list li {
            margin-bottom: 4px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 12px;
            border: 1px solid #fbbf24;
            background: #fef3c7;
            color: #92400e;
        }

        .status-pill.ok {
            border-color: #86efac;
            background: #dcfce7;
            color: #166534;
        }

        .status-pill.err {
            border-color: #fca5a5;
            background: #fee2e2;
            color: #991b1b;
        }

        .loading-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.9);
        }

        .loading-box {
            min-width: 320px;
            max-width: 420px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            text-align: center;
        }

        .loading-spinner {
            width: 58px;
            height: 58px;
            border-radius: 999px;
            border: 6px solid #d1d5db;
            border-top-color: #2563eb;
            margin: 0 auto 12px auto;
            animation: giro 0.9s linear infinite;
        }

        .loading-steps {
            text-align: left;
            margin: 14px 0 0 0;
            padding-left: 18px;
            color: #4b5563;
            font-size: 13px;
        }

        .loading-steps li {
            margin-bottom: 4px;
        }

        .loading-steps li.active {
            color: #166534;
            font-weight: 700;
        }

        .scan-toast-wrap {
            position: fixed;
            top: 18px;
            right: 18px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .scan-toast {
            min-width: 230px;
            max-width: 340px;
            background: #111827;
            color: #fff;
            border-radius: 10px;
            padding: 10px 12px;
            opacity: 0;
            transform: translateY(-8px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.22);
            font-size: 14px;
        }

        .scan-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes giro {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <div id="loadingText" class="font-weight-bold">Preparando escáner...</div>
            <ol class="loading-steps" id="loadingSteps">
                <li data-step="1">Leyendo QR</li>
                <li data-step="2">Obteniendo ubicación</li>
                <li data-step="3">Registrando asistencia</li>
                <li data-step="4">Finalizando</li>
            </ol>
        </div>
    </div>

    <div id="scanToastWrap" class="scan-toast-wrap"></div>

    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="card card-outline card-primary panel-card h-100">
                <div class="card-header">
                    <strong>Contexto de clase</strong>
                </div>
                <div class="card-body">
                    <div class="status-row">
                        <span>Docente</span>
                        <span class="status-pill ok" style="border-color:#60a5fa;background:#eff6ff;color:#1e3a8a;">
                            {{ trim(($docenteRegistro?->nombre ?? '') . ' ' . ($docenteRegistro?->paterno ?? '')) }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <label for="centroSelect" class="mb-1"><strong>Centro</strong></label>
                        <select id="centroSelect" class="form-select-lite" style="color:#111827;">
                            @foreach(($centrosDisponibles ?? collect()) as $centroItem)
                                <option value="{{ $centroItem['id'] }}" {{ (string) ($centroId ?? '') === (string) $centroItem['id'] ? 'selected' : '' }}>
                                    {{ $centroItem['nombre'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="claseSelect" class="mb-1"><strong>Clase / Taller</strong></label>
                        <select id="claseSelect" class="form-select-lite" style="color: #111827;"></select>
                    </div>
                    <div class="mb-2">
                        <label for="horarioSelect" class="mb-1"><strong>Horario</strong></label>
                        <select id="horarioSelect" class="form-select-lite" style="color: #111827;"></select>
                        <small id="horarioHint" class="text-muted d-block mt-1"></small>
                    </div>
                    <div class="status-row mb-0" style="display:block;">
                        <div><strong>Días y horarios permitidos</strong></div>
                        <ul id="horariosClase" class="horario-list mt-2">
                            <li>Sin horario configurado.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-3">
            <div class="card card-outline card-success panel-card h-100">
                <div class="card-header">
                    <strong>Escáner QR</strong>
                </div>
                <div class="card-body">
                    <div id="reader"></div>
                    <div class="camera-hint" id="cameraHint">Iniciando cámara...</div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 mb-3">
            <div class="card card-outline card-warning panel-card h-100">
                <div class="card-header">
                    <strong>Estado del dispositivo</strong>
                </div>
                <div class="card-body">
                    <div class="status-row">
                        <span>Ubicación</span>
                        <span id="ubicacionStatus" class="status-pill">Pendiente</span>
                    </div>
                    <div class="status-row">
                        <span>Último QR</span>
                        <span id="qrStatus" class="status-pill">Esperando</span>
                    </div>
                    <div class="status-row mb-0">
                        <span>Asistencia</span>
                        <span id="asistenciaStatus" class="status-pill">Sin registro</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="asistenciaForm" class="d-none">
        @csrf
        <input type="hidden" name="alumno_clave" id="alumno_clave">
        <input type="hidden" name="latitud" id="latitud">
        <input type="hidden" name="longitud" id="longitud">
        <input type="hidden" name="clase_asignada_id" id="clase_asignada_id" value="{{ $claseAsignadaId ?? '' }}">
        <input type="hidden" name="horario" id="horario" value="">
    </form>
@stop

@push('js')
    <script id="clasesDataJson" type="application/json">@json($clasesData ?? [])</script>
    <script src="{{ asset('vendor/html5-qrcode/html5-qrcode.min.js') }}"></script>
    <script>
        let scannerInstance = null;
        let procesando = false;
        let ubicacionLista = false;
        let cameraPermissionGranted = false;
        const clasesData = JSON.parse(document.getElementById('clasesDataJson')?.textContent || '[]');
        let centroSeleccionado = "{{ $centroId ?? '' }}";
        let claseSeleccionada = "{{ $claseAsignadaId ?? '' }}";

        const routeGuardar = "{{ route('registroclases.store') }}";
        const csrfToken = "{{ csrf_token() }}";

        function cargarClasesPorCentro(centroId) {
            const claseSelect = document.getElementById('claseSelect');
            const clasesCentro = clasesData.filter(function(c) {
                return String(c.centro_id) === String(centroId);
            });

            claseSelect.innerHTML = '';

            if (clasesCentro.length === 0) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Sin clases disponibles en este centro';
                claseSelect.appendChild(opt);
                claseSeleccionada = '';
                document.getElementById('clase_asignada_id').value = '';
                renderHorarios([]);
                return;
            }

            clasesCentro.forEach(function(clase) {
                const opt = document.createElement('option');
                opt.value = String(clase.id);
                opt.textContent = clase.taller_nombre + ' (Clase #' + clase.id + ')';
                claseSelect.appendChild(opt);
            });

            const existeSeleccionada = clasesCentro.some(function(c) {
                return String(c.id) === String(claseSeleccionada);
            });

            if (!existeSeleccionada) {
                claseSeleccionada = String(clasesCentro[0].id);
            }

            claseSelect.value = String(claseSeleccionada);
            document.getElementById('clase_asignada_id').value = String(claseSeleccionada);

            const claseActual = clasesCentro.find(function(c) {
                return String(c.id) === String(claseSeleccionada);
            });
            renderHorarios(claseActual ? claseActual.horarios : []);
        }

        function renderHorarios(horarios) {
            const ul = document.getElementById('horariosClase');
            const horarioSelect = document.getElementById('horarioSelect');
            const horarioHint = document.getElementById('horarioHint');
            ul.innerHTML = '';
            horarioSelect.innerHTML = '';
            horarioHint.textContent = '';

            if (!horarios || horarios.length === 0) {
                const li = document.createElement('li');
                li.textContent = 'Sin horario configurado.';
                ul.appendChild(li);
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Sin horario disponible';
                horarioSelect.appendChild(opt);
                document.getElementById('horario').value = '';
                return;
            }

            const ahora = new Date();
            const diaActual = ahora.getDay() === 0 ? 7 : ahora.getDay();
            const minutosActuales = ahora.getHours() * 60 + ahora.getMinutes();
            const horariosOrdenados = horarios.map(function(horario) {
                const [hora, minuto] = String(horario.hora_inicio).slice(0, 5).split(':').map(Number);
                const [horaFin, minutoFin] = String(horario.hora_fin).slice(0, 5).split(':').map(Number);
                let diasHasta = (Number(horario.dia) - diaActual + 7) % 7;
                const minutosInicio = hora * 60 + minuto;
                const minutosFin = horaFin * 60 + minutoFin;

                if (diasHasta === 0 && minutosFin < minutosActuales) {
                    diasHasta = 7;
                }

                return {
                    horario: horario,
                    orden: diasHasta * 24 * 60 + minutosInicio,
                    minutosInicio: minutosInicio,
                    minutosFin: minutosFin,
                };
            }).sort(function(a, b) {
                return a.orden - b.orden;
            });

            const proximoHorario = horariosOrdenados[0].horario;

            horarios.forEach(function(h) {
                const li = document.createElement('li');
                li.textContent = h.etiqueta;
                ul.appendChild(li);

                const opt = document.createElement('option');
                opt.value = String(h.id);
                opt.textContent = h.etiqueta;
                if (String(h.id) !== String(proximoHorario.id)) {
                    opt.disabled = true;
                }
                horarioSelect.appendChild(opt);
            });

            horarioSelect.value = String(proximoHorario.id);
            document.getElementById('horario').value = String(proximoHorario.id);
            horarioHint.textContent = 'Solo está habilitado el próximo horario.';
        }

        function setLoadingStep(step) {
            document.querySelectorAll('#loadingSteps li').forEach(function(item) {
                const itemStep = Number(item.getAttribute('data-step'));
                if (itemStep <= step) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        }

        function showLoading(texto, step) {
            document.getElementById('loadingText').innerText = texto || 'Procesando...';
            document.getElementById('loadingOverlay').style.display = 'flex';
            if (step) {
                setLoadingStep(step);
            }
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        function showToast(message) {
            const wrap = document.getElementById('scanToastWrap');
            const toast = document.createElement('div');
            toast.className = 'scan-toast';
            toast.innerText = message;
            wrap.appendChild(toast);
            requestAnimationFrame(function() {
                toast.classList.add('show');
            });

            setTimeout(function() {
                toast.classList.remove('show');
                setTimeout(function() {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 220);
            }, 2200);
        }

        function setStatus(id, text, state) {
            const el = document.getElementById(id);
            el.innerText = text;
            el.className = 'status-pill';
            if (state) {
                el.classList.add(state);
            }
        }

        function normalizarClaveQR(resultado) {
            const texto = String(resultado || '').trim();

            try {
                if (texto.includes('entrem=')) {
                    const url = new URL(texto, window.location.origin);
                    return url.searchParams.get('entrem') || texto;
                }

                if (texto.startsWith('http://') || texto.startsWith('https://')) {
                    const url = new URL(texto);
                    return url.searchParams.get('entrem') || url.searchParams.get('cve') || texto;
                }
            } catch (error) {
                return texto;
            }

            return texto;
        }

        function setCameraHint(texto) {
            document.getElementById('cameraHint').innerText = texto;
        }

        async function detenerCamara() {
            if (scannerInstance) {
                try {
                    await scannerInstance.clear();
                } catch (error) {
                    // Ignorar errores al limpiar instancia previa.
                }
                scannerInstance = null;
            }
            setCameraHint('Cámara detenida.');
        }

        async function iniciarCamara() {
            await detenerCamara();

            if (typeof Html5QrcodeScanner === 'undefined') {
                setCameraHint('No se cargó html5-qrcode.');
                showToast('No se pudo cargar la librería de escaneo QR.');
                return;
            }

            try {
                // Verificar si ya se solicitaron permisos en esta sesión
                const permisosYaSolicitados = sessionStorage.getItem('cameraPermissionRequested') === 'true';
                if (permisosYaSolicitados) {
                    setCameraHint('Reiniciando cámara...');
                } else {
                    setCameraHint('Solicitando acceso a la cámara...');
                }
                
                document.getElementById('reader').innerHTML = '';
                scannerInstance = new Html5QrcodeScanner('reader', {
                    fps: 12,
                    qrbox: { width: 250, height: 250 },
                    rememberLastUsedCamera: true,
                    showTorchButtonIfSupported: false,
                    showZoomSliderIfSupported: false,
                    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
                }, false);

                scannerInstance.render(async function onSuccess(decodedText) {
                    if (procesando) {
                        return;
                    }

                    const clave = normalizarClaveQR(decodedText);
                    if (!clave) {
                        return;
                    }

                    // Marcar que se solicitaron los permisos exitosamente
                    sessionStorage.setItem('cameraPermissionRequested', 'true');

                    setStatus('qrStatus', 'Detectado', 'ok');
                    showToast('QR detectado. Iniciando registro...');
                    setCameraHint('QR detectado, procesando...');
                    await detenerCamara();
                    await registrarAsistencia(clave);
                }, function onError() {
                    // Errores de lectura transitorios, se ignoran.
                });

                setCameraHint('Cámara activa. Coloca el QR dentro del cuadro.');
                // Marcar que ya se solicitaron los permisos
                sessionStorage.setItem('cameraPermissionRequested', 'true');
            } catch (error) {
                setCameraHint('No se pudo abrir la cámara: ' + error.message);
                showToast('No se pudo abrir la cámara: ' + error.message);
            }
        }

        function obtenerUbicacion() {
            return new Promise(function(resolve, reject) {
                if (!navigator.geolocation) {
                    reject(new Error('Tu navegador no soporta geolocalización.'));
                    return;
                }

                navigator.geolocation.getCurrentPosition(function(posicion) {
                    const latitud = posicion.coords.latitude.toString();
                    const longitud = posicion.coords.longitude.toString();
                    document.getElementById('latitud').value = latitud;
                    document.getElementById('longitud').value = longitud;
                    ubicacionLista = true;
                    setStatus('ubicacionStatus', 'Lista', 'ok');
                    resolve({ latitud: latitud, longitud: longitud });
                }, function(error) {
                    ubicacionLista = false;
                    setStatus('ubicacionStatus', 'Error', 'err');
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            reject(new Error('Debes permitir el acceso a la ubicación para registrar la asistencia.'));
                            break;
                        case error.POSITION_UNAVAILABLE:
                            reject(new Error('No fue posible obtener la ubicación del dispositivo.'));
                            break;
                        case error.TIMEOUT:
                            reject(new Error('La solicitud de ubicación excedió el tiempo de espera.'));
                            break;
                        default:
                            reject(new Error('No se pudo obtener la ubicación del dispositivo.'));
                    }
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            });
        }

        async function asegurarUbicacion() {
            if (ubicacionLista && document.getElementById('latitud').value && document.getElementById('longitud').value) {
                return {
                    latitud: document.getElementById('latitud').value,
                    longitud: document.getElementById('longitud').value,
                };
            }

            showLoading('Obteniendo ubicación...', 2);
            return await obtenerUbicacion();
        }

        async function registrarAsistencia(claveAlumno) {
            if (procesando) {
                return;
            }

            procesando = true;
            document.getElementById('alumno_clave').value = claveAlumno;
            setStatus('qrStatus', 'Detectado', 'ok');
            showLoading('QR detectado, preparando registro...', 1);

            try {
                const coords = await asegurarUbicacion();
                showLoading('Registrando asistencia...', 3);

                const form = document.getElementById('asistenciaForm');
                const formData = new FormData(form);
                formData.set('alumno_clave', claveAlumno);
                formData.set('latitud', coords.latitud);
                formData.set('longitud', coords.longitud);

                const response = await fetch(routeGuardar, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok || !data.ok) {
                    throw new Error(data.message || 'No se pudo registrar la asistencia.');
                }

                setStatus('asistenciaStatus', data.duplicate ? 'Duplicada' : 'Registrada', 'ok');
                showLoading(data.message || 'Asistencia registrada.', 4);
                showToast(data.message || 'Asistencia registrada correctamente.');

                // No recargar la página completa, solo reiniciar la cámara
                setTimeout(async function() {
                    hideLoading();
                    // Limpiar el estado de QR
                    setStatus('qrStatus', 'Esperando', '');
                    // Reiniciar la cámara sin recargar la página
                    procesando = false;
                    await iniciarCamara();
                }, 3200);
            } catch (error) {
                hideLoading();
                setStatus('asistenciaStatus', 'Error', 'err');
                showToast(error.message || 'Ocurrió un error al registrar.');
                procesando = false;
                await iniciarCamara();
            }
        }

        document.addEventListener('DOMContentLoaded', async function() {
            const centroSelect = document.getElementById('centroSelect');
            const claseSelect = document.getElementById('claseSelect');
            const horarioSelect = document.getElementById('horarioSelect');

            if (centroSelect) {
                centroSeleccionado = centroSelect.value || centroSeleccionado;
                cargarClasesPorCentro(centroSeleccionado);

                centroSelect.addEventListener('change', function() {
                    centroSeleccionado = this.value;
                    cargarClasesPorCentro(centroSeleccionado);
                });
            }

            if (claseSelect) {
                claseSelect.addEventListener('change', function() {
                    claseSeleccionada = this.value;
                    document.getElementById('clase_asignada_id').value = String(claseSeleccionada || '');

                    const claseActual = clasesData.find(function(c) {
                        return String(c.id) === String(claseSeleccionada);
                    });
                    renderHorarios(claseActual ? claseActual.horarios : []);
                });
            }

            if (horarioSelect) {
                horarioSelect.addEventListener('change', function() {
                    document.getElementById('horario').value = this.value;
                });
            }

            try {
                setStatus('ubicacionStatus', 'Buscando...', '');
                await obtenerUbicacion();
            } catch (error) {
                setStatus('ubicacionStatus', 'Error', 'err');
                showToast(error.message);
            }

            await iniciarCamara();
        });

        window.addEventListener('beforeunload', function() {
            detenerCamara();
        });
    </script>
@endpush
