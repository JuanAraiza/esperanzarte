<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    @php
        $backgroundPath = public_path('assets/img/fondocred44.jpg');
        $backgroundDataUri = null;

        if (file_exists($backgroundPath)) {
            $backgroundMime = mime_content_type($backgroundPath) ?: 'image/jpeg';
            $backgroundBase64 = base64_encode(file_get_contents($backgroundPath));
            $backgroundDataUri = "data:{$backgroundMime};base64,{$backgroundBase64}";
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

        .page-bg {
            position: fixed;
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
    </style>
</head>
<body>
  
            @if ($backgroundDataUri)
                <div class="page-bg" style="background-image: url('{{ $backgroundDataUri }}');"></div>
            @endif

            <div class="content" style="text-align: center; margin-top: 0px;">
                <h2>DOCENTES<br>
                {{ $docentes->folio }} - {{ $docentes->nombre }} {{ $docentes->paterno }}</h2>
                <br>
                @php

                $my_qr_content = "https://celayalaesperanza.com/?esperanzaDE=".$docentes->clave;
                    $qr = null;
                    if (extension_loaded('imagick')) {
                    $qr = QrCode::size(250)
                                    ->format('png')
                                    ->generate($my_qr_content);
                    }
                    
                @endphp

                @if ($qr)
                    <img src="data:image/png;base64,{!! base64_encode($qr) !!}" />
                @endif
            </div>
        
</body>
</html>