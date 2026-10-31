<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    @php
        $frontBackgroundPath = public_path('assets/img/frentecredecialesperanzarte.jpg');
        $backBackgroundPath = public_path('assets/img/atrascredencialesperanzarte.jpg');

        $frontBackgroundDataUri = null;
        $backBackgroundDataUri = null;

        if (file_exists($frontBackgroundPath)) {
            $frontBackgroundMime = mime_content_type($frontBackgroundPath) ?: 'image/jpeg';
            $frontBackgroundBase64 = base64_encode(file_get_contents($frontBackgroundPath));
            $frontBackgroundDataUri = "data:{$frontBackgroundMime};base64,{$frontBackgroundBase64}";
        }

        if (file_exists($backBackgroundPath)) {
            $backBackgroundMime = mime_content_type($backBackgroundPath) ?: 'image/jpeg';
            $backBackgroundBase64 = base64_encode(file_get_contents($backBackgroundPath));
            $backBackgroundDataUri = "data:{$backBackgroundMime};base64,{$backBackgroundBase64}";
        }

        $my_qr_content = $alumno->clave;
        $qr = null;
        $photoDataUri = null;

        if (!empty($alumno->foto)) {
            $photoPath = Storage::disk('public')->path($alumno->foto);

            if (file_exists($photoPath)) {
                $photoMime = mime_content_type($photoPath) ?: 'image/jpeg';
                $photoBase64 = base64_encode(file_get_contents($photoPath));
                $photoDataUri = "data:{$photoMime};base64,{$photoBase64}";
            }
        }

        if (extension_loaded('imagick')) {
            $qr = QrCode::size(200)
                        ->format('png')
                        ->generate($my_qr_content);
        }
    @endphp
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
        }

        .page {
            position: relative;
            width: 100%;
            height: 100%;
            page-break-after: always;
            overflow: hidden;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .page-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-position: center;
        }

        .content {
            position: relative;
            z-index: 10;
            padding: 10px;
        }

        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .pdf-left-cell {
            
            text-align: left;
            vertical-align: top;
        }

        .pdf-right-cell {
            text-align: left;
            vertical-align: top;
            padding-left: 10px;
        }
    </style>
</head>
<body>
    <div class="page">
        @if ($frontBackgroundDataUri)
            <div class="page-bg" style="background-image: url('{{ $frontBackgroundDataUri }}');"></div>
        @endif

        <div class="content" style="text-align: left; margin-top: 83px; margin-left: 30px;">
            <table class="pdf-table">
                <tr>
                    <td class="pdf-left-cell" >
                    @if ($photoDataUri)
                        <img src="{{ $photoDataUri }}" style="width: 180px; height: 210px; object-fit: cover;" alt="Foto de alumno"><br>
                    @endif
                    </td>
                    <td class="pdf-right-cell">
                        <div style="margin-left: -80px; margin-top:20px; color:#a21a4c; font-size:22px;">
                        <br>
                         <strong>{{ $alumno->nombre }}<br>
                         {{ $alumno->paterno }} {{ $alumno->materno }}<br>
                         {{ $alumno->folio }}</strong>

                        </div>
                    </td>
                </tr>
            </table>
            <br>

            
        </div>
    </div>

    <div class="page">
        @if ($backBackgroundDataUri)
            <div class="page-bg" style="background-image: url('{{ $backBackgroundDataUri }}');"></div>
        @endif

        <div class="content" style="text-align: left; margin-top: 20px;">
        {{ $alumno->domicilio }} #{{ $alumno->num_ext }}<br>
        @if ($alumno->colonia!='')
            {{ $alumno->colonia }}
            @else
            {{ $alumno->localidad }}
        @endif
    </div>

        <div class="content" style="text-align: center; margin-top: 10px;">
            @if ($qr)
                <img src="data:image/png;base64,{!! base64_encode($qr) !!}" />
            @endif  

        </div>
    </div>
</body>
</html>