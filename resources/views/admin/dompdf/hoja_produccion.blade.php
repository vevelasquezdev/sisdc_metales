<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    
    <title>Orden de Producción {{ $numero ?? '' }}</title>
    <style>
        /* ── Reset ──────────────────────────────────────────────────────── */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            size: 148mm 210mm landscape;   /* A5 landscape */
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            /* font-family: "DejaVu Sans", "Arial Unicode MS", sans-serif; */
            font-size: 7.5pt;
            color: #141414;
            background: #fff;
            width: 100%;
        }
        

        /* ── Franjas rojas ──────────────────────────────────────────────── */
        .franja-top {
            background-color: #CC1E1E;
            height: 7px;
            width: 100%;
        }

        /*
         * .franja-bottom es el wrapper fijo al fondo que agrupa:
         *   1) la tabla de direcciones  (fondo blanco)
         *   2) la tira roja con créditos del sistema
         *
         * line-height: normal → evita que un valor fijo aplaste las filas
         *   internas de la tabla de direcciones.
         * background: #fff    → fondo blanco visible detrás de la tabla.
         */
        .franja-bottom {
            position: fixed;
            bottom: 0; left: 0;
            width: 100%;
            height: 49px;
            color: #141414;
            font-size: 9pt;
            line-height: normal;
            margin-top: 4px;
            border-top: 1px solid #e0e0e0;
            background: #fff;
        }

        /* ── HEADER ─────────────────────────────────────────────────────── */
        .header-wrap {
            padding: 5px 10px 4px 10px;
            border-bottom: 1px solid #ccc;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: middle;
            padding: 0;
        }
        .titulo-op {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 1px;
            color: #141414;
            text-align: center;
        }

        /* ── SECTION BAR ────────────────────────────────────────────────── */
        .section-bar {
            background-color: #1a1a1a;
            color: #fff;
            font-size: 7.5pt;
            font-weight: bold;
            padding: 3px 8px;
            letter-spacing: 0.4px;
        }

        /* ── DATOS SUPERIORES ───────────────────────────────────────────── */
        .datos-top-wrap {
            padding: 0 10px;
            margin-top: 4px;
        }
        .datos-top-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ccc;
            table-layout: fixed;
        }
        .datos-top-table td {
            padding: 3px 6px;
            font-size: 7.5pt;
            vertical-align: middle;
        }
        .lbl {
            font-weight: bold;
            color: #141414;
            background-color: #f2f2f2;
            white-space: nowrap;
        }
        .val {
            border-bottom: 1px solid #ccc;
            color: #141414;
        }
        .cell-border-right {
            border-right: 1px solid #ccc;
        }

        /* Checkboxes de material */
        .chk-box {
            font-family: "DejaVu Sans", sans-serif; 
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #555;
            vertical-align: middle;
            margin-right: 2px;
            text-align: center;
           
            /* position: absolute; */
            /* top: 180px; left: 150px;  */
        }
       
        .chk-label {
            font-size: 6.5pt;
            vertical-align: middle;
            margin-right: 5px;
        }

        /* ── TABLA DE PROCESO ───────────────────────────────────────────── */
        .proceso-wrap {
            padding: 0 10px;
            margin-top: 4px;
        }
        .proceso-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ccc;
            table-layout: fixed;
        }
        .proceso-table thead th {
            background-color: #1a1a1a;
            color: #fff;
            font-size: 7.5pt;
            font-weight: bold;
            padding: 4px 5px;
            text-align: center;
            border-right: 1px solid #555;
        }
        .proceso-table thead th:last-child {
            border-right: none;
        }
        .proceso-table tbody tr td {
            font-size: 7pt;
            padding: 3px 5px;
            border-bottom: 1px solid #e0e0e0;
            border-right: 1px solid #e0e0e0;
            vertical-align: middle;
            color: #141414;
        }
        .proceso-table tbody tr td:last-child {
            border-right: none;
        }
        .proceso-table tbody tr:nth-child(even) td {
            background-color: #fafafa;
        }

        /* columnas de la tabla proceso */
        .col-ruta     { width: 22%; }
        .col-chk      { width: 5%;  text-align: center; }
        .col-resp     { width: 20%; }
        .col-fecha    { width: 18%; text-align: center; }
        .col-obs-proc { width: 35%; }

        .ruta-nombre {
            font-weight: bold;
            font-size: 7pt;
        }

        /* ── PIE ────────────────────────────────────────────────────────── */
        .footer-dir-table {
            width: 100%;
            border-collapse: collapse;
            padding: 3px 10px;
        }
        .footer-dir-table td {
            font-size: 6.5pt;
            color: #141414;
            vertical-align: middle;
        }
        .web-label {
            font-size: 7.5pt;
            font-weight: bold;
            color: #fff;
            background-color: #CC1E1E;
            padding: 2px 6px;
            text-align: center;
        }

        /* ── Utilidades ─────────────────────────────────────────────────── */
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .text-bold   { font-weight: bold; }
    </style>
