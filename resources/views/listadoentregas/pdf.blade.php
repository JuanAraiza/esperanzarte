<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formato de entrega {{ $entrega->folio }}</title>
    <style>
        @page { margin: 28px 34px; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 10px; }
        .header { width: 100%; border-bottom: 2px solid #1f4e79; padding-bottom: 10px; }
        .logo { width: 105px; }
        .title { text-align: center; font-size: 14px; font-weight: bold; color: #1f4e79; }
        .subtitle { text-align: center; font-size: 9px; margin-top: 4px; }
        .meta { width: 100%; margin: 18px 0 12px; border-collapse: collapse; }
        .meta td { border: 1px solid #9ca3af; padding: 7px; }
        .label { font-weight: bold; color: #1f4e79; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items th { background: #1f4e79; color: #fff; padding: 8px; text-align: left; }
        .items td { border: 1px solid #9ca3af; padding: 8px; }
        .items td:last-child, .items th:last-child { text-align: center; width: 90px; }
        .notes { border: 1px solid #9ca3af; min-height: 48px; padding: 8px; margin-top: 16px; }
        .signatures { width: 100%; margin-top: 75px; text-align: center; }
        .signatures td { width: 50%; padding: 0 24px; vertical-align: bottom; }
        .line { border-top: 1px solid #111827; padding-top: 6px; font-weight: bold; }
        .footer { margin-top: 22px; text-align: right; font-size: 8px; color: #4b5563; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td style="width: 120px;"><img class="logo" src="{{ public_path('assets/img/logo-Reporte-Honorarios-izquierdo.jpg') }}" alt="Esperanzarte"></td>
            <td>
                <div class="title">FORMATO DE ENTREGA DE MATERIAL</div>
                <div class="subtitle">INSTITUTO MUNICIPAL DE ARTE Y CULTURA DE CELAYA<br>Centros Culturales Esperanzarte</div>
            </td>
            <td style="width: 120px; text-align: right;"><strong>FOLIO</strong><br>{{ $entrega->folio }}</td>
        </tr>
    </table>

    <table class="meta">
        <tr>
            <td><span class="label">DOCENTE:</span> {{ $docente->nombre }} {{ $docente->paterno }} {{ $docente->materno }}</td>
            <td><span class="label">FOLIO DOCENTE:</span> {{ $docente->folio }}</td>
        </tr>
        <tr>
            <td><span class="label">FECHA DE ENTREGA:</span> {{ \Carbon\Carbon::parse($entrega->fecha)->format('d/m/Y') }}</td>
            <td><span class="label">HORA DE ENTREGA:</span> {{ $entrega->hora }}</td>
        </tr>
    </table>

    <table class="items">
        <thead><tr><th>MATERIAL ENTREGADO</th><th>MARCA</th><th>UNIDAD</th><th>CANTIDAD</th></tr></thead>
        <tbody>
            @foreach($materialesEntregados as $materialEntregado)
                <tr>
                    <td>{{ $materialEntregado->materialRegistro?->material ?? 'Material no disponible' }}</td>
                    <td>{{ $materialEntregado->materialRegistro?->marca }}</td>
                    <td>{{ $materialEntregado->materialRegistro?->unidad_medida }}</td>
                    <td>{{ $materialEntregado->cantidad }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="notes"><span class="label">OBSERVACIONES:</span><br>{{ $entrega->observaciones ?: 'Sin observaciones.' }}</div>

    <table class="signatures">
        <tr>
            <td><div class="line">ENTREGA</div><div>Nombre y firma</div></td>
            <td><div class="line">RECIBE</div><div>{{ $docente->nombre }} {{ $docente->paterno }} {{ $docente->materno }}</div></td>
        </tr>
    </table>

    <div class="footer">Formato generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i:s') }}.</div>
</body>
</html>