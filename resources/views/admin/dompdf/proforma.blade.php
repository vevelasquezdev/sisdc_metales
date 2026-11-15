<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Proforma {{ $datos[0]->codigo_orden ?? '' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 7mm 10mm 10mm;
            color: #141414;
            background: #fff;
            font-family: Arial, sans-serif;
            font-size: 8pt;
        }

        .top-line {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 2mm;
            background: #cc1e1e;
        }

        .header {
            width: 100%;
            height: 28mm;
            border-collapse: collapse;
        }

        .header-divider {
            position: relative;
            top: 0mm;
            height: 0;
            border-top: 1px solid #ccc;
        }

        .header td {
            width: 50%;
            padding-top: 2mm;
            vertical-align: top;
        }

        .logo {
            margin-top: 5px;
            display: block;
            width: 48mm;
            max-height: 17mm;
        }

        .website {
            width: 48mm;
            margin-top: 0px;
            color: #005ae6;
            font-size: 9pt;
            font-weight: bold;
            text-align: center;
        }

        .proforma-title {
            padding-top: 0px;
            font-size: 15pt;
            font-weight: bold;
            white-space: nowrap;
        }

        .proforma-code {
            color: #cc1e1e;
        }

        .company {
            margin-top: 1mm;
            font-size: 9pt;
            line-height: 1.5;
        }

        .panels {
            width: 100%;
            margin-top: 4mm;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .panels>tbody>tr>td {
            width: 50%;
            padding: 0 1mm;
            vertical-align: top;
        }

        .panel-title,
        .section-title {
            padding: 1.5mm 2mm;
            color: #fff;
            background: #1a1a1a;
            font-weight: bold;
            font-size: 8pt;
        }

        .panel-content {
            height: 35mm;
            padding: 2mm;
            border: 1px solid #ccc;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 1.3mm 0.5mm;
            vertical-align: top;
            border-bottom: 1px solid #ddd;
            line-height: 1.25;
            word-wrap: break-word;
        }

        .data-label {
            width: 22mm;
            font-weight: bold;
            white-space: nowrap;
        }

        .products {
            width: 100%;
            margin-top: 6mm;
        }

        .items-area {
            height: 105mm;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .items-table th,
        .items-table td {
            padding: 2mm 1.5mm;
            border: 1px solid #ccc;
            vertical-align: top;
            word-wrap: break-word;
        }

        .items-table th {
            font-size: 8pt;
            text-align: center;
        }

        .items-table td {
            font-size: 8pt;
        }

        .item-number,
        .quantity,
        .price,
        .subtotal {
            text-align: center;
        }

        .description {
            text-align: left;
        }

        .summary-row td {
            /* font-weight: bold; */
        }

        .summary {
            width: 100%;
            margin-top: 2mm;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .summary td {
            width: 50%;
            padding: 0 1mm;
            vertical-align: top;
        }

        .conditions-table,
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .conditions-table td,
        .totals-table td {
            padding: 1.5mm 2mm;
        }

        .totals-table .label {
            text-align: right;
        }

        .totals-table .amount {
            width: 35%;
            text-align: right;
            white-space: nowrap;
        }

        .total-row td {
            font-weight: bold;
        }

        .terms {
            margin-top: 3mm;
        }

        .terms-content {
            padding: 2mm 1mm 0;
            font-size: 7pt;
            line-height: 1.45;
        }

        .company-contact {
            margin-top: 4mm;
            padding: 2mm 1mm 0;
            border-top: 1px solid #cc1e1e;
            font-weight: bold;
            font-size: 8pt;
            line-height: 1.5;
            word-wrap: break-word;
        }

        .bottom-line {
            position: fixed;
            right: 0;
            bottom: 0;
            left: 0;
            height: 6mm;
            padding-top: 1.2mm;
            color: #fff;
            background: #cc1e1e;
            font-weight: bold;
            font-size: 9pt;
            text-align: center;
        }

        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div class="top-line"></div>

    <table class="header">
        <tr>
            <td>
                <img class="logo" src="{{ asset('smartadmin/dist/img/logo_png.png') }}">
                <div class="website">{{ $info_local->link_web ?? '' }}</div>
            </td>
            <td>
                <div class="proforma-title">
                    N° PROFORMA:
                    <span class="proforma-code">#{{ $datos[0]->codigo_orden ?? '' }}</span>
                </div>
                <div class="company">
                    <strong>{{ $info_local->razon_social ?? '' }} - {{ $info_local->local ?? '' }}</strong><br>
                    RUC: {{ $info_local->ruc ?? '' }}
                </div>
            </td>
        </tr>
    </table>
    <div class="header-divider"></div>

    <table class="panels">
        <tr>
            <td>
                <div class="panel-title">Datos del Cliente</div>
                <div class="panel-content">
                    <table class="data-table">
                        <tr>
                            <td class="data-label">Cliente:</td>
                            <td>{{ $datos[0]->cliente ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="data-label">RUC:</td>
                            <td>{{ $datos[0]->ruc ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="data-label">Teléfono:</td>
                            <td>{{ $datos[0]->telefono ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="data-label">Email:</td>
                            <td>{{ $datos[0]->email ?? '' }}</td>
                        </tr>
                    </table>
                </div>
            </td>
            <td>
                <div class="panel-title">Datos del Asesor</div>
                <div class="panel-content">
                    <table class="data-table">
                        <tr>
                            <td class="data-label">Nombre:</td>
                            <td>{{ $datos[0]->usuario ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="data-label">Teléfono:</td>
                            <td>{{ $datos[0]->user_telefono ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="data-label">Correo:</td>
                            <td>{{ $datos[0]->user_email ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="data-label">Fecha:</td>
                            <td>{{ $fecha_emision }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="products">
        <div class="section-title">Descripción del servicio / producto</div>
        <div class="items-area">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:7%;">ÍTEM</th>
                        <th style="width:47%; text-align:left;">DESCRIPCIÓN</th>
                        <th style="width:13%;">CANTIDAD</th>
                        <th style="width:16%;">P. UNITARIO</th>
                        <th style="width:17%;">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $index => $item)
                        <tr>
                            <td class="item-number">{{ $index + 1 }}</td>
                            <td class="description">{{ $item['descripcion'] }}</td>
                            <td class="quantity">{{ $item['cantidad'] }}</td>
                            <td class="price">S/ {{ number_format((float) $item['precio'], 2, ',', ' ') }}</td>
                            <td class="subtotal">S/ {{ number_format((float) $item['subtotal'], 2, ',', ' ') }}</td>
                        </tr>
                    @endforeach

                    @if ($tiene_insumos)
                        <tr>
                            <td></td>
                            <td class="description"><strong>SERVICIO</strong></td>
                            <td></td>
                            <td></td>
                            <td class="subtotal">S/ {{ number_format((float) $total_servicio, 2, ',', ' ') }}</td>
                        </tr>
                    @endif
                    <tr class="summary-row">
                        <td colspan="4" class="description">{{ $datos[0]->des_gen ?? '' }}</td>
                        <td class="subtotal">S/ {{ number_format((float) $precio_total, 2, ',', ' ') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <table class="summary avoid-break">
        <tr>
            <td>

                <table class="conditions-table">
                    <tr>
                        <td>Incl. IGV: {{ (int) ($datos[0]->igv ?? 0) === 0 ? 'NO' : 'SI' }}</td>
                    </tr>
                    <tr>
                        <td>Incl. Material: {{ (int) ($datos[0]->material ?? 0) === 0 ? 'NO' : 'SI' }}</td>
                    </tr>
                    <tr>
                        <td>Incl. Plano: {{ (int) ($datos[0]->plano ?? 0) === 0 ? 'NO' : 'SI' }}</td>
                    </tr>
                </table>
            </td>
            <td>

                <table class="totals-table">
                    <tr>
                        <td class="label">A CUENTA:</td>
                        <td class="amount">S/ {{ number_format((float) $monto_pagado, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <td class="label">SALDO:</td>
                        <td class="amount">S/ {{ number_format((float) $saldo, 2, '.', ',') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="label">TOTAL:</td>
                        <td class="amount">S/ {{ number_format((float) $precio_total, 2, '.', ',') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="terms avoid-break">
        <div class="section-title">Términos y Condiciones</div>
        <div class="terms-content">
            * Una vez entregado el producto y/o servicio no se aceptan cambios ni devoluciones.<br>
            * Los productos deberán recogerse dentro de las 24 horas; después se cobrará almacenamiento.<br>
            * La empresa no se responsabiliza por trabajos o productos no recogidos en la fecha pactada.
        </div>
    </div>

    <div class="company-contact">
        Dirección: {{ $info_local->direccion ?? '' }}<br>
        Teléfono: {{ $telefono_local }} &nbsp;|&nbsp; Email: {{ $info_local->email ?? '' }}
    </div>

    <div class="bottom-line">
        Líderes en transformación y habilitación del acero en el sur del Perú
    </div>
</body>

</html>