</head>
<body>

    {{-- ── FRANJA SUPERIOR ────────────────────────────────────────────── --}}
    <div class="franja-top"></div>

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="header-wrap" style="padding-left:22px; padding-right:22px;">
        <table class="header-table">
            <tr>
                {{-- Logo --}}
                <td style="width:22%; vertical-align:middle;">
                    <img src="{{ asset('smartadmin/dist/img/logo_png.png') }}" width="110" style="padding-left:4px;">
                </td>
                {{-- Título --}}
                <td style="width:56%; vertical-align:middle;">
                    <div class="titulo-op">ORDEN DE PRODUCCIÓN</div>
                </td>
                {{-- N° + Fecha --}}
                <td style="width:22%; vertical-align:middle;">
                    <table width="100%" border="0" cellpadding="1" cellspacing="0">
                        <tr>
                            <td class="lbl" style="font-size:7pt; border:1px solid #ccc; padding:2px 4px;">N°</td>
                            <td style="font-size:9pt; font-weight:bold; color:#CC1E1E; border:1px solid #ccc; border-left:none; padding:2px 5px;">
                                {{ $codigo }}
                            </td>
                        </tr>
                        <tr>
                            <td class="lbl" style="font-size:7pt; border:1px solid #ccc; border-top:none; padding:2px 4px;">Fecha Imp.:</td>
                            <td style="font-size:7pt; border:1px solid #ccc; border-top:none; border-left:none; padding:2px 5px;">
                                {{ date('d/m/Y') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── DATOS CLIENTE ───────────────────────────────────────────────── --}}
    <div class="datos-top-wrap" style="padding-left:22px; padding-right:22px;">
        <table class="datos-top-table">
            <colgroup>
                <col style="width:14%;">
                <col style="width:40%;">
                <col style="width:10%;">
                <col style="width:13%;">
                <col style="width:10%;">
                <col style="width:13%;">
            </colgroup>
            <tr>
                <td class="lbl cell-border-right">NOMBRE DE CLIENTE:</td>
                <td class="val cell-border-right" colspan="3">{{ $cliente ?? '' }}</td>
                <td class="lbl cell-border-right">TELÉFONO:</td>
                <td class="val">{{ $telefono ?? '' }}</td>
            </tr>
            <tr>
                <td class="lbl cell-border-right">CANTIDAD:</td>
                <td class="val cell-border-right" colspan="3">{{ $cantidad ?? '' }}</td>
                <td class="lbl cell-border-right">APROBADO<br>CLIENTE:</td>
                <td class="val" style="text-align:center;">
                    <span class="chk-box">@if(!empty($aprobado_cliente)) ✓ @endif</span>
                </td>
            </tr>
            <tr>
                <td class="lbl cell-border-right">DETALLE:</td>
                <td class="val" colspan="5" style="border-bottom:1px solid #ccc;">
                    {{ $detalle ?? '' }}&nbsp;
                </td>
            </tr>
            <tr>
                <td style="height:3px;" colspan="6"></td>
            </tr>
            {{-- ── Las 3 fechas juntas, en orden ──────────────────────────── --}}
            <tr>
                <td class="lbl cell-border-right">FECHA INGRESO:</td>
                <td class="val cell-border-right">
                    &nbsp;&nbsp;{{ $fecha_ingreso ?? '___ / ___ / 26' }}
                </td>
                <td class="lbl cell-border-right">FECHA ENTREGA<br>COMPROMETIDA:</td>
                <td class="val cell-border-right" style="font-weight:bold; color:#CC1E1E;">
                    {{ $fecha_entrega ?? '___ / ___ / 26' }}
                </td>
                <td class="lbl cell-border-right">HORA ENTREGA:</td>
                <td class="val">{{ $hora_entrega ?? '___ : ___ am/pm' }}</td>
            </tr>
            <tr>
                <td class="lbl cell-border-right">MATERIAL:</td>
                <td colspan="5" style="padding:3px 6px;">
                    <span class="chk-box">X</span>
                    <span class="chk-label">{{ $material ?? '' }}</span>
                </td>
            </tr>
            <tr>
                <td class="lbl cell-border-right">OBSERVACIONES:</td>
                <td colspan="5" style="padding:3px 6px; border-top:1px solid #ccc;">{{ $observaciones_generales ?? '' }}&nbsp;</td>
            </tr>
        </table>
    </div>

    {{-- ── TABLA RUTA DE PROCESO ───────────────────────────────────────── --}}
    <div class="proceso-wrap" style="padding-left:22px; padding-right:22px; margin-top:4px;">
        <table class="proceso-table">
            <colgroup>
                <col class="col-ruta">
                <col class="col-chk">
                <col class="col-resp">
                <col class="col-fecha">
                <col class="col-obs-proc">
            </colgroup>
            <thead>
                <tr>
                    <th class="col-ruta  text-center">RUTA DE PROCESO</th>
                    <th class="col-chk   text-center"></th>
                    <th class="col-resp  text-center">RESPONSABLE</th>
                    <th class="col-fecha text-center">FECHA</th>
                    <th class="col-obs-proc text-center">OBSERVACIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proceso_data as $fila)
                    <tr>
                        <td><span class="ruta-nombre">{{ $fila['nombre'] }}</span></td>
                        <td class="text-center">
                            <span class="chk-box"></span>
                        </td>
                        <td>{{ $fila['responsable'] }}</td>
                        <td class="text-center" style="font-weight:bold;">
                            {{ $fila['fecha'] ?? "__/__/2026" }}
                        </td>
                        <td style="font-size:6.5pt;">{{ $fila['observaciones'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ── PIE + FRANJA: wrapper fijo al fondo ───────────────────────── --}}
    {{--
        Un solo div position:fixed agrupa:
          · tabla de direcciones  (fondo blanco)
          · tira roja con créditos del sistema
    --}}
    <div class="franja-bottom">        
        <table class="footer-dir-table" style="padding:3px 22px;">
            <tr>
                <td style="width:78%;">
                    <table border="0" cellpadding="0" cellspacing="2">
                        <tr>
                            <td style="padding-right:3px; vertical-align:top;">
                                <div style="width:7px; height:7px; background:#CC1E1E; border-radius:50% 50% 50% 0; display:inline-block;"></div>
                            </td>
                            <td style="font-size:6.5pt;">
                                <b>Sede 1:</b> Urb. San Lorenzo, Calle Malecón Zolezzi 403-A — Mariano Melgar
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-right:3px; vertical-align:top;">
                                <div style="width:7px; height:7px; background:#CC1E1E; border-radius:50% 50% 50% 0; display:inline-block;"></div>
                            </td>
                            <td style="font-size:6.5pt;">
                                <b>Sede 2:</b> Av. Argentina Mz E Lote 7B — Cerro Colorado
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:22%; text-align:right; vertical-align:middle; padding-right:22px;">
                    <div class="web-label">www.dcmetalesperu.pe</div>
                </td>
            </tr>
        </table>

        {{-- Tira roja inferior --}}
        <div style="background-color:#CC1E1E; color:#fff; font-size:7pt; text-align:center; line-height:15px;vertical-align:middle;">
            Sistemas para Empresas &nbsp;|&nbsp; WebSoftAqp &nbsp; Cel: 927245347
        </div>

    </div>{{-- /franja-bottom --}}

</body>
</html>