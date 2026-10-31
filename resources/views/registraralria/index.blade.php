@extends('adminlte::page')

@section('title', 'Registro de Alumnos RIA')
@section('classes_body', 'sidebar-collapse')

@section('content_header')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="mb-0">Registro de Alumnos</h3>
            <small>Captura INE frente, INE reverso y foto de rostro. Solo se solicitan telefono y correo.</small>
        </div>
    </div>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Hay errores en el formulario:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <style>
        .camera-wrap {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        #my_camera {
            width: 100%;
            min-height: 360px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            background: #111827;
            overflow: hidden;
        }

        .capture-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 12px;
            background: #f9fafb;
            margin-bottom: 10px;
        }

        .capture-pill {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 999px;
            color: #92400e;
            background: #fef3c7;
            border: 1px solid #fde68a;
        }

        .capture-pill.ok {
            color: #166534;
            background: #dcfce7;
            border-color: #86efac;
        }

        .loading-box {
            min-width: 300px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 18px;
        }

        .loading-steps {
            text-align: left;
            margin-top: 10px;
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

        .loading-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.88);
        }

        .loading-spinner {
            width: 56px;
            height: 56px;
            border: 6px solid #d1d5db;
            border-top-color: #16a34a;
            border-radius: 999px;
            animation: spin 0.9s linear infinite;
            margin: 0 auto 10px auto;
        }

        .capture-toast-wrap {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .capture-toast {
            min-width: 220px;
            max-width: 320px;
            background: #166534;
            color: #ffffff;
            border-radius: 8px;
            padding: 10px 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(-8px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            font-size: 14px;
        }

        .capture-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <div id="loadingOverlay" class="loading-overlay">
        <div class="text-center loading-box">
            <div class="loading-spinner"></div>
            <div id="loadingText" class="font-weight-bold">Procesando registro...</div>
            <ol class="loading-steps mb-0" id="loadingSteps">
                <li data-step="1">Validando capturas</li>
                <li data-step="2">Guardando foto de rostro</li>
                <li data-step="3">Guardando INE frente</li>
                <li data-step="4">Ejecutando OCR de INE</li>
                <li data-step="5">Guardando INE reverso</li>
                <li data-step="6">Creando alumno</li>
                <li data-step="7">Finalizando guardado</li>
                <li data-step="8">Registro completado</li>
            </ol>
        </div>
    </div>

    <div id="captureToastWrap" class="capture-toast-wrap"></div>

    <form action="{{ route('alumnos.registroalumnosria.store') }}" method="POST" id="riaForm" enctype="multipart/form-data" data-stream-url="{{ route('alumnos.registroalumnosria.stream') }}">
        @csrf
        <input type="hidden" name="submission_token" value="{{ old('submission_token', (string) \Illuminate\Support\Str::uuid()) }}">

        <div class="row">
            <div class="col-lg-6 mb-3">
                <div class="card card-outline card-success">
                    <div class="card-header"><strong>Camara</strong></div>
                    <div class="card-body camera-wrap">
                        <div id="my_camera"></div>
                        <div class="d-flex flex-wrap" style="gap: 8px;">
                            <button type="button" class="btn btn-success" id="btnIneFrente">Capturar INE frente</button>
                            <button type="button" class="btn btn-info" id="btnIneReverso">Capturar INE reverso</button>
                            <button type="button" class="btn btn-secondary" id="btnRostro">Capturar rostro</button>
                            <button type="button" class="btn btn-outline-warning" id="btnPermisoCamara">Solicitar permiso cámara</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="card card-outline card-primary">
                    <div class="card-header"><strong>Estado de capturas</strong></div>
                    <div class="card-body">
                        <div class="capture-status">
                            <span>INE frente</span>
                            <span class="capture-pill" id="statusIneFrente">Pendiente</span>
                        </div>
                        <div class="capture-status">
                            <span>INE reverso</span>
                            <span class="capture-pill" id="statusIneReverso">Pendiente</span>
                        </div>
                        <div class="capture-status mb-0">
                            <span>Rostro</span>
                            <span class="capture-pill" id="statusRostro">Pendiente</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telefono">Telefono</label>
                    <input type="text" class="form-control" name="telefono" id="telefono" maxlength="30"  value="{{ old('telefono') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="correo">Correo</label>
                    <input type="text" class="form-control" name="correo" id="correo" maxlength="120"  value="{{ old('correo') }}">
                </div>
            </div>
        </div>
            <div class="row">
<div class="col-md-12">
                <div class="form-group">
        <input type="file" name="image1" id="image1" class="d-none" accept="image/*">
        <input type="file" name="image2" id="image2" class="d-none" accept="image/*">
        <input type="file" name="image3" id="image3" class="d-none" accept="image/*">
                </div>
            </div>
        <div class="col-md-12">
                <div class="form-group">
                     <label >&nbsp;</label>
        <button type="submit" class="form-control btn btn-primary btn-block btn-lg">Guardar alumno</button>
                </div>
            </div>

</div>
    </form>
@stop

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/webcamjs@1.0.26/webcam.min.js"></script>
    <script>
        Webcam.set({
            width: 360,
            height: 480,
            dest_width: 720,
            dest_height: 960,
            image_format: 'jpeg',
            jpeg_quality: 90,
            flip_horiz: false,
            constraints: {
                facingMode: 'environment'
            }
        });

        Webcam.attach('#my_camera');

        let envioRiaEnCurso = false;

        // Botón para solicitar permiso de cámara nuevamente
        document.getElementById('btnPermisoCamara').addEventListener('click', function(e) {
            e.preventDefault();
            Webcam.reset();
            Webcam.set({
            width: 360,
            height: 480,
            dest_width: 720,
            dest_height: 960,
            image_format: 'jpeg',
            jpeg_quality: 90,
            flip_horiz: false,
            constraints: {
                facingMode: 'environment'
            }
        });
            Webcam.attach('#my_camera');
        });

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
            document.getElementById('loadingText').innerText = texto || 'Procesando registro...';
            document.getElementById('loadingOverlay').style.display = 'flex';
            if (step) {
                setLoadingStep(step);
            }
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        function dataUriToFile(dataUri, fileName) {
            const parts = dataUri.split(',');
            const mimeMatch = parts[0].match(/:(.*?);/);
            const mime = mimeMatch ? mimeMatch[1] : 'image/jpeg';
            const bstr = atob(parts[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], fileName, { type: mime });
        }

        function setFileInputFromDataUri(inputId, dataUri, fileName) {
            const file = dataUriToFile(dataUri, fileName);
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById(inputId).files = dt.files;
        }

        function marcarCaptura(statusId) {
            const el = document.getElementById(statusId);
            el.innerText = 'Capturada';
            el.classList.add('ok');
        }

        function mostrarMensajeCaptura(texto) {
            const wrap = document.getElementById('captureToastWrap');
            const toast = document.createElement('div');
            toast.className = 'capture-toast';
            toast.innerText = texto;
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
            }, 1800);
        }

        function capturar(target) {
            Webcam.snap(function(dataUri) {
                if (target === 'ine-frente') {
                    setFileInputFromDataUri('image2', dataUri, 'ine-frente.jpg');
                    marcarCaptura('statusIneFrente');
                    mostrarMensajeCaptura('INE frente capturada correctamente.');
                    return;
                }

                if (target === 'ine-reverso') {
                    setFileInputFromDataUri('image3', dataUri, 'ine-reverso.jpg');
                    marcarCaptura('statusIneReverso');
                    mostrarMensajeCaptura('INE reverso capturada correctamente.');
                    return;
                }

                setFileInputFromDataUri('image1', dataUri, 'rostro.jpg');
                marcarCaptura('statusRostro');
                mostrarMensajeCaptura('Foto de rostro capturada correctamente.');
            });
        }

        document.getElementById('btnIneFrente').addEventListener('click', function() {
            capturar('ine-frente');
        });

        document.getElementById('btnIneReverso').addEventListener('click', function() {
            capturar('ine-reverso');
        });

        document.getElementById('btnRostro').addEventListener('click', function() {
            capturar('rostro');
        });

        async function procesarStream(form) {
            const streamUrl = form.getAttribute('data-stream-url');
            const formData = new FormData(form);
            const csrf = form.querySelector('input[name="_token"]').value;

            const response = await fetch(streamUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/x-ndjson'
                },
                body: formData
            });

            if (!response.ok) {
                let mensaje = 'No se pudo iniciar el registro.';
                if (response.status === 422) {
                    const data = await response.json();
                    const errores = data && data.errors ? Object.values(data.errors).flat() : [];
                    if (errores.length > 0) {
                        mensaje = errores.join('\n');
                    }
                }
                throw new Error(mensaje);
            }

            const reader = response.body.getReader();
            const decoder = new TextDecoder('utf-8');
            let buffer = '';

            while (true) {
                const { value, done } = await reader.read();
                if (done) {
                    break;
                }

                buffer += decoder.decode(value, { stream: true });
                const lineas = buffer.split('\n');
                buffer = lineas.pop();

                for (const linea of lineas) {
                    const limpia = linea.trim();
                    if (!limpia) {
                        continue;
                    }

                    const payload = JSON.parse(limpia);
                    if (payload.type === 'progress') {
                        showLoading(payload.message, Number(payload.step || 1));
                    }

                    if (payload.type === 'done') {
                        showLoading(payload.message, Number(payload.step || 8));
                        setTimeout(function() {
                            window.location.href = payload.redirect || form.action;
                        }, 500);
                        return;
                    }

                    if (payload.type === 'error') {
                        throw new Error(payload.message || 'Error de registro.');
                    }
                }
            }
        }

        document.getElementById('riaForm').addEventListener('submit', async function(e) {
            const submitButton = this.querySelector('button[type="submit"]');

            if (envioRiaEnCurso) {
                e.preventDefault();
                return;
            }

            const image1 = document.getElementById('image1').files.length > 0;
            const image2 = document.getElementById('image2').files.length > 0;
            const image3 = document.getElementById('image3').files.length > 0;

            if (!image1 || !image2 || !image3) {
                e.preventDefault();
                alert('Debes capturar INE frente, INE reverso y rostro antes de guardar.');
                return;
            }

            e.preventDefault();
            try {
                envioRiaEnCurso = true;
                if (submitButton) {
                    submitButton.disabled = true;
                }
                showLoading('Validando capturas...', 1);
                await procesarStream(this);
            } catch (error) {
                envioRiaEnCurso = false;
                if (submitButton) {
                    submitButton.disabled = false;
                }
                hideLoading();
                alert(error.message || 'Ocurrio un error durante el registro.');
            }
        });

        window.addEventListener('pageshow', function() {
            envioRiaEnCurso = false;
            const submitButton = document.querySelector('#riaForm button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = false;
            }
            hideLoading();
        });
    </script>
@endpush
