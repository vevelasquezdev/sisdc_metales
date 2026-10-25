<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Cotización {{ $codigo ?? '' }}</title>
    <style>
        /* ── Reset base ─────────────────────────────────────────────────── */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            color: #141414;
            background: #fff;
            width: 100%;          
        }

        /* ── Franja roja superior ───────────────────────────────────────── */
        .franja-top {
            background-color: #CC1E1E;
            height: 10px;
            width: 100%;
            margin-bottom: 0;
        }

        /* ── HEADER: logo + número cotización ──────────────────────────── */
        .header-wrap {
            width: 100%;
            padding: 10px 14px 6px 14px;
            border-bottom: 1px solid #ccc;
            height: 8%;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
            padding: 0;
        }
        .header_image {
            margin-bottom: 0px;
            padding-left:30px; 
            padding-top:20px
        }        
        .logo-subtitle {
            font-size: 10pt;
            color: #555;
            margin-top: 2px;
            line-height: 1.5;
        }       

        /* ── Fondo diamantes (decorativo header) ────────────────────────── */
        /* dompdf no soporta background-image con SVG inline;
           se omite el patrón y se conserva el fondo blanco limpio */

        /* ── Sección datos cliente / asesor ─────────────────────────────── */
        .section-bar {
            background-color: #1a1a1a;
            color: #fff;
            font-size: 8.5pt;
            font-weight: bold;
            padding: 5px 10px;
            margin: 8px 14px 0 14px;
            letter-spacing: 0.4px;
        }

        .datos-wrap {
            width: 100%;
            padding: 0 14px;
            margin-top: 6px;
        }
        .datos-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ccc; 
            width:100%; table-layout:fixed;           
        }
        .datos-table td {
            padding: 4px 8px;
            vertical-align: top;
            font-size: 8.5pt;
        }
        .datos-table .label {
            font-weight: bold;
            width: 70px;
            color: #141414;
        }
        .datos-table .valor {
            border-bottom: 1px solid #ccc;
            min-width: 140px;
            color: #141414;
        }
        .datos-table .divider-col {
            width: 1px;
            background-color: #ccc;
            padding: 0;
        }
        .datos-table .spacer-row td {
            height: 4px;
        }

        /* ── Tabla productos ─────────────────────────────────────────────── */
        .productos-wrap {
            padding: 0 14px;
            margin-top: 6px;
        }
        .productos-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-top: 0;
            border: 1px solid #ccc;
        }
        .productos-table thead tr th {
            background-color: #fff;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            font-size: 8.5pt;
            font-weight: bold;
            color: #141414;
            padding: 5px 6px;
            text-align: center;
            overflow: hidden;
        }
        .productos-table thead tr th.desc-col {
            text-align: left;
        }
        .productos-table tbody tr td {
            font-size: 8.5pt;
            color: #141414;
            padding: 5px 6px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
            overflow: hidden;
        }
        .productos-table tbody tr td.item-num {
            text-align: center;
            color: #333;
            border-right: 1px solid #e0e0e0;
        }
        .productos-table tbody tr td.desc {
            text-align: left;
        }
        .productos-table tbody tr td.cant {
            text-align: center;
            border-left: 1px solid #e0e0e0;
        }
        .productos-table tbody tr td.precio {
            text-align: center;
            border-left: 1px solid #e0e0e0;
        }
        .productos-table tbody tr td.subtotal {
            text-align: right;
            border-left: 1px solid #e0e0e0;
            padding-right: 10px;
        }
        /* CLiente Asesor  */
        .col-clie1      { width: 9%;  }
        .col-value1     { width: 38%; }
        .col-espacio1   { width: 2%; }
        .col-user1      { width: 9%; }
        .col-valuser1   { width: 38%; }
        .col-espadiofin { width: 2%; }

        /* Descripcion del producto */
        .col-item    { width: 6%;  }
        .col-desc    { width: 56%; }
        .col-cant    { width: 10%; }
        .col-precio  { width: 13%; }
        .col-sub     { width: 13%; }

        /* ── Imagen referencial + Totales ─────────────────────────────── */
        .bottom-section {
            width: 100%;
            padding: 8px 14px 0 14px;
            height: 310px;
        }
        .bottom-table {
            width: 100%;
            border-collapse: collapse;
        }
        .bottom-table td {
            vertical-align: top;
            padding: 0;
        }

        .img-ref-label {
            font-size: 8pt;
            font-weight: bold;
            color: #CC1E1E;
            margin-bottom: 4px;
        }
        .img-ref-box {
            border: 1px solid #ccc;
            width: 100%;
            height: 200px;
            background-color: #fafafa;
            text-align: center;
            vertical-align: middle;
            color: #aaa;
            font-size: 7.5pt;
            font-style: italic;
        }
        .img-ref-box img {
            max-width: 100%;
            max-height: 195px;
            
        }

        /* Especificaciones / Observaciones */
        .obs-label {
            font-size: 8pt;
            font-weight: bold;
            color: #000000;
            margin-bottom: 4px;
        }
        .obs-box {
            border: 1px solid #ccc;
            min-height: 60px;
            padding: 6px;
            font-size: 8pt;
            color: #141414;
            background: #fff;
        }

        /* ── Totales ─────────────────────────────────────────────────────── */
        .totales-wrap {
            margin-top: 10px;
        }
        .totales-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totales-table td {
            font-size: 8.5pt;
            padding: 3px 6px;
        }
        .totales-table .tot-label {
            text-align: left;
            color: #141414;
            width: 60%;
        }
        .totales-table .tot-prefix {
            text-align: right;
            width: 15%;
            color: #141414;
            padding-right: 2px;
        }
        .totales-table .tot-valor {
            text-align: right;
            width: 25%;
            color: #141414;
            border-bottom: 1px solid #e0e0e0;
            padding-right: 4px;
        }
        .totales-table tr.total-row td {
            font-weight: bold;
            font-size: 10pt;
            background-color: #1a1a1a;
            color: #fff;
            padding: 5px 6px;
        }
        .totales-table tr.total-row .tot-valor {
            color: #fff;
            border-bottom: none;
        }
        .totales-table tr.igv-row td {
            border-bottom: 1px solid #ccc;
        }

        /* ── Línea separadora horizontal ─────────────────────────────────── */
        .hr-line {
            border: none;
            border-top: 1.5px solid #CC1E1E;
            margin: 10px 14px 0 14px;
        }

        /* ── Condiciones + Medios de pago ───────────────────────────────── */
        .footer-section {
            padding: 6px 14px 0 14px;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-table td {
            vertical-align: top;
            padding: 0 6px 0 0;
        }
        .footer-bar {
            background-color: #1a1a1a;
            color: #fff;
            font-size: 8pt;
            font-weight: bold;
            padding: 5px 8px;
            margin-bottom: 5px;
            letter-spacing: 0.3px;
        }
        .footer-content {
            font-size: 8pt;
            color: #141414;
            line-height: 1.6;
        }
        .footer-content b {
            font-weight: bold;
        }

        /* ── Términos y condiciones ──────────────────────────────────────── */
        .terminos-section {
            padding: 6px 14px 0 14px;
        }
        .terminos-title {
            font-size: 8pt;
            font-weight: bold;
            color: #141414;
            margin-bottom: 3px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 2px;
        }
        .terminos-list {
            font-size: 7pt;
            color: #333;
            line-height: 1.6;
        }
        .terminos-list p {
            margin-bottom: 1px;
        }

        /* ── Pie de página ───────────────────────────────────────────────── */
        .page-footer {
            margin-top: 8px;
            background-color: #fff;
            border-top: 1px solid #e0e0e0;
            padding: 6px 14px;
        }
        .footer-direcciones-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-direcciones-table td {
            vertical-align: middle;
            font-size: 7.5pt;
            color: #141414;
        }
        .pin-icon {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #CC1E1E;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            margin-right: 3px;
        }
        .web-label {
            font-size: 10pt;
            font-weight: bold;
            color: #fff;
            background-color: #CC1E1E;
            padding: 3px 8px;
            text-align: center;
        }

        .franja-bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #CC1E1E;
            height: 17px;
            color: #fff;
            font-size: 12px;
            line-height: 15px;
        }

        /* ── Utilidades ──────────────────────────────────────────────────── */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .w-50 { width: 50%; }
        .w-55 { width: 55%; }
        .w-45 { width: 45%; }
        .w-60 { width: 60%; }
        .w-40 { width: 40%; }
        .pr-8 { padding-right: 8px; }
        .pl-8 { padding-left: 8px; }
    </style>
</head>
<body>

    {{-- ── FRANJA ROJA SUPERIOR ──────────────────────────────────────────── --}}
    <div class="franja-top"></div>

    {{-- ── HEADER: Logo + Nº Cotización ─────────────────────────────────── --}}
    <div class="header-wrap">
        <table class="header-table">
            <tr>
                {{-- Logo DC Metales --}}
                <td class="w-50" style="vertical-align:bottom;">
                    
                    <img class="header_image" src="{{ asset('smartadmin/dist/img/logo_png.png') }}" width="200">
                 
                    <div  style="position: absolute; left: 45px; top: 93px; font-size: 10pt; color:#005AE6; font-weight:bold;">www.dcmetalesperu.pe</div>
                </td>

                {{-- Número de cotización --}}
                <td class="w-50" style="vertical-align:bottom;">
                    <div style="font-size:17pt; font-weight:bold; letter-spacing:0.2px; color:#141414;">
                        N° COTIZACI&Oacute;N:
                         <span style="font-size:17pt; font-weight:bold; color:#f81313;">
                            #{{ $codigo ?? '' }}
                        </span>
                    </div>
                   <div class="logo-subtitle">
                        DC METALES E.I.R.L<br>RUC: 20607329843
                    </div>
                </td>
            </tr>
        </table>
    </div>


    {{-- ── DATOS DEL CLIENTE + ASESOR ────────────────────────────────────── --}}
    <div class="section-bar">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="50%" style="color:#fff; font-weight:bold; font-size:8.5pt;">
                    &nbsp;Datos del Cliente
                </td>
                <td width="50%" style="color:#fff; font-weight:bold; font-size:8.5pt;">
                    &nbsp;Datos del Asesor
                </td>
            </tr>
        </table>
    </div>

    <div class="datos-wrap">
        <table class="datos-table" style="width:100%; table-layout:fixed;">
            <colgroup>
                <col style="width:9%;">
                <col style="width:38%;">
                <col style="width:2%;">
                <col style="width:9%;">
                <col style="width:38%;">
                <col style="width:2%;">
            </colgroup>            
            <tr class="spacer-row"><td colspan="6" style="height:3px;"></td></tr>          
            <tr>
                @if ($ruc!="")
                    <td class="col-clie1 label">Empresa:</td>
                    <td class="col-value1 valor" >{{ $empresa ?? '' }}</td>
                @else
                    <td class="col-clie1 label">Cliente:</td>
                    <td class="col-value1 valor" >{{ $contacto ?? '' }}</td>
                @endif
                {{-- <td class="col-clie1 label">Empresa:</td>
                <td class="col-value1 valor" >{{ $empresa ?? '' }}</td> --}}
                <td class="col-espacio1" >&nbsp;</td>
                <td class="col-user1 label">Nombre:</td>
                <td class="col-valuser1 valor">{{ $asesor_nombre ?? '' }}</td>
                <td class="col-espadiofin">&nbsp;</td>
            </tr>
            <tr>
                @if ($ruc!="")
                    <td class="label">RUC:</td>
                    <td class="valor">{{ $ruc ?? '' }}</td>
                @else
                    <td class="label">Tel&eacute;fono:</td>
                    <td class="valor">{{ $telefono_cliente ?? '' }}</td>
                @endif
                
                <td>&nbsp;</td>
                <td class="label">Tel&eacute;fono:</td>
                <td class="valor">{{ $asesor_telefono ?? '' }}</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                @if ($ruc!="")
                    <td class="label">Contacto:</td>
                    <td class="valor">{{ $contacto ?? '' }}</td>
                @else
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                @endif
                
                <td>&nbsp;</td>
                <td class="label">Correo:</td>
                <td class="valor" style="color:#005AE6;">{{ $asesor_email ?? '' }}</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                @if ($ruc!="")
                    <td class="label">Tel&eacute;fono:</td>
                    <td class="valor">{{ $telefono_cliente ?? '' }}</td>
                @else
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                @endif
                
                <td>&nbsp;</td>
                <td class="label">Fecha:</td>
                <td class="valor">{{ date('d-m-Y') }}</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class="label" >Proyecto:</td>
                <td class="valor" colspan="4">{{ $proyecto ?? '' }}</td>
                <td>&nbsp;</td>
            </tr>
            <tr class="spacer-row"><td colspan="6" style="height:3px;"></td></tr>
        </table>

    </div>

    {{-- ── DESCRIPCIÓN DEL SERVICIO / PRODUCTO ────────────────────────────── --}}
    <div class="section-bar" style="margin-top:10px;">
        &nbsp;DESCRIPCI&Oacute;N DEL SERVICIO / PRODUCTO
    </div>

    <div class="productos-wrap">
        <table class="productos-table" style="width:100%; table-layout:fixed;">
            <colgroup>
                <col style="width:6%;">
                <col style="width:56%;">
                <col style="width:10%;">
                <col style="width:13%;">
                <col style="width:13%;">
            </colgroup>
            <thead>
                <tr>
                    <th class="col-item text-center">Ítem</th>
                    <th class="col-desc desc-col">Descripci&oacute;n</th>
                    <th class="col-cant text-center">Cantidad</th>
                    <th class="col-precio text-center">P. Unitario</th>
                    <th class="col-sub text-right" style="padding-right:10px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="item-num">1</td>
                    <td class="desc">{{ $item1_descripcion ?? '' }}</td>
                    <td class="cant">{{ $item1_cantidad ?? '' }}</td>
                    <td class="precio">
                        @if(!empty($item1_precio))
                            {{ $simbolo }} {{ number_format($item1_precio, 2) }}
                        @endif
                    </td>
                    <td class="subtotal">
                        {{ $simbolo }}&nbsp;
                        @if(!empty($item1_cantidad) && !empty($item1_precio))
                            {{ number_format($item1_cantidad * $item1_precio, 2) }}
                        @else
                            &ndash;
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="item-num">2</td>
                    <td class="desc">{{ $item2_descripcion ?? '' }}</td>
                    <td class="cant">{{ $item2_cantidad ?? '' }}</td>
                    <td class="precio">
                        @if(!empty($item2_precio))
                            {{ $simbolo }} {{ number_format($item2_precio, 2) }}
                        @endif
                    </td>
                    <td class="subtotal">
                        {{ $simbolo }}&nbsp;
                        @if(!empty($item2_cantidad) && !empty($item2_precio))
                            {{ number_format($item2_cantidad * $item2_precio, 2) }}
                        @else
                            &ndash;
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="item-num">3</td>
                    <td class="desc">{{ $item3_descripcion ?? '' }}</td>
                    <td class="cant">{{ $item3_cantidad ?? '' }}</td>
                    <td class="precio">
                        @if(!empty($item3_precio))
                            {{ $simbolo }} {{ number_format($item3_precio, 2) }}
                        @endif
                    </td>
                    <td class="subtotal">
                        {{ $simbolo }}&nbsp;
                        @if(!empty($item3_cantidad) && !empty($item3_precio))
                            {{ number_format($item3_cantidad * $item3_precio, 2) }}
                        @else
                            &ndash;
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

   {{-- ── IMAGEN REFERENCIAL + ESPECIFICACIONES / TOTALES ───────────────── --}}
    <div class="section-bar" style="margin-top:10px;">
        IMAGEN REFERENCIAL
    </div>
    <div class="bottom-section"> 
        {{-- FILA 1: Imagen referencial al 100% del ancho --}}        
        <div class="img-ref-box" style="width:100%; height:200px;">
            @if(!empty($imagen_referencial))
                <img src="{{ asset($imagen_referencial) }}" alt="Imagen referencial">
            @else
                <br><br><br>Sin imagen
            @endif
        </div>       
        <table style="width:100%; border-collapse:collapse; margin-top:6px;">
            <tr>
                {{-- Especificaciones / Observaciones --}}
                <td style="width:50%; vertical-align:top; padding-right:6px;">
                    <div class="obs-label">ESPECIFICACIONES / OBSERVACIONES:</div>
                    <div class="obs-box" style="min-height:60px;">
                        {{ $observaciones ?? '' }}
                    </div>
                </td> 
                {{-- Totales --}}
                <td style="width:50%; vertical-align:top; padding-left:6px;">
                    
                    <table class="totales-table">
                        <tr>
                            <td class="tot-label">Subtotal:</td>
                            <td class="tot-prefix">{{ $simbolo }}</td>
                            <td class="tot-valor">
                                {{ $hayTotal ? number_format($subtotal, 2) : '&ndash;' }}
                            </td>
                        </tr>
                        <tr class="igv-row">
                            <td class="tot-label">IGV</td>
                            <td class="tot-prefix">{{ $simbolo }}</td>
                            <td class="tot-valor">
                                {{ $hayTotal ? number_format($igv, 2) : '&ndash;' }}
                            </td>
                        </tr>
                        <tr class="total-row">
                            <td class="tot-label" style="font-size:10pt;">Total:</td>
                            <td class="tot-prefix" style="color:#fff; font-size:10pt;">{{ $simbolo }}</td>
                            <td class="tot-valor" style="font-size:10pt;">
                                {{ $hayTotal ? number_format($total, 2) : '&ndash;' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> 
    </div>

    {{-- ── LÍNEA SEPARADORA ──────────────────────────────────────────────── --}}
    <hr class="hr-line">

    {{-- ── CONDICIONES COMERCIALES + MEDIOS DE PAGO ─────────────────────── --}}
    <div class="footer-section">
        <table class="footer-table">
            <tr>               
                <td class="w-50 pr-8" style="vertical-align:top;">
                    <div class="footer-bar">CONDICIONES COMERCIALES:</div>
                    <div class="footer-content">
                        <b>Tiempo de entrega:</b> {{ $tiempo_entrega ?? '3 días' }}<br>
                        <b>Forma de pago:</b> {{ $forma_pago ?? 'Min 70% de adelanto' }}<br>
                        <b>Validez de la cotización:</b> {{ $validez ?? '07 días' }}
                    </div>
                </td>                
                <td class="w-50 pl-8" style="vertical-align:top;">
                    <div class="footer-bar">MEDIOS DE PAGO</div>
                    <div class="footer-content">
                        <b>SOLES:</b>&nbsp;&nbsp;&nbsp;
                        CTA. CTE. BCP 215740610202-7<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        CCI. BCP : 22150074061020200000<br>
                        <br>
                        <b>D&Oacute;LARES:</b>&nbsp;
                        CTA. CTE. BCP 215200489091-19<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        CCI. BCP : 00221512004890911000
                    </div>
                </td>
            </tr>
        </table>
        @if($ruc=="")
            <img src="{{ asset('smartadmin/dist/img/qr_zaida.png') }}" width="100" alt="Imagen referencial" style="position: absolute; left:85%; top:830px">        
        @endif
    </div>

    {{-- ── TÉRMINOS Y CONDICIONES ────────────────────────────────────────── --}}
    <div class="terminos-section">
        <div class="terminos-title">T&Eacute;RMINOS Y CONDICIONES</div>
        <div class="terminos-list">
            <p>* Cambios adicionales o modificaciones mayores en el dise&ntilde;o podr&aacute;n generar recotizaci&oacute;n o cargos administrativos, seg&uacute;n su impacto operativo.</p>
            <p>* Si la orden ya se encuentra en ejecuci&oacute;n, cualquier modificaci&oacute;n ser&aacute; considerada como reproceso o nueva orden, y los costos asociados ser&aacute;n asumidos por el cliente.</p>
            <p>* No se aceptan devoluciones ni reembolsos de dinero, salvo en casos de errores en el pedido atribuibles a la empresa.</p>
            <p>* En los casos excepcionales en que corresponda un reembolso, este se realizar&aacute; en un plazo m&aacute;ximo de 15 d&iacute;as h&aacute;biles por el mismo medio de pago.</p>
            <p>* El pedido debe ser recogido en la fecha acordada; a partir del 3er d&iacute;a se aplicar&aacute; un cargo por almacenamiento.</p>
            <p>* La empresa no se responsabiliza por trabajos que no han sido recogidos en la fecha pactada.</p>
        </div>
    </div>

    {{-- ── PIE DE PÁGINA ─────────────────────────────────────────────────── --}}
    <div class="page-footer">
        <table class="footer-direcciones-table">
            <tr>
                <td style="width:75%;">
                    <table border="0" cellpadding="0" cellspacing="2">
                        <tr>
                            <td style="vertical-align:top; padding-right:4px; padding-top:1px;">
                                <div style="width:9px; height:9px; background-color:#CC1E1E; border-radius:50% 50% 50% 0; display:inline-block;"></div>
                            </td>
                            <td style="font-size:7.5pt; color:#141414;">
                                <b>Sede 1:</b> Urb. San Lorenzo, Calle Malec&oacute;n Zolezzi 403-A Mariano Melgar
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align:top; padding-right:4px; padding-top:1px;">
                                <div style="width:9px; height:9px; background-color:#CC1E1E; border-radius:50% 50% 50% 0; display:inline-block;"></div>
                            </td>
                            <td style="font-size:7.5pt; color:#141414;">
                                <b>Sede 2:</b> Av. Argentina Mz E Lote 7B &ndash; Cerro Colorado
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:25%; text-align:right; vertical-align:middle;">
                    <div class="web-label">Contacta a tu asesor:<br> {{ $asesor_telefono ?? '' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── FRANJA ROJA INFERIOR ──────────────────────────────────────────── --}}
    <div class="franja-bottom text-center" style="font-size:7.5pt;">Sistemas para Empresas WebSoftAqp Cel:927245347</div>

</body>
</html>