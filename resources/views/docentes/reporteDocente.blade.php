<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de honorarios</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #222;
            background: white;
        }
        
        .container {
            padding: 22px 42px 18px;
            max-width: 816px;
            margin: 0 auto;
        }
        
        .top-logos {
            width: 100%;
            height: 74px;
            margin-bottom: 8px;
        }
        .logo-left {
            float: left;
            width: 185px;
            height: 68px;
            object-fit: contain;
        }
        .logo-right {
            float: right;
            width: 185px;
            height: 68px;
            object-fit: contain;
        }
        .institution {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            line-height: 1.9;
        }
        .report-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 23px 0 26px;
        }
        .docente-line {
            /* margin: 0 4px 17px; */
           
            font-size: 12px;
        }
        .docente-line strong {
            font-weight: bold;
            /* margin-right: 30px; */
            
        }
        .talleres-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9px;
            margin-bottom: 28px;
        }
        .talleres-table th {
            height: 38px;
            padding: 5px 4px;
            background: #f58228;
            color: white;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        .talleres-table td {
            height: 32px;
            padding: 5px 4px;
            border-bottom: 1px solid #c8a77e;
            vertical-align: middle;
            color: #555;
        }
        .talleres-table th:nth-child(1), .talleres-table td:nth-child(1) { width: 17%; }
        .talleres-table th:nth-child(2), .talleres-table td:nth-child(2) { width: 22%; }
        .talleres-table th:nth-child(3), .talleres-table td:nth-child(3) { width: 29%; }
        .talleres-table th:nth-child(4), .talleres-table td:nth-child(4) { width: 16%; text-align: center; }
        .talleres-table th:nth-child(5), .talleres-table td:nth-child(5) { width: 16%; }
        .empty-row { text-align: center; color: #777 !important; height: 48px !important; }
        .total-row {
            width: 100%;
            height: 43px;
            border: 1px solid #222;
            margin-bottom: 29px;
            font-size: 12px;
            font-weight: bold;
        }
        .total-label {
            display: inline-block;
            width: 31%;
            padding: 13px 8px;
            border-right: 1px solid #222;
        }
        .total-value {
            display: inline-block;
            width: 68%;
            text-align: center;
            font-size: 13px;
        }
        .honorarios-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 10px;
            margin-bottom: 25px;
        }
        .honorarios-table th {
            background: #f5ad82;
            color: #9e1717;
            padding: 5px 3px;
            height: 40px;
            font-weight: bold;
            vertical-align: middle;
            text-align: center;
        }
        .honorarios-table td {
            height: 25px;
            padding: 4px 3px;
            text-align: center;
            color: #555;
        }
        .honorarios-table th:nth-child(1), .honorarios-table td:nth-child(1) { width: 14%; }
        .honorarios-table th:nth-child(2), .honorarios-table td:nth-child(2) { width: 17%; }
        .honorarios-table th:nth-child(3), .honorarios-table td:nth-child(3) { width: 14%; }
        .honorarios-table th:nth-child(4), .honorarios-table td:nth-child(4) { width: 15%; }
        .honorarios-table th:nth-child(5), .honorarios-table td:nth-child(5) { width: 14%; }
        .honorarios-table th:nth-child(6), .honorarios-table td:nth-child(6) { width: 14%; }
        .honorarios-table th:nth-child(7), .honorarios-table td:nth-child(7) { width: 12%; }
        .description {
            text-align: center;
            font-size: 9px;
            line-height: 1.45;
            margin: 0 5px 43px;
        }
        .signatures {
            width: 100%;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
        }
        .signature {
            display: inline-block;
            width: 43%;
            margin: 0 3%;
            padding-top: 9px;
            border-top: 1px solid #222;
            line-height: 1.7;
        }
        .signature-bottom {
            width: 62%;
            margin: 47px auto 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-logos">
            <img src="{{ public_path('assets/img/logo-Reporte-Honorarios-izquierdo.jpg') }}" alt="Esperanzarte" class="logo-left">
            <img src="{{ public_path('assets/img/logo-Reporte-Honorarios-derecho.jpg') }}" alt="Esperanzarte" class="logo-right">
        </div>
        <div class="institution">
            <div>GOBIERNO MUNICIPAL DE CELAYA 2024 - 2027<br>INSTITUTO MUNICIPAL DE ARTE Y CULTURA DE CELAYA</div>
        </div>
        <div class="report-title">REPORTE DE TALLERES EN CENTROS CULTURALES ESPERANZARTE</div>
        <div class="docente-line">
            <strong>FECHA EMISION:</strong> {{ date('d/m/Y') }} 
            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <strong>NOMBRE COMPLETO:</strong> {{ $docente->nombre }} {{ $docente->paterno }} {{ $docente->materno }} 
            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <strong>CORREO ELECTRÓNICO:</strong> {{ $docente->email ?? 'N/A' }}
            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <strong>TELÉFONO:</strong> {{ $docente->telefono ?? 'N/A' }}
            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <strong>RFC:</strong> {{ $docente->rfc ?? 'N/A' }}
            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <strong>CURP:</strong> {{ $docente->curp ?? 'N/A' }}
            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <strong>REGIMEN:</strong> {{ $docente->regimen ?? 'N/A' }}
            
        </div>
        <br>
        <table class="talleres-table">
            <thead>
                <tr>
                    <th>TALLER</th>
                    <th>CENTRO CULTURAL<br>ESPERANZARTE</th>
                    <th>HORARIO</th>
                    <th>TOTAL DE HORAS</th>
                    <th>OBSERVACIONES</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reporteData as $item)
                    @php
                        $horariosTexto = $item['horarios']->map(function ($horario) {
                            $dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                            return ($horario->dia ?? 'N/A').' de '.substr($horario->hora_inicio, 0, 5).' a '.substr($horario->hora_fin, 0, 5).' hrs.';
                        })->implode('<br>');
                    @endphp
                    <tr>
                        <td>{{ $item['taller_nombre'] }}</td>
                        <td>{{ $item['centro_nombre'] }}</td>
                        <td>{!! $horariosTexto ?: 'Sin horario registrado' !!}</td>
                        <td>{{ $item['horas'] }}</td>
                        <td>{{ $observaciones->get($item['taller_id'], '') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-row">No se registraron talleres en el periodo seleccionado.</td></tr>
                @endforelse
            </tbody>
        </table>
         <table  cellspacing="0" cellpadding="0" style="width:700px;">
                <tr>
                    <td class="total-label" style="text-align: center; border: 1px solid #000; width:200px;">HORAS TOTALES</td>
                    <td class="total-label" style="text-align: center; border: 1px solid #000; width:462px;">{{ $totalHoras }}</td>
                </tr>
        </table>
        <?php /*
        <div class="total-row">
            <span class="total-label">HORAS TOTALES</span>
            <span class="total-value">{{ $totalHoras }}</span>
        </div>
 */ ?>
        <table class="honorarios-table">
            <thead>
                <tr>
                    <th>PAGO POR<br>HORA</th><th>HONORARIOS</th><th>IVA 16%</th><th>SUBTOTAL</th><th>ISR 1.25 %</th><th>CEDULAR 2%</th><th>TOTAL A<br>PAGAR</th>
                </tr>
            </thead>
            <tbody><tr>
                @php
                    // RESICO: ISR 1.25%, CEDULAR 2%. Otros: ISR 10%, CEDULAR 2.5%
                    $pagoPorHora = 160.00;
                    $honorarios = $totalHoras * $pagoPorHora;
                    $iva = $honorarios * 0.16;
                    $subtotal = $honorarios + $iva;
                    if($docente->regimen == 'RESICO'){
                        $isr = $subtotal * 0.0125;
                        $cedular = $subtotal * 0.02;
                    }else{
                        $isr = $subtotal * 0.1;
                        $cedular = $subtotal * 0.025;
                    }
                    

                    $subtotalPagar = $subtotal - $isr - $cedular;

                    $ivasubtotal= $subtotalPagar * 0.16;

                    $totalPagar = $subtotalPagar + $ivasubtotal;
                @endphp 
                <td>$ 160.00</td><td>${{ number_format($honorarios, 2) }}</td><td>${{ number_format($iva, 2) }}</td><td>${{ number_format($subtotal, 2) }}</td><td>${{ number_format($isr, 2) }}</td><td>${{ number_format($cedular, 2) }}</td><td>${{ number_format($totalPagar, 2) }}</td>
            </tr></tbody>
        </table>
        <div class="description">
            <strong>DESCRIPCIÓN CFDI:</strong> Impartición de clases artístico - culturales en los Centros Culturales Esperanzarte del {{ \Carbon\Carbon::parse($fechaInicio)->format('d') }} al {{ \Carbon\Carbon::parse($fechaFin)->format('d') }} de {{ \Carbon\Carbon::parse($fechaFin)->locale('es')->translatedFormat('F') }} del {{ \Carbon\Carbon::parse($fechaFin)->format('Y') }}. Una vez elaborada la factura, enviar los archivos PDF y XML al correo: <strong>coordinacion.esperanzarte@gmail.com</strong>
        </div>
        <div class="signatures">
            <div class="signature">{{ $docente->nombre }} {{ $docente->paterno }} {{ $docente->materno }}<br>DOCENTE</div>
            <div class="signature">LIC. CANDY MIRARI CORDERO MARIN<br>COORDINACIÓN DE CENTROS CULTURALES<br>ESPERANZARTE</div>
            <div class="signature signature-bottom">LIC. FERNANDO KRISSANT HERNÁNDEZ AGUILAR<br>COORDINACIÓN DE CULTURAS POPULARES Y PRESERVACIÓN</div>
        </div>
       
    </div>
</body>
</html>
