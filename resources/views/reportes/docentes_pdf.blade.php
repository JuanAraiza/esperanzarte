<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Oficio de Comprobacion de Talleres</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
            line-height: 1.35;
            margin: 26px;
        }

        .header-title {
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .header-wrap {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 8px;
        }

        .logo-wrap {
            text-align: center;
            margin-bottom: 6px;
        }

        .logo-wrap img {
            height: 56px;
            width: auto;
        }

        .header-subtitle {
            text-align: center;
            font-size: 12px;
            margin-bottom: 14px;
        }

        .folio {
            text-align: right;
            margin-bottom: 14px;
        }

        .block {
            margin-bottom: 10px;
        }

        .strong {
            font-weight: 700;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 14px 0;
        }

        .summary td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
        }

        .summary .label {
            background: #f3f4f6;
            width: 42%;
            font-weight: 700;
        }

        table.detail {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.detail th,
        table.detail td {
            border: 1px solid #d1d5db;
            padding: 6px 7px;
            font-size: 11px;
            vertical-align: top;
        }

        table.detail th {
            background: #111827;
            color: #ffffff;
            text-align: left;
        }

        .empty {
            text-align: center;
            padding: 10px;
        }

        .signatures {
            width: 100%;
            margin-top: 34px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
            display: inline-block;
            vertical-align: top;
        }

        .signature-line {
            border-top: 1px solid #111827;
            margin: 48px 20px 6px 20px;
            height: 1px;
        }

        .small {
            font-size: 10px;
            color: #4b5563;
        }

        .firmante {
            font-size: 11px;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <div class="header-wrap">
        @if (!empty($logoInstitucional) && file_exists($logoInstitucional))
            <div class="logo-wrap">
                <img src="{{ $logoInstitucional }}" alt="Logo institucional">
            </div>
        @endif

        <div class="header-title">{{ $nombreInstitucion }}</div>
    </div>

    <div class="folio">
        <div><span class="strong">Fecha de emision:</span> {{ $fechaEmision->format('d/m/Y H:i') }}</div>
    </div>

    <div class="header-title">OFICIO DE COMPROBACION DE TALLERES IMPARTIDOS</div>
    <div class="header-subtitle">Reporte de evidencia para validacion y pago de talleres</div>

    <div class="block">
        Por medio del presente se hace constar que el/la docente
        <span class="strong">{{ trim(($docenteSeleccionado->nombre ?? '').' '.($docenteSeleccionado->paterno ?? '').' '.($docenteSeleccionado->materno ?? '')) }}</span>
        impartio talleres en el periodo comprendido del
        <span class="strong">{{ \Carbon\Carbon::parse($filtros['fecha_inicio'])->format('d/m/Y') }}</span>
        al
        <span class="strong">{{ \Carbon\Carbon::parse($filtros['fecha_fin'])->format('d/m/Y') }}</span>,
        conforme a los registros de asistencia capturados en el sistema.
    </div>

    <table class="summary">
        <tr>
            <td class="label">Total de sesiones impartidas</td>
            <td>{{ $resumen['sesiones'] }}</td>
        </tr>
        <tr>
            <td class="label">Total de talleres distintos</td>
            <td>{{ $resumen['talleres_distintos'] }}</td>
        </tr>
        <tr>
            <td class="label">Total de asistencias de alumnos</td>
            <td>{{ $resumen['alumnos_asistentes'] }}</td>
        </tr>
    </table>

    <table class="detail">
        <thead>
            <tr>
                <th style="width: 16%;">Fecha</th>
                <th style="width: 30%;">Taller</th>
                <th style="width: 30%;">Centro</th>
                <th style="width: 24%;">Alumnos que asistieron</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reporte as $fila)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($fila->dia)->format('d/m/Y') }}</td>
                    <td>{{ $fila->taller_nombre }}</td>
                    <td>{{ $fila->centro_nombre }}</td>
                    <td>{{ $fila->total_alumnos }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty">No existen asistencias registradas en el rango solicitado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="block" style="margin-top: 14px;">
        Se expide el presente oficio para los fines administrativos y de comprobacion que correspondan.
    </div>

    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="strong">Firma del Docente</div>
            <div class="firmante">{{ trim(($docenteSeleccionado->nombre ?? '').' '.($docenteSeleccionado->paterno ?? '').' '.($docenteSeleccionado->materno ?? '')) }}</div>
            <div class="small">Nombre y firma</div>
        </div>

        <div class="signature-box" style="float: right;">
            <div class="signature-line"></div>
            <div class="strong">Firma por {{ $nombreInstitucion }}</div>
            <div class="firmante">Representante institucional</div>
            <div class="small">Nombre, cargo y firma</div>
        </div>
    </div>
</body>
</html>
