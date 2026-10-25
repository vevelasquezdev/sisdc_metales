@extends('layouts.app')
@section('titulo') Orden de Servicio @endsection


@section('contenido')
<div class="row">
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Tabla</i></span>&nbsp;&nbsp;Ordenes de Servicio
                </h2>
                
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10"
                        data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                        data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row ml-1 mb-3">                        
                        <button class="btn btn-primary" onclick="OpenModal();">
                            Nueva Orden de Servicio
                        </button>
                        <button class="btn btn-primary ml-2" onclick="Open_modal_Producto();">
                            Nueva Orden Producto
                        </button>
                        <table class="ml-3 mt-0 mb-1">
                            <tr>
                              <td>
                                 <input type='text'  id='search_fromdate' class="form-control form-control-sm" value="{{ date( "d-m-Y", strtotime( "-30 day", strtotime( date( "d-m-Y" ) ) ) ) }}" placeholder='desde...'>
                              </td>
                              <td>
                                 <input type='text'  id='search_todate' class="form-control form-control-sm" value="{{ date( "d-m-Y" ) }}" placeholder='hasta...'>
                              </td>
                              <td>
                                 <button type='button' class="btn btn-sm btn-info" id="btn_search">Buscar</button>
                              </td>
                            </tr>
                        </table>
                    </div>

                    <div class="table-responsive">
                        
                        <table id="tabla" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>Codigo</th>
                                    <th>Descripción Gral.</th>
                                    <th>Estado</th>
                                    <th>Ent.</th>
                                    <th>Pago</th>
                                    <th>Procedencia</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Telefono</th>
                                    <th>Fch Entrega</th>
                                    <th>Asesor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade default-example-modal-right-lg show" id="DlgModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-right modal-lg" style="max-width: 950px">
        <div class="modal-content" id="DlgModal_spinner">
            <div class="modal-header">
                <h5 class="modal-title h4">ORDEN DE SERVICIO - (PROFORMA)</h5>
                <button onclick="close_modal();" type="button" class="close" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body" >
                <form id="form" enctype="multipart/form-data">

                    {{-- HIDDEN --}}
                    <input type="hidden" id="DlgModal_id_orden">
                    <input type="hidden" id="DlgModal_txt_id_cliente">
                    <input type="hidden" id="DlgModal_id_servicio" class="form-control text-uppercase" value="">

                    <style>
                        /* ── Variables de marca ─────────────────────────── */
                        :root { --dc-red: #CC1E1E; --dc-lbl: #f5f5f5; --dc-border: #d0d0d0; }

                        /* ── Sección con borde rojo superior ────────────── */
                        .dc-card {
                            border: 1px solid var(--dc-border);
                            border-top: 3px solid var(--dc-red);
                            border-radius: 4px;
                            background: #fff;
                            margin-bottom: 12px;
                        }
                        .dc-card-header {
                            background: var(--dc-lbl);
                            border-bottom: 1px solid var(--dc-border);
                            padding: 6px 14px;
                            font-size: 11px;
                            font-weight: 700;
                            letter-spacing: .5px;
                            color: #333;
                            text-transform: uppercase;
                            display: flex;
                            align-items: center;
                            gap: 6px;
                        }
                        .dc-card-header i { color: var(--dc-red); }
                        .dc-card-body { padding: 10px 14px; }

                        /* ── Tabla-ficha (espejo del PDF) ───────────────── */
                        .dc-ficha {
                            width: 100%;
                            border-collapse: collapse;
                            font-size: 12.5px;
                        }
                        .dc-ficha td {
                            padding: 5px 10px;
                            border: 1px solid var(--dc-border);
                            vertical-align: middle;
                        }
                        .dc-ficha .lbl {
                            background: var(--dc-lbl);
                            font-weight: 700;
                            white-space: nowrap;
                            color: #333;
                            width: 1%;            /* encoge al mínimo */
                        }
                        .dc-ficha .sep {
                            height: 4px;
                            background: #f0f0f0;
                            padding: 0;
                        }

                        /* ── Tabla de servicios ─────────────────────────── */
                        #tableDynamic_ittems thead th {
                            background: #1a1a1a !important;
                            color: #fff !important;
                            font-size: 11.5px;
                            font-weight: 600;
                            border-color: #333 !important;
                            white-space: nowrap;
                        }
                        #tableDynamic_ittems tbody tr td { font-size: 12px; }

                        /* ── Archivos ───────────────────────────────────── */
                        .dc-file-row {
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            padding: 5px 10px;
                            border-bottom: 1px solid var(--dc-border);
                        }
                        .dc-file-row:last-child { border-bottom: none; }
                        .dc-file-label {
                            min-width: 130px;
                            font-size: 11.5px;
                            font-weight: 700;
                            color: #555;
                            white-space: nowrap;
                        }
                        .dc-file-row .custom-file { flex: 1; }
                        .dc-file-row .custom-file-label { font-size: 12px; }

                        /* ── Switches ───────────────────────────────────── */
                        .dc-switches {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 10px 24px;
                            padding: 8px 14px;
                            border-bottom: 1px solid var(--dc-border);
                            align-items: center;
                        }

                        /* ── Total badge ────────────────────────────────── */
                        .dc-total {
                            padding: 8px 14px;
                            font-size: 18px;
                            font-weight: 700;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        }
                        .dc-total .badge { font-size: 16px; padding: 4px 10px; }
                    </style>

                    <div class="form-row pl-2 pr-2">

                        {{-- ══════════════════════════════════════════════════
                            COLUMNA LATERAL — botones de acción
                        ══════════════════════════════════════════════════ --}}
                        <div class="col-md-1 col-lg-1 col-xl-1 d-flex flex-column align-items-center pt-2">
                            <a href="javascript:void(0);" onclick="realizarPago();"
                            class="btn btn-success btn-icon waves-effect waves-themed mb-2"
                            data-toggle="tooltip" title="Pagar en Caja">&#36;</a>
                            <a href="javascript:void(0);" onclick="print_boleta();"
                            class="btn btn-primary btn-icon waves-effect waves-themed mb-2"
                            data-toggle="tooltip" title="Imprimir Proforma">
                            <i class="fal fa-print"></i></a>
                            <a href="javascript:void(0);" onclick="qr_code_gen();"
                            class="btn btn-info btn-icon waves-effect waves-themed mb-2"
                            data-toggle="tooltip" title="Generar QR">
                            <i class="fa fa-qrcode"></i></a>
                            <a href="javascript:void(0);" onclick="ticket_proforma();"
                            class="btn btn-default btn-icon waves-effect waves-themed mb-2"
                            data-toggle="tooltip" title="Ticket proforma">
                            <i class="fa fa-print"></i></a>
                            <a href="javascript:void(0);" onclick="enviar_email();"
                            class="btn btn-danger btn-icon waves-effect waves-themed mb-2"
                            data-toggle="tooltip" title="Enviar E-mail">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z"/>
                                <path d="M14.247 14.269c1.01 0 1.587-.857 1.587-2.025v-.21C15.834 10.43 14.64 9 12.52 9h-.035C10.42 9 9 10.36 9 12.432v.214C9 14.82 10.438 16 12.358 16h.044c.594 0 1.018-.074 1.237-.175v-.73c-.245.11-.673.18-1.18.18h-.044c-1.334 0-2.571-.788-2.571-2.655v-.157c0-1.657 1.058-2.724 2.64-2.724h.04c1.535 0 2.484 1.05 2.484 2.326v.118c0 .975-.324 1.39-.639 1.39-.232 0-.41-.148-.41-.42v-2.19h-.906v.569h-.03c-.084-.298-.368-.63-.954-.63-.778 0-1.259.555-1.259 1.4v.528c0 .892.49 1.434 1.26 1.434.471 0 .896-.227 1.014-.643h.043c.118.42.617.648 1.12.648Zm-2.453-1.588v-.227c0-.546.227-.791.573-.791.297 0 .572.192.572.708v.367c0 .573-.253.744-.564.744-.354 0-.581-.215-.581-.8Z"/>
                            </svg>
                            </a>
                        </div>

                        {{-- ══════════════════════════════════════════════════
                            COLUMNA PRINCIPAL
                        ══════════════════════════════════════════════════ --}}
                        <div class="col-md-11 col-lg-11 col-xl-11">

                            {{-- ┌──────────────────────────────────────────────┐
                                │  1. CABECERA — Código · Local · Asesor · Fecha │
                                └──────────────────────────────────────────────┘ --}}
                            <div class="dc-card">
                                <div class="dc-card-body py-2">
                                    <div class="d-flex align-items-center flex-wrap" style="gap:12px;">

                                        <img src="{{ asset('smartadmin/dist/img/DC_PNG.jpg') }}"
                                            style="height:46px;" alt="Logo">

                                        <div style="min-width:130px;">
                                            <div style="font-size:10px; font-weight:700; color:#888; text-transform:uppercase; margin-bottom:2px;">
                                                N° Orden <span class="text-danger">*</span>
                                            </div>
                                            <input id="DlgModal_codigo" name="DlgModal_codigo" type="text"
                                                class="form-control text-uppercase"
                                                style="font-weight:700; font-size:20px; height:36px; border:0;
                                                    background:ghostwhite; color:#CC1E1E; text-align:center;"
                                                readonly>
                                        </div>

                                        <div style="min-width:130px;">
                                            <div style="font-size:10px; font-weight:700; color:#888; text-transform:uppercase; margin-bottom:2px;">
                                                Local <span class="text-danger">*</span>
                                            </div>
                                            <select id="DlgModal_procedencia" name="DlgModal_procedencia"
                                                class="form-control form-control-sm jquery_field text-uppercase" required>
                                                <option value="">Selecciona</option>
                                                <option value="RIO_SECO">RIO SECO</option>
                                                <option value="ZOLEZZI">ZOLEZZI</option>
                                            </select>
                                            <div id="error_DlgModal_procedencia" class="error_msg text-danger d-none"></div>
                                        </div>

                                        <div style="flex:1; min-width:180px;">
                                            <div style="font-size:10px; font-weight:700; color:#888; text-transform:uppercase; margin-bottom:2px;">
                                                Asesor <span class="text-danger">*</span>
                                            </div>
                                            <input id="DlgModal_id_user" name="DlgModal_id_user" type="text"
                                                class="form-control form-control-sm text-uppercase"
                                                value="{!! Auth::user()->name !!}" readonly>
                                            <div id="error_DlgModal_id_user" class="error_msg text-danger d-none"></div>
                                        </div>

                                        <div style="min-width:140px;">
                                            <div style="font-size:10px; font-weight:700; color:#888; text-transform:uppercase; margin-bottom:2px;">
                                                Fecha Ingreso <span class="text-danger">*</span>
                                            </div>
                                            <input id="DlgModal_fch_create" name="DlgModal_fch_create" type="text"
                                                class="form-control form-control-sm text-uppercase"
                                                value="{{ date('Y-m-d') }}">
                                            <div id="error_DlgModal_fch_create" class="error_msg text-danger d-none"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- ┌──────────────────────────────────────────────┐
                                │  2. FICHA DE ORDEN — espejo del PDF impreso  │
                                └──────────────────────────────────────────────┘ --}}
                            <div class="dc-card">
                                <div class="dc-card-header">
                                    <i class="fal fa-file-alt"></i> Datos de la Orden
                                </div>
                                <div class="p-0">
                                    <table class="dc-ficha">

                                        {{-- Fila 1: NOMBRE completo ancho + botones --}}
                                        <tr>
                                            <td class="lbl">NOMBRE DE CLIENTE <span class="text-danger">*</span></td>
                                            <td colspan="3">
                                                <div class="d-flex align-items-center" style="gap:6px;">
                                                    <input id="DlgModal_txt_name" type="text"
                                                        class="form-control form-control-sm text-uppercase"
                                                        placeholder="Nombres" readonly style="flex:1;">
                                                    <input id="DlgModal_txt_surname" type="text"
                                                        class="form-control form-control-sm text-uppercase"
                                                        placeholder="Apellidos" readonly style="flex:1;">
                                                    <button onclick="Open_buscar_cliente();" type="button"
                                                        class="btn btn-sm btn-outline-primary px-2" style="white-space:nowrap;">
                                                        <i class="fal fa-search"></i> Buscar
                                                    </button>
                                                    <button onclick="OpenModalCliente();" type="button"
                                                        class="btn btn-sm btn-outline-success px-2" style="white-space:nowrap;">
                                                        <i class="fal fa-user-plus"></i> Nuevo
                                                    </button>
                                                </div>
                                                <div id="error_DlgModal_txt_name"    class="error_msg_cli text-danger d-none"></div>
                                                <div id="error_DlgModal_txt_surname" class="error_msg_cli text-danger d-none"></div>
                                            </td>
                                        </tr>

                                        {{-- Fila 2: RUC · Razón Social (ancho completo) --}}
                                        <tr>
                                            <td class="lbl">RUC / RAZÓN SOCIAL</td>
                                            <td colspan="3">
                                                <div class="d-flex" style="gap:6px;">
                                                    <input id="DlgModal_txt_ruc" type="text"
                                                        class="form-control form-control-sm text-uppercase"
                                                        placeholder="RUC" readonly style="max-width:130px;">
                                                    <input id="DlgModal_txt_raz_soc" type="text"
                                                        class="form-control form-control-sm text-uppercase"
                                                        placeholder="Razón Social" readonly style="flex:1;">
                                                </div>
                                                <div id="error_DlgModal_txt_ruc"     class="error_msg_cli text-danger d-none"></div>
                                                <div id="error_DlgModal_txt_raz_soc" class="error_msg_cli text-danger d-none"></div>
                                            </td>
                                        </tr>

                                        {{-- Fila 3: TELÉFONO · EMAIL juntos --}}
                                        <tr>
                                            <td class="lbl">TELÉFONO</td>
                                            <td>
                                                <input id="DlgModal_txt_telefono" type="text"
                                                    class="form-control form-control-sm text-uppercase"
                                                    readonly style="max-width:180px;">
                                                <div id="error_DlgModal_txt_telefono" class="error_msg_cli text-danger d-none"></div>
                                            </td>
                                            <td class="lbl">EMAIL</td>
                                            <td>
                                                <input id="DlgModal_txt_email" type="text"
                                                    class="form-control form-control-sm" readonly>
                                                <div id="error_DlgModal_txt_email" class="error_msg_cli text-danger d-none"></div>
                                            </td>
                                        </tr>

                                        {{-- Detalle --}}
                                        <tr>
                                            <td class="lbl">DETALLE <span class="text-danger">*</span></td>
                                            <td colspan="3">
                                                <input id="DlgModal_des_gen" type="text"
                                                    class="form-control form-control-sm text-uppercase"
                                                    placeholder="Descripción general del trabajo...">
                                            </td>
                                        </tr>

                                        {{-- Separador --}}
                                        <tr><td colspan="4" class="sep"></td></tr>

                                        {{-- Fecha Entrega · Hora Entrega --}}
                                        <tr>
                                            <td class="lbl">FECHA ENTREGA <span class="text-danger">*</span></td>
                                            <td>
                                                <input id="DlgModal_fecha_entrega" name="DlgModal_fecha_entrega"
                                                    type="text" class="form-control form-control-sm text-uppercase"
                                                    value="{{ date('Y-m-d') }}"
                                                    style="font-weight:700; color:#CC1E1E; max-width:160px;">
                                                <div id="error_DlgModal_fecha_entrega" class="error_msg text-danger d-none"></div>
                                            </td>
                                            <td class="lbl">HORA ENTREGA <span class="text-danger">*</span></td>
                                            <td>
                                                <input id="DlgModal_hora_entrega" name="DlgModal_hora_entrega"
                                                    type="time" class="form-control form-control-sm text-uppercase"
                                                    value="{{ date('H:i') }}" style="max-width:140px;">
                                                <div id="error_DlgModal_hora_entrega" class="error_msg text-danger d-none"></div>
                                            </td>
                                        </tr>

                                        {{-- Material --}}
                                        <tr>
                                            <td class="lbl">MATERIAL <span class="text-danger">*</span></td>
                                            <td colspan="3">
                                                <div class="d-flex flex-wrap" style="gap:4px 16px;">
                                                    <div class="custom-control custom-radio custom-control-inline custom-radio-rounded">
                                                        <input type="radio" class="custom-control-input" id="m_a_36" value="ma36" name="radio_material">
                                                        <label class="custom-control-label" for="m_a_36">Acero A36</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline custom-radio-rounded">
                                                        <input type="radio" class="custom-control-input" id="m_a_201" value="ma201" name="radio_material">
                                                        <label class="custom-control-label" for="m_a_201">Acero 201</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline custom-radio-rounded">
                                                        <input type="radio" class="custom-control-input" id="m_a_304" value="ma304" name="radio_material">
                                                        <label class="custom-control-label" for="m_a_304">Acero 304</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline custom-radio-rounded">
                                                        <input type="radio" class="custom-control-input" id="m_a_430" value="ma430" name="radio_material">
                                                        <label class="custom-control-label" for="m_a_430">Acero 430</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline custom-radio-rounded">
                                                        <input type="radio" class="custom-control-input" id="m_aluminio" value="maluminio" name="radio_material">
                                                        <label class="custom-control-label" for="m_aluminio">Aluminio</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline custom-radio-rounded">
                                                        <input type="radio" class="custom-control-input" id="m_galvanizado" value="mgalvanizado" name="radio_material">
                                                        <label class="custom-control-label" for="m_galvanizado">Galvanizado</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline custom-radio-rounded">
                                                        <input type="radio" class="custom-control-input" id="m_otros" value="motros" name="radio_material">
                                                        <label class="custom-control-label" for="m_otros">Otros</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="lbl">APROBADO <span class="text-danger">*</span></td>
                                            <td colspan="3">
                                                <div class="d-flex flex-wrap" style="gap:4px 16px;">
                                                    <div class="custom-control custom-radio custom-control-inline custom-radio-rounded">
                                                        <input type="checkbox" class="custom-control-input" id="chk_aprobado_por_cliente" value="0">
                                                        <label class="custom-control-label" for="chk_aprobado_por_cliente">Aprobado por el cliente</label>
                                                    </div>                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            {{-- ┌──────────────────────────────────────────────┐
                                │  3. SERVICIOS — agregar ítems               │
                                └──────────────────────────────────────────────┘ --}}
                            <div class="dc-card">
                                <div class="dc-card-header">
                                    <i class="fal fa-tools"></i> Servicios
                                </div>
                                <div class="dc-card-body">

                                    {{-- Fila agregar ítem --}}
                                    <div class="d-flex flex-wrap align-items-end mb-3" style="gap:10px;">
                                        <div style="flex:2; min-width:200px;">
                                            <label class="form-label mb-1" style="font-size:11px; font-weight:700; color:#555; text-transform:uppercase;">
                                                Descripción del Servicio <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="DlgModal_des_serv"
                                                placeholder="Buscar servicio..."
                                                class="typeahead form-control form-control-sm text-uppercase"
                                                autocomplete="off"/>
                                            <div id="error_DlgModal_des_serv" class="error_msg text-danger d-none"></div>
                                        </div>
                                        <div style="min-width:80px;">
                                            <label class="form-label mb-1" style="font-size:11px; font-weight:700; color:#555; text-transform:uppercase;">
                                                Cant. <span class="text-danger">*</span>
                                            </label>
                                            <input id="DlgModal_cant" type="number" min="1"
                                                class="form-control form-control-sm text-uppercase" value="1" required>
                                            <div id="error_DlgModal_cant" class="error_msg text-danger d-none"></div>
                                        </div>
                                        <div style="min-width:110px;">
                                            <label class="form-label mb-1" style="font-size:11px; font-weight:700; color:#555; text-transform:uppercase;">
                                                Precio Unit. <span class="text-danger">*</span>
                                            </label>
                                            <input id="DlgModal_precio_unid" type="text"
                                                class="form-control form-control-sm text-uppercase"
                                                placeholder="0.00" onkeypress="return isNumberKey(event)">
                                            <div id="error_DlgModal_precio_unid" class="error_msg text-danger d-none"></div>
                                        </div>
                                        <div>
                                            <button type="button" onclick="add_item();"
                                                class="btn btn-sm btn-info waves-effect waves-themed">
                                                <span class="fal fa-plus-square mr-1"></span> Agregar
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Tabla dinámica --}}
                                    <div class="table-responsive">
                                        <table id="tableDynamic_ittems"
                                            class="table table-bordered table-hover table-striped w-100 mb-0">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Cant</th>
                                                    <th style="min-width:150px;">Descripción del Servicio</th>
                                                    <th>Pr. Unid</th>
                                                    <th>Pr. Total</th>
                                                    <th>Orden</th>
                                                    <th>Estado</th>
                                                    <th style="min-width:150px;">Comentario / Observ.</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </div>

                                {{-- Total + Switches --}}
                                <div class="dc-switches">
                                    <div class="dc-total p-0" style="font-size:15px;">
                                        TOTAL &nbsp;<strong style="color:#CC1E1E;">S/.</strong>
                                        <span id="DlgModal_txt_ttotal" class="badge badge-info" style="font-size:14px;"></span>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="chk_correccion" value="0">
                                        <label class="custom-control-label" for="chk_correccion">Corrección</label>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="chk_rapido" value="0">
                                        <label class="custom-control-label" for="chk_rapido">Orden Rápida</label>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="chk_igv" value="0">
                                        <label class="custom-control-label" for="chk_igv">IGV</label>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="chk_material" value="0">
                                        <label class="custom-control-label" for="chk_material">Material</label>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="chk_plano" value="0">
                                        <label class="custom-control-label" for="chk_plano">Plano</label>
                                    </div>
                                </div>

                            </div>

                            {{-- ┌──────────────────────────────────────────────┐
                                │  4. ARCHIVOS ADJUNTOS                        │
                                └──────────────────────────────────────────────┘ --}}
                            <div class="dc-card">
                                <div class="dc-card-header">
                                    <i class="fal fa-paperclip"></i> Archivos Adjuntos
                                    <small class="ml-1" style="font-weight:400; text-transform:none; color:#888;">(.pdf / .jpg / .jpeg / .dwg)</small>
                                </div>
                                <div class="p-0">

                                    @php $archivos = [1,2,3,4,5]; @endphp
                                    @foreach($archivos as $n)
                                    @php $sfx = ($n === 1) ? '' : $n; @endphp
                                    <div class="dc-file-row">
                                        <span class="dc-file-label">
                                            <i class="fal fa-file mr-1" style="color:#CC1E1E;"></i>
                                            Archivo {{ $n }}
                                        </span>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input"
                                                id="file{{ $sfx }}" name="file{{ $sfx }}"
                                                accept="pdf|jpg|jpeg|dwg"
                                                data-file_types="pdf|jpg|jpeg|dwg">
                                            <label id="label_file{{ $sfx }}"
                                                class="custom-file-label"
                                                for="file{{ $sfx }}">
                                                Seleccionar archivo {{ $n }}...
                                            </label>
                                        </div>
                                        <a href="javascript:void(0);"
                                            id="ver_archivo_{{ $n }}"
                                            class="btn btn-sm btn-outline-info btn-icon waves-effect waves-themed"
                                            data-toggle="tooltip" title="Ver archivo {{ $n }}">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                        <div id="alert{{ $sfx }}" style="font-size:11px;"></div>
                                    </div>
                                    @endforeach

                                </div>
                            </div>

                        </div>{{-- /col-md-11 --}}
                    </div>
                </form>
            </div>

            <div class="modal-footer border-faded">                
                <button id="BtnGuardarpedido" onclick="guardar_orden();" type="button" class="btn btn-primary">ENVIAR
                    ORDEN</button>
                <button onclick="close_modal();" type="button" class="btn btn-secondary">CERRAR</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade default-example-modal" id="DldModalCliente" tabindex="-1" role="dialog" style="display: none;"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="form_cliente">
                <input type="hidden" id='_token' name="_token" value="{{ csrf_token() }}" />
                <div class="modal-header">
                    <h5 class="modal-title h4">.: AGREGAR NUEVO CLIENTE :.</h5>
                    <button onclick="close_modal_cliente();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">
                        <input type="hidden" id="clie_txt_id">
                        <div class="col-md-5 mb-3">
                            <label class="form-label" for="clie_txt_nombres">Nombres: <span class="text-danger">*</span>
                            </label>
                            <input id="clie_txt_nombres" type="text" class="form-control jquery_field text-uppercase"
                                required>
                            <div id="error_clie_txt_nombres" class="error_msg text-danger d-none"> </div>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label" for="clie_txt_apellidos">Apellidos:<span
                                    class="text-danger">*</span></label>
                            <input id="clie_txt_apellidos" type="text" class="form-control jquery_field text-uppercase"
                                required>
                            <div id="error_clie_txt_apellidos" class="error_msg text-danger d-none"> </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label" for="clie_txt_fch_nac">Fecha Nac.:<span
                                    class="text-danger">*</span></label>
                            <input id="clie_txt_fch_nac" type="text" class="form-control jquery_field text-uppercase"
                                required>
                            <div id="error_clie_txt_fch_nac" class="error_msg text-danger d-none"> </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="clie_txt_dni">DNI:<span class="text-danger">*</span></label>
                            <input id="clie_txt_dni" type="text" class="form-control jquery_field" required>
                            <div id="error_clie_txt_dni" class="error_msg text-danger d-none"> </div>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label" for="clie_txt_email">Email:<span
                                    class="text-danger">*</span></label>
                            <input id="clie_txt_email" type="text" class="form-control jquery_field" required>
                            <div id="error_clie_txt_email" class="error_msg text-danger d-none"> </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="clie_txt_telefono">Telefono:<span
                                    class="text-danger">*</span></label>
                            <input id="clie_txt_telefono" type="text" class="form-control jquery_field" required>
                            <div id="error_clie_txt_telefono" class="error_msg text-danger d-none"> </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="clie_txt_direccion">Dirección:<span
                                    class="text-danger">*</span></label>
                            <input id="clie_txt_direccion" type="text" class="form-control jquery_field" required>
                            <div id="error_clie_txt_direccion" class="error_msg text-danger d-none"> </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="clie_txt_ruc">RUC:<span class="text-danger">*</span></label>
                            <input id="clie_txt_ruc" type="text" class="form-control jquery_field" required>
                            <div id="error_clie_txt_ruc" class="error_msg text-danger d-none"> </div>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label" for="clie_txt_razon_social">Razón Social:<span
                                    class="text-danger">*</span></label>
                            <input id="clie_txt_razon_social" type="text" class="form-control jquery_field" required>
                            <div id="error_clie_txt_razon_social" class="error_msg text-danger d-none"> </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="guardar_cliente();" type="button" class="btn btn-primary">Guardar</button>
                    <button onclick="close_modal_cliente();" type="button" class="btn btn-secondary">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade default-example-modal" id="DldModalBuscarCliente" tabindex="-1" role="dialog"
    style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form_cliente">
                <input type="hidden" id='_token' name="_token" value="{{ csrf_token() }}" />
                <div class="modal-header">
                    <h5 class="modal-title h4">.: TABLA CLIENTES :.</h5>
                    <button onclick="close_modal_buscar_cliente();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">
                        <div class="table-responsive">
                            <table id="tabla_buscar_cliente"
                                class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombres y apellidos</th>
                                        <th>Dni</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="select_buscar_cliente(0);" type="button"
                        class="btn btn-primary">Seleccionar</button>
                    <button onclick="close_modal_buscar_cliente();" type="button"
                        class="btn btn-secondary">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade default-example-modal" id="DldModalPago" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="dlg_form_pago">
                <div class="modal-header">
                    <h5 class="modal-title h4">.: PAGAR CONSUMO :.</h5>
                    <button onclick="close_pago();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="DldModalPago_txt_caja">Caja: <span class="text-danger">*</span> </label>
                            <select id="DldModalPago_txt_caja" class="form-control jquery_field text-uppercase"
                                required>
                                <option value="">Selecciona</option>
                                <option value="1">CAJA_1</option>
                                <option value="2">CAJA_2</option>
                                <option value="3">CAJA_3</option>
                            </select>
                            <div id="error_DldModalPago_txt_caja" class="error_msg_pago text-danger d-none"> </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="DldModalPago_txt_razon_social">Razon Social:<span class="text-danger">*</span></label>
                            <input id="DldModalPago_txt_razon_social" type="text" class="form-control text-uppercase">
                            <div id="error_DldModalPago_txt_razon_social" class="error_msg_pago text-danger d-none"> </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="DldModalPago_txt_id_orden">Codigo:<span class="text-danger">*</span></label>
                            <input id="DldModalPago_txt_id_orden" type="text" class="form-control text-uppercase" style="font-weight: bold;font-size: 26px; height: 37px;border: 0px; background: ghostwhite;color: red;" readonly>
                            <div id="error_DldModalPago_txt_id_orden" class="error_msg_pago text-danger d-none"> </div>
                        </div>
                        <div class="col-md-9 mb-3">
                            <label class="form-label" for="DldModalPago_txt_descripcion">Descripcion: <span
                                    class="text-danger">*</span> </label>
                            <input id="DldModalPago_txt_descripcion" type="text"
                                class="form-control jquery_field text-uppercase" required>
                            <div id="error_DldModalPago_txt_descripcion" class="error_msg_pago text-danger d-none">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="DldModalPago_txt_fch_emi">Fecha-Hora Emision: <span
                                    class="text-danger">*</span> </label>
                            <input id="DldModalPago_txt_fch_emi" class="form-control jquery_field" type="text"
                                placeholder="" data-inputmask="'mask': '99-99-9999 99:99'"
                                value="{{ date('d-m-Y H:i') }}">
                            <span class="help-block">Ejemplo: 25-06-2022 09:45</span>
                            <div id="error_DldModalPago_txt_fch_emi" class="error_msg_pago text-danger d-none"> </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="DldModalPago_txt_id_forma_pago">Forma Pago: <span
                                    class="text-danger">*</span> </label>
                            <select id="DldModalPago_txt_id_forma_pago" onchange="forma_pago(this.value)"
                                class="form-control jquery_field text-uppercase" required>
                                <option value="">Selecciona</option>
                                @foreach ($formapagos as $formapago)
                                <option value="{{$formapago->id}}">{{$formapago->descripcion}}</option>
                                @endforeach
                            </select>
                            <div id="error_DldModalPago_txt_id_forma_pago" class="error_msg_pago text-danger d-none">
                            </div>
                        </div>


                        <div id="fp_1" class="col-md-4 mb-3" style="display: none">
                            <label class="form-label" for="DldModalPago_txt_efectivo">monto Efectivo S/.<span
                                    class="text-danger">*</span></label>
                            <input id="DldModalPago_txt_efectivo" type="text" class="form-control text-uppercase"
                                onkeypress="return isNumberKey(event)">
                            <div id="error_DldModalPago_txt_efectivo" class="error_msg_pago text-danger d-none"> </div>
                        </div>
                        <div id="fp_2" class="col-md-4 mb-3" style="display: none">
                            <label class="form-label" for="DldModalPago_txt_tarjeta">monto Tarjeta S/.<span
                                    class="text-danger">*</span></label>
                            <input id="DldModalPago_txt_tarjeta" type="text" class="form-control text-uppercase"
                                onkeypress="return isNumberKey(event)">
                            <div id="error_DldModalPago_txt_tarjeta" class="error_msg_pago text-danger d-none"> </div>
                        </div>
                        <div id="fp_3" class="col-md-4 mb-3" style="display: none">
                            <label class="form-label" for="DldModalPago_txt_yape">monto Yape S/.<span
                                    class="text-danger">*</span></label>
                            <input id="DldModalPago_txt_yape" type="text" class="form-control text-uppercase"
                                onkeypress="return isNumberKey(event)">
                            <div id="error_DldModalPago_txt_yape" class="error_msg_pago text-danger d-none"> </div>
                        </div>
                        <div id="fp_4" class="col-md-4 mb-3" style="display: none">
                            <label class="form-label" for="DldModalPago_txt_transferencia">monto Transferencia S/.<span
                                    class="text-danger">*</span></label>
                            <input id="DldModalPago_txt_transferencia" type="text" class="form-control text-uppercase"
                                onkeypress="return isNumberKey(event)">
                            <div id="error_DldModalPago_txt_transferencia" class="error_msg_pago text-danger d-none">
                            </div>
                        </div>
                        <div id="fp_5" class="col-md-4 mb-3" style="display: none">
                            <label class="form-label" for="DldModalPago_txt_credito">monto Credito S/.<span
                                    class="text-danger">*</span></label>
                            <input id="DldModalPago_txt_credito" type="text" class="form-control text-uppercase"
                                onkeypress="return isNumberKey(event)">
                            <div id="error_DldModalPago_txt_credito" class="error_msg_pago text-danger d-none"> </div>
                        </div>
                        <div id="fp_6" class="form-row col-md-8 mb-3" style="display: none">
                            <div class="col-md-6">
                                <label class="form-label" for="DldModalPago_txt_efectivo2">monto Efectivo S/.<span
                                        class="text-danger">*</span></label>
                                <input id="DldModalPago_txt_efectivo2" type="text" class="form-control text-uppercase"
                                    onkeypress="return isNumberKey(event)">
                                <div id="error_DldModalPago_txt_efectivo" class="error_msg_pago text-danger d-none">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="DldModalPago_txt_tarjeta2">monto Tarjeta S/.<span
                                        class="text-danger">*</span></label>
                                <input id="DldModalPago_txt_tarjeta2" type="text" class="form-control text-uppercase"
                                    onkeypress="return isNumberKey(event)">
                                <div id="error_DldModalPago_txt_tarjeta" class="error_msg_pago text-danger d-none">
                                </div>
                            </div>
                        </div>
                        <div id="fp_7" class="form-row col-md-8 mb-3" style="display: none">
                            <div class="col-md-6">
                                <label class="form-label" for="DldModalPago_txt_efectivo3">monto Efectivo S/.<span
                                        class="text-danger">*</span></label>
                                <input id="DldModalPago_txt_efectivo3" type="text" class="form-control text-uppercase"
                                    onkeypress="return isNumberKey(event)">
                                <div id="error_DldModalPago_txt_efectivo" class="error_msg_pago text-danger d-none">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="DldModalPago_txt_yape2">monto Yape S/.<span
                                        class="text-danger">*</span></label>
                                <input id="DldModalPago_txt_yape2" type="text" class="form-control text-uppercase">
                                <div id="error_DldModalPago_txt_yape" class="error_msg_pago text-danger d-none"> </div>
                            </div>
                        </div>
                        <div id="fp_8" class="form-row col-md-8 mb-3" style="display: none">
                            <div class="col-md-6">
                                <label class="form-label" for="DldModalPago_txt_efectivo4">monto Efectivo S/.<span
                                        class="text-danger">*</span></label>
                                <input id="DldModalPago_txt_efectivo4" type="text" class="form-control text-uppercase"
                                    onkeypress="return isNumberKey(event)">
                                <div id="error_DldModalPago_txt_efectivo" class="error_msg_pago text-danger d-none">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="DldModalPago_txt_transferencia2">monto Transferencia
                                    S/.<span class="text-danger">*</span></label>
                                <input id="DldModalPago_txt_transferencia2" type="text"
                                    class="form-control text-uppercase" onkeypress="return isNumberKey(event)">
                                <div id="error_DldModalPago_txt_transferencia"
                                    class="error_msg_pago text-danger d-none"> </div>
                            </div>
                        </div>
                        <div id="fp_9" class="form-row col-md-8 mb-3" style="display: none">
                            <div class="col-md-6">
                                <label class="form-label" for="DldModalPago_txt_efectivo5">monto Efectivo S/.<span
                                        class="text-danger">*</span></label>
                                <input id="DldModalPago_txt_efectivo5" type="text" class="form-control text-uppercase"
                                    onkeypress="return isNumberKey(event)">
                                <div id="error_DldModalPago_txt_efectivo" class="error_msg_pago text-danger d-none">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="DldModalPago_txt_credito2">monto Credito S/.<span
                                        class="text-danger">*</span></label>
                                <input id="DldModalPago_txt_credito2" type="text" class="form-control text-uppercase"
                                    onkeypress="return isNumberKey(event)">
                                <div id="error_DldModalPago_txt_credito" class="error_msg_pago text-danger d-none">
                                </div>
                            </div>
                        </div>
                        <div id="fp_10" class="col-md-4 mb-3" style="display: none">
                            <label class="form-label" for="DldModalPago_txt_cupon">monto Cupón S/.<span
                                    class="text-danger">*</span></label>
                            <input id="DldModalPago_txt_cupon" type="text" class="form-control text-uppercase"
                                onkeypress="return isNumberKey(event)">
                            <div id="error_DldModalPago_txt_cupon" class="error_msg_pago text-danger d-none"> </div>
                        </div>
                        <div class="col-md-12 mb-3">

                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="DldModalPago_txt_saldo">Saldo S/.<span
                                    class="text-danger">*</span></label>
                            <input id="DldModalPago_txt_saldo" type="text" class="form-control text-uppercase"
                                onkeypress="return isNumberKey(event)" readonly>
                            <div id="error_DldModalPago_txt_saldo" class="error_msg_pago text-danger d-none"> </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-faded">
                    <button onclick="close_pago();" type="button" class="btn btn-secondary">Cerrar</button>
                    <button onclick="insert_pago_orden();" type="button" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade default-example-modal" id="DldModalVerArchivos" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="dlg_form">
                <div class="modal-header">
                    <h5 class="modal-title h4">.: Ver Archivos :.</h5>
                    <button onclick="close_modal_ver_archivos();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">                        
                        <div class="col-md-10">
                            <div id="alertArchivo1" style="height: 25px; padding-top: 4px;"></div>
                        </div>
                        <div class="col-md-2">
                            <a href="javascript:void(0);" id="ver_archivo_11" class="btn btn-sm btn-info btn-icon waves-effect waves-themed" data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-dark&quot;></div></div>"
                                data-toggle="tooltip" data-original-title="Ver archivo 1">
                                <i class="fal fa-file-image"></i>
                            </a>
                        </div>
                        <div class="col-md-10 mt-3">
                            <div id="alertArchivo2" style="height: 25px; padding-top: 4px;"></div>
                        </div>
                        <div class="col-md-2 mt-3">
                            <a href="javascript:void(0);" id="ver_archivo_22" class="btn btn-sm btn-info btn-icon waves-effect waves-themed" data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-dark&quot;></div></div>"
                                data-toggle="tooltip" data-original-title="Ver archivo 2">
                                <i class="fal fa-file-image"></i>
                            </a>
                        </div>
                        <div class="col-md-10 mt-3">
                            <div id="alertArchivo3" style="height: 25px; padding-top: 4px;"></div>
                        </div>
                        <div class="col-md-2 mt-3">
                            <a href="javascript:void(0);" id="ver_archivo_33" class="btn btn-sm btn-info btn-icon waves-effect waves-themed" data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-dark&quot;></div></div>"
                                data-toggle="tooltip" data-original-title="Ver archivo 3">
                                <i class="fal fa-file-image"></i>
                            </a>
                        </div>
                        <div class="col-md-10 mt-3">
                            <div id="alertArchivo4" style="height: 25px; padding-top: 4px;"></div>
                        </div>
                        <div class="col-md-2 mt-3">
                            <a href="javascript:void(0);" id="ver_archivo_44" class="btn btn-sm btn-info btn-icon waves-effect waves-themed" data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-dark&quot;></div></div>"
                                data-toggle="tooltip" data-original-title="Ver archivo 4">
                                <i class="fal fa-file-image"></i>
                            </a>
                        </div>
                        <div class="col-md-10 mt-3">
                            <div id="alertArchivo5" style="height: 25px; padding-top: 4px;"></div>
                        </div>
                        <div class="col-md-2 mt-3">
                            <a href="javascript:void(0);" id="ver_archivo_55" class="btn btn-sm btn-info btn-icon waves-effect waves-themed" data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-dark&quot;></div></div>"
                                data-toggle="tooltip" data-original-title="Ver archivo 5">
                                <i class="fal fa-file-image"></i>
                            </a>
                        </div>                       
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="close_modal_ver_archivos();" type="button" class="btn btn-secondary">Cerrar</button>                    
                </div>
            </form>            
        </div>
    </div>
</div>

<div class="modal fade default-example-modal" id="DldModalEditMaterial" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="dlg_form_edit_material">
                <div class="modal-header">
                    <h5 class="modal-title h4">.: Editar material :.</h5>
                    <button onclick="close_modal_edit_material();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">
                            {{-- MATERIAL --}}
                            <input type="hidden" id="edit_material_id_orden_detalle">
                            <div class="col-md-3 mt-1">
                                <label class="form-label" for="DlgModal_cant_mat2">Cant.:<span class="text-danger">*</span></label>
                                <input id="DlgModal_cant_mat2" type="number" min="1" onChange="editar_cal_costo_auto();" class="form-control text-uppercase" value="1" required>
                                <div id="error_DlgModal_cant_mat2" class="error_msg text-danger d-none"> </div>
                            </div>
                            <div class="col-md-3 mt-1">
                                <label class="form-label" for="DlgModal_largo2">Largo (cm)<span class="text-danger">*</span></label>
                                <input id="DlgModal_largo2" type="text" class="matcal2 form-control text-uppercase" placeholder="00.00" onChange="editar_cal_costo_auto();" onkeypress="return isNumberKey(event)">
                                <div id="error_DlgModal_largo2" class="error_msg text-danger d-none"> </div>
                            </div>
                            <div class="col-md-3 mt-1">
                                <label class="form-label" for="DlgModal_ancho2">Ancho (cm)<span class="text-danger">*</span></label>
                                <input id="DlgModal_ancho2" type="text" class="matcal2 form-control text-uppercase" placeholder="00.00" onChange="editar_cal_costo_auto();" onkeypress="return isNumberKey(event)">
                                <div id="error_DlgModal_ancho2" class="error_msg text-danger d-none"> </div>
                            </div>
                            <div class="col-md-3 mt-1">
                                <label class="form-label" for="DlgModal_espesor2">Espesor (mm)<span class="text-danger">*</span></label>
                                <input id="DlgModal_espesor2" type="text" class="matcal2 form-control text-uppercase" placeholder="00.00" onChange="editar_cal_costo_auto();" onkeypress="return isNumberKey(event)">
                                <div id="error_DlgModal_espesor2" class="error_msg text-danger d-none"> </div>
                            </div>                                
                           
                            <div class="col-md-12 mt-1">
                                <label class="form-label" for="DlgModal_id_mat2">Material:<span class="text-danger">*</span></label>
                                <select id="DlgModal_id_mat2" name="DlgModal_id_mat2" class="form-control jquery_field" onChange="editar_cal_costo_auto();" required>
                                    <option value="">Selecciona</option>
                                    @foreach ($materiales as $material)
                                        <option value="{{$material->id}}">{{$material->desc}} -- S/.{{$material->precio}}</option>
                                    @endforeach
                                </select>
                                <div id="error_DlgModal_id_mat2" class="error_msg text-danger d-none"> </div>
                            </div>                               
                           
                            <div class="col-md-6 mt-3">
                                <h4>Unid: <span style="margin-top: 12px; margin-left:5px" id="DlgModal_txt_unidad_mat2" class="badge badge-success">00.00</span></h4>
                            </div>
                            <div class="col-md-6 mt-3">
                                <h4>Total: <span style="margin-top: 12px; margin-left:5px" id="DlgModal_txt_ttotal_mat2" class="badge badge-success">00.00</span></h4>
                            </div>
                                               
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="guardar_edit_material();" type="button" class="btn btn-primary">Guardar</button>
                    <button onclick="close_modal_edit_material();" type="button" class="btn btn-secondary">Cerrar</button>
                </div>
            </form>            
        </div>
    </div>
</div>

<div class="modal fade default-example-modal" id="DldModalProductos" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="dlg_form_producto">
                <div class="modal-header">
                    <h5 class="modal-title h4">.: Crear Orden segun Producto :.</h5>
                    <button onclick="close_modal_producto();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">
                        <input type="hidden" id="DlgModal_id">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="DlgModal_des_producto">Descripcion Producto:<span class="text-danger">*</span></label>
                            <input type="text" id="DlgModal_des_producto" placeholder="Buscar Producto..." class="typeahead form-control text-uppercase" autocomplete="off"/>
                            <input id="DlgModal_id_producto" type="hidden" class="form-control text-uppercase" value="">
                            <div id="error_DlgModal_des_producto" class="error_msg text-danger d-none"> </div>
                        </div>      
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="siguiente_orden();" type="button" class="btn btn-primary">Siguiente</button>
                    <button onclick="close_modal_producto();" type="button" class="btn btn-secondary">Cerrar</button>
                </div>
            </form>            
        </div>
    </div>
</div>




@endsection
@push('scripts')

<script src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>

<script src="{{ asset('js/cliente.js') }}"></script>
<script src="{{ asset('js/realizar_pago.js') }}"></script>
<script src="{{ asset('js/validar_planos.js') }}"></script>
<script src="{{ asset('js/planchas.js') }}"></script>
<script>

var global_modal=0; 
var user_rol = "{!! Auth::user()->rol !!}"; 
var user_name = "{!! Auth::user()->name !!}";
var id_orden_para_producto_global=0;
window.onbeforeunload = function(e) { /// al actualizar
    if(global_modal==0){
        console.log('modal cerrado');
    }else{
        console.log('modal abierto'); close_modal();
    } 
    borrar_registros_orden_no();   
};

$(document).keyup(function(e){
    if(e.which==27) {
        if ($('#DldModalCliente').is(':visible')) {
            close_modal_cliente();
        }else if($('#DldModalBuscarCliente').is(':visible')) {  
            close_modal_buscar_cliente();
        }else if($('#DldModalPago').is(':visible')) {  
            close_pago();
        }else if($('.sweetalert_editar_precio').is(':visible')) { 
            console.log('cerrando sweetalert');
        }else if($('#bootbox_add_comentario').is(':visible')) { 
            console.log('cerrando bootbox comentario');
        }else if($('#bootbox_del_item').is(':visible')) { 
            console.log('cerrando bootbox del item');
        }else if($('#DldModalVerArchivos').is(':visible')) { 
            close_modal_ver_archivos();
        }else if($('#DldModalEditMaterial').is(':visible')) { 
            close_modal_edit_material();
        }else if($('#DlgModal').is(':visible')){
            close_modal();
        }
    }
});




$(function () { 
    $("#menu_orden").addClass("active");
    $('#DlgModal_fch_create').datepicker({ todayHighlight: true, templates: controls,format: 'yyyy-mm-dd' });
    $('#DlgModal_fecha_entrega').datepicker({ todayHighlight: true, templates: controls,format: 'yyyy-mm-dd' });

    $('#search_fromdate').datepicker({ todayHighlight: true,orientation: "bottom left",templates: controls, format: 'dd-mm-yyyy'  });
    $('#search_todate').datepicker({ todayHighlight: true,orientation: "bottom left",templates: controls, format: 'dd-mm-yyyy'  });

    $('#chk_correccion').on('change', function(){ this.value = this.checked ? 1 : 0; }).change();
    $('#chk_rapido').on('change', function(){ this.value = this.checked ? 1 : 0; }).change();
    $('#chk_igv').on('change', function(){ this.value = this.checked ? 1 : 0; }).change();
    $('#chk_material').on('change', function(){ this.value = this.checked ? 1 : 0; }).change();
    $('#chk_plano').on('change', function(){ this.value = this.checked ? 1 : 0; }).change();

     
    var table = $('#tabla').DataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        responsive: true,       
        ajax: {
            url:"{{ route('orden.list') }}",
            data: function(data){
                var from_date = $('#search_fromdate').val();
                var to_date = $('#search_todate').val();
                data.from_date = from_date;
                data.to_date = to_date;
            }
        },
        columns: [
            {data: 'codigo',className:'text-center'}, 
            {data: 'des_gen'},
            {data: 'estado'},
            {data: 'entrega',className:'text-center'},
            {data: 'pago_caja',className:'text-center'},
            {data: 'procedencia'},                                     
            {data: 'fch_create',className:'text-center'},
            {data: 'cliente'},            
            {data: 'telefono'},  
            {data: 'fecha_entrega',className:'text-center'},
            {data: 'usuario'},
            {data: 'action',  width: "10%", searchable: false},
        ],
        columnDefs: [{targets: 2,
            render: function ( data, type, row ) {
                if(data=="0"){
                    return "<span class='badge badge-danger badge-pill'>EN ESPERA</span>";                    
                }else if(data=="1"){
                    return "<span class='badge badge-info badge-pill'>EJECUTANDO</span>"; 
                }else{
                    return "<span class='badge badge-success badge-pill'>TERMINADO</span>"; 
                }                
            }
        }],
        dom: dom_buttons_table,
        buttons: [{
            extend: 'pdfHtml5',
            text: 'PDF',
            titleAttr: 'Generate PDF',
            className: 'btn-outline-danger btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9,10]
            }
        },{
            extend: 'excelHtml5',
            text: 'Excel',
            titleAttr: 'Generate Excel',
            className: 'btn-outline-success btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9,10]
            }
        }],
        lengthMenu: [ [30, 50,100, -1], [30, 50,100, "All"] ],
        language: espanol,
        ordering: false,
        "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData.rapido == "1" || aData.rapido == 1) {
                $('td', nRow).css('background-color', '#b7fcd4');
            }
            if (aData.correccion == "1" || aData.correccion == 1) {
                $('td', nRow).css('background-color', '#FFFFCC');
            }
        },
        initComplete: function () {
            this.api().columns(2).every( function () {
                var column = this;
                var select = $("<select class='form-control' style='padding: 0px;font-size: 11px;height: 20px;'><option value=''>ESTADO</option></select>")
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column.search( val ? '^'+val+'$' : '', true, false ).draw();
                    } );
                select.append("<option value='0'>EN ESPERA</option>")
                select.append("<option value='1'>EJECUTANDO</option>")
                select.append("<option value='2'>TERMINADO</option>")
            } );
            this.api().columns(4).every( function () {
                var column = this;
                var header = $(column.header());
                
                var input = $('<input type="text" class="form-control" style="padding: 0px 5px;font-size: 11px;height: 20px;width:50px" placeholder="Buscar ' + header.text() + '"/>')
                    .appendTo(header)
                    .on('keyup change', function() {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
              
            } );
            this.api().columns(5).every( function () {
                var column = this;
                var select = $("<select class='form-control' style='padding: 0px;font-size: 11px;height: 20px;'><option value=''>Local</option></select>")
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column.search( val ? '^'+val+'$' : '', true, false ).draw();
                    } );
                select.append("<option value='RIO_SECO'>RIO_SECO</option>")
                select.append("<option value='ZOLEZZI'>ZOLEZZI</option>")
              
            } );
            this.api().columns(7).every( function () {
                var column = this;
                var header = $(column.header());
                
                var input = $('<input type="text" class="form-control" style="padding: 0px 5px;font-size: 11px;height: 20px;" placeholder="Buscar ' + header.text() + '"/>')
                    .appendTo(header)
                    .on('keyup change', function() {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
              
            } );
            this.api().columns(9).every(function () {
                var column = this;
                var header = $(column.header());

                var input = $(`
                        <input type="text" id="filtro_fecha_entrega" class="form-control" style="padding:0px 5px;font-size:11px;height:20px;width:95px;" placeholder="Fecha">
                    `)
                    .appendTo(header.empty())
                    .on('change keyup', function () {
                        column.search(this.value).draw();
                    });               
                input.datepicker({todayHighlight: true, templates: controls, format: 'dd-mm-yyyy', autoclose: true });
            });
            this.api().columns(10).every( function () {
                var column = this;
                var header = $(column.header());
                
                var input = $('<input type="text" class="form-control" style="padding: 0px 5px;font-size: 11px;height: 20px;"" placeholder="Buscar ' + header.text() + '"/>')
                    .appendTo(header)
                    .on('keyup change', function() {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
              
            } );
        }      
    });

    $('#btn_search').click(function(){
        table.draw();
    });

    var table_buscar_cliente = $('#tabla_buscar_cliente').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        select: true,
        ajax: "{{ route('clientes.list') }}",
        columns: [
            {data: 'id',visible: false, searchable: false},                                     
            {data: 'persona'},
            {data: 'dni'}            
        ],        
        lengthMenu: [ [10, 20, -1], [10, 20, "All"] ],
        language: espanol,
        ordering: false,
        info: false       
    });

    // $('div.dataTables_filter input', table_buscar_cliente.table().container()).focus();

    MsgDlgLoadAjaxForm("form");
    var table_dinamica = $('#tableDynamic_ittems').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        ajax : "{{ route('tabla_orden_detalle') }}"+"?id_orden=0",  
        drawCallback: function () {
            var sum = $('#tableDynamic_ittems').DataTable().column(4).data().sum();            
            var subtotal=sum/1.18; 
            var igv = subtotal*0.18;       
            $('#DlgModal_txt_ttotal').html(parseFloat(sum).toFixed(2));           
        },      
        columns: [      
            {data: 'id',visible: false,orderable: false, searchable: false},      
            {data: 'cant', className:'text-center'},
            {data: 'descripcion'},
            {data: 'precio_unid',className:'text-right'},
            {data: 'pre_tot',className:'text-right'},
            {data: 'orden',className:'text-center'},
            {data: 'estado',className:'text-center'},
            {data: 'comentario'},
            {data: 'action', className:'text-center', orderable: false, searchable: false}
        ],              
        ordering: false,
        bLengthChange: false,
        searching: false,
        bPaginate: false,
        info:false,        
        language: espanol        
    });

    setTimeout(function(){ 
        MsgDlgLoadAjaxFinish("form"); 
    }, 300);
   
});

function add_item_material(){
    $('.error_msg').addClass('d-none').text('');
    if($("#DlgModal_id_mat").val()==""){
        $("#DlgModal_id_mat").focus(); 
        return maquinge.notificaciones("Seleccione el material...", 'Sistema', 'warning');        
    }   
    if($("#DlgModal_largo").val()==""){
        $("#DlgModal_largo").focus(); 
        return maquinge.notificaciones("Ingrese el largo del material...", 'Sistema', 'warning');        
    }
    if($("#DlgModal_ancho").val()==""){
        $("#DlgModal_ancho").focus(); 
        return maquinge.notificaciones("Ingrese el ancho del material...", 'Sistema', 'warning');        
    }
    if($("#DlgModal_espesor").val()==""){
        $("#DlgModal_espesor").focus(); 
        return maquinge.notificaciones("Ingrese el espesor del material...", 'Sistema', 'warning');        
    }

    var cant_mat = $("#DlgModal_txt_unidad_mat").text();
    var can_mat2 = parseFloat(cant_mat.replace("S/.", ""));
  
    MsgDlgLoadAjaxForm("form");   
    $.ajax({         
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'orden-detalle/create',
        type: 'GET',
        data:{  
            "_token"        : "{{ csrf_token() }}",          
            id_orden        : $("#DlgModal_id_orden").val(),         
            cant            : $("#DlgModal_cant_mat").val(),
            id_servicio     : 29,//id_del servicio material
            precio_unid     : can_mat2.toFixed(2),
            material        : 1      
        },
    }).done(function (data) { 
        $.ajax({         
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            url: 'consumomaterial/create',
            type: 'GET',
            data:{  
                "_token"        : "{{ csrf_token() }}",  
                largo           : $("#DlgModal_largo").val(),
                ancho           : $("#DlgModal_ancho").val(),
                espesor         : $("#DlgModal_espesor").val(),
                id_tip_mat      : $("#DlgModal_id_mat").val(),
                id_orden        : $("#DlgModal_id_orden").val(),
                id_orden_det    : data.id_detalle,         
                cant            : $("#DlgModal_cant_mat").val(),
                id_servicio     : 29, //id_del servicio material
                precio_unid     : can_mat2.toFixed(2)      
            },
            success: function(data2){
                console.log(data2.msg);
            },
            error : function(xhr, status) {
                console.log('Error al agregar material...');
            }
        });       
        setTimeout(function(){
            $("#DlgModal_cant_mat").val('1');
            $("#DlgModal_largo").val('');
            $("#DlgModal_ancho").val('');
            $("#DlgModal_espesor").val('');
            $("#DlgModal_id_mat").val('');
            $("#DlgModal_txt_unidad_mat").text('00.00');
            $("#DlgModal_txt_ttotal_mat").text('00.00');
            MsgDlgLoadAjaxFinish("form");
            $("#DlgModal_des_serv").focus(); 
        }, 300);
        
        actualizar_tabla_dinamica();
    }).fail(function (jqXHR, textStatus,errorThrown) {
        maquinge.notificaciones(textStatus, 'Sistema', 'error');     
        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
        MsgDlgLoadAjaxFinish("form"); 
    });


}

function cal_costo_auto(){
   
    var mmm= $('select[name="DlgModal_id_mat"] option:selected').text();
    var mmm2 = mmm.split('--',2);
    var precio = parseFloat((mmm2[1]).slice(4));
    var factor_k=0.0008;
    var add = 1.0;
    var add2 = 1.0;
   
    $('.matcal').each(function() {
        if (!isNaN($(this).val())) {            
            if (add < 0) { add = add * -1;}
            if (add == 0) { add = 1;}
            add = add * parseFloat($(this).val());
            valor1 = add;
            console.log("val: "+add);         
        }
    });
    // $('.matcal_total').each(function() {
    //     if (!isNaN($(this).val())) {            
    //         if (add2 < 0) { add2 = add2 * -1;}
    //         if (add2 == 0) { add2 = 1;}
    //         add2 = add2 * parseFloat($(this).val());
    //         valor2 = add2;          
    //     }
    // });
    pre_unid=valor1*factor_k*precio;
    p_unid=Math.ceil(pre_unid)*1.00;
   
    cant=parseFloat($("#DlgModal_cant_mat").val());
    precio_total=p_unid*cant;
    console.log("precio: "+precio);
    // precio_tot_format= Math.ceil(precio_total)*1.00;
    // console.log("Valor multiplicado: "+valor1);
    // console.log("Valor * factor: "+valor1*factor_k);
    // console.log("Precio total: "+precio_total);  
    $("#DlgModal_txt_unidad_mat").text("S/."+p_unid.toFixed(2));
    $("#DlgModal_txt_ttotal_mat").text("S/."+precio_total.toFixed(2));
}


    
function add_item(){
    $('.error_msg').addClass('d-none').text('');
    if($("#DlgModal_id_servicio").val()=="" || $("#DlgModal_des_serv").val()==""){
        $("#DlgModal_des_serv").focus(); 
        return maquinge.notificaciones("Seleccione un servicio...", 'Sistema', 'warning');        
    }
    if($("#DlgModal_precio_unid").val()==""){
        $("#DlgModal_precio_unid").focus(); 
        return maquinge.notificaciones("Ingrese un precio...", 'Sistema', 'warning');        
    }
    MsgDlgLoadAjaxForm("form");   
    $.ajax({         
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'orden-detalle/create',
        type: 'GET',
        data:{  
            "_token"        : "{{ csrf_token() }}",          
            id_orden        : $("#DlgModal_id_orden").val(),         
            cant            : $("#DlgModal_cant").val(),
            id_servicio     : $("#DlgModal_id_servicio").val(),
            precio_unid     : $("#DlgModal_precio_unid").val()        
        },
    }).done(function (data) {
        actualizar_tabla_dinamica();
        setTimeout(function(){
            $("#DlgModal_cant").val('1');
            $("#DlgModal_des_serv, #DlgModal_id_servicio, #DlgModal_precio_unid").val('');            
            MsgDlgLoadAjaxFinish("form");
            $("#DlgModal_des_serv").focus(); 
        }, 200);
    }).fail(function (jqXHR, textStatus,errorThrown) {
        maquinge.notificaciones(textStatus, 'Sistema', 'error');     
        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
        MsgDlgLoadAjaxFinish("form"); 
    });
}

function editar_precio(id_detalle){   
    
    Swal.fire({
        customClass: 'sweetalert_editar_precio',     
        target: document.getElementById('DlgModal'),
        title: '.:: MODIFICAR CANTIDAD Y PRECIO ::.',        
        html: `<div class="form-row mt-3" style="text-align: left;">
                <div class="col-md-10 mb-3 ml-4">
                    <label class="form-label" for="new_cant">Cantidad:<span class="text-danger">*</span></label>
                    <input id="new_cant" type="number" class="form-control">
                </div>
                <div class="col-md-10 mb-3 ml-4">
                    <label class="form-label" for="new_precio">Precio:<span class="text-danger">*</span></label>
                    <input id="new_precio" type="number" class="form-control" >                            
                </div></div>`,
        confirmButtonText: 'Aceptar',
        showCancelButton: true,
        focusConfirm: false,
        preConfirm: () => {
            const cantidad = Swal.getPopup().querySelector('#new_cant').value
            const precio = Swal.getPopup().querySelector('#new_precio').value
            if (!cantidad || !precio) {
                Swal.showValidationMessage(`Ingresa la cantidad y el precio.`);
            }
            return { cantidad: cantidad, precio: precio }
        }
    }).then((result) => {
        $.ajax({ 
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            url: 'orden-detalle/'+id_detalle,
            type: 'PUT',
            data:{ 
                "_token": "{{ csrf_token() }}",
                cantidad : result.value.cantidad,
                precio: result.value.precio        
            },
            // beforeSend: function () {
            //     maquinge.notificaciones('Actualizando registro...', 'Sistema', 'info');
            // },
        }).done(function (data) {
            maquinge.notificaciones(data.msg, 'Sistema', 'success');
            actualizar_tabla_dinamica();
        }).fail(function (error, jqXHR, textStatus,errorThrown) {
            if (error.responseJSON) {
                var response = error.responseJSON;                
                if (response.msg) {
                    var mensajeError = response.msg;
                    if (response.stock_disponible !== undefined) {
                        mensajeError += ' (Stock disponible: ' + response.stock_disponible + ')';
                    }
                    maquinge.notificaciones(mensajeError, 'Sistema', 'error');
                } else {
                    maquinge.notificaciones('Error desconocido en la respuesta del servidor.', 'Sistema', 'error');
                }
            } else {
                maquinge.notificaciones('Error en la solicitud. Por favor, intente nuevamente.', 'Sistema', 'error');
            }                               
        });  
        // Swal.fire({
        //     target: document.getElementById('DlgModal'),
        //     title: result.value.cantidad+' - '+result.value.precio
        // });
    })
   
   
}

function del_ittem(id_orden_detalle){
    id_orden=$("#DlgModal_id_orden").val();
    
    $.ajax({ 
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'check_orden/'+id_orden_detalle,
        type: 'GET',            
    }).done(function (data) {
        if(data.msg=="NO"){            
            $.ajax({ 
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                url: 'orden-detalle/'+id_orden_detalle,
                type: 'DELETE',
                data:{
                    "_token": "{{ csrf_token() }}",
                    id_orden_detalle : id_orden_detalle,
                    tipo:'ELIMINAR'                        
                },
            }).done(function (data) {
                actualizar_tabla_dinamica();
            }).fail(function (error, jqXHR, textStatus,errorThrown) {
                var errors = error.responseJSON;                        
                if (errors.status === 500) {
                    maquinge.notificaciones(error.msg, 'Sistema', 'error');
                }
            });
        }else{
            var box = bootbox.prompt({
                title: "¿Porqué eliminas este registro?", 
                centerVertical: true,
                callback: function(result){                    
                    if(result){
                        $.ajax({ 
                            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                            url: 'orden-detalle/'+id_orden_detalle,
                            type: 'DELETE',
                            data:{
                                "_token": "{{ csrf_token() }}",
                                id_orden_detalle : id_orden_detalle,
                                des_elim: result,
                                tipo:'NO_ELIMINAR'        
                            }
                        }).done(function (data) {                            
                            actualizar_tabla_dinamica();                            
                        }).fail(function (error, jqXHR, textStatus,errorThrown) {
                            var errors = error.responseJSON;                        
                            if (errors.status === 500) {
                                maquinge.notificaciones(error.msg, 'Sistema', 'error');
                            }                          
                        });
                    }
                }
            });
            box.on('hidden.bs.modal', function (e) {
                if($('.modal.in')){  $('body').addClass('modal-open');}
            });
            box.init(function() {
                box.attr("id", "bootbox_del_item");
            });
        }

    }).fail(function (error, jqXHR, textStatus,errorThrown) {
        var errors = error.responseJSON;                        
        if (error.status === 500) {
            maquinge.notificaciones(errorThrown, 'OceanClub', 'error');
        }
    });
}


function add_comentario(id_orden_detalle){
    
    var box = bootbox.prompt({
        title: "Escribe aqui comentario y/o observación...", 
        centerVertical: true,
        backdrop: false,        
        callback: function(result){            
            if(result){
                $.ajax({ 
                    headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                    url: 'add_coment',
                    type: 'GET',
                    data:{                    
                        id_orden_detalle : id_orden_detalle,
                        comentario : result          
                    },
                }).done(function (data) {
                    actualizar_tabla_dinamica();
                }).fail(function (error, jqXHR, textStatus,errorThrown) {
                    var errors = error.responseJSON;                        
                    if (errors.status === 500) {
                        maquinge.notificaciones(error.msg, 'Sistema', 'error');
                    }                                       
                });                
            }        
        },       
    });

    box.on('hidden.bs.modal', function (e) {
        if($('.modal.in')){  $('body').addClass('modal-open');}
    });
    box.init(function() {
        box.attr("id", "bootbox_add_comentario");
    });
}

function guardar_orden(){
    
    var table = $('#tableDynamic_ittems').DataTable();
    var table_length = table.data().count();
    id_orden=$("#DlgModal_id_orden").val();

    if($("#DlgModal_procedencia").val()==""){
        $("#DlgModal_procedencia").focus(); 
        return maquinge.notificaciones("Seleccione un local de procedencia.", 'Sistema', 'warning');        
    }
    if($("#DlgModal_fch_create").val()==""){
        $("#DlgModal_fch_create").focus(); 
        return maquinge.notificaciones("Seleccione una fecha de creación de Orden de servicio.", 'Sistema', 'warning');        
    }
    if($("#DlgModal_fecha_entrega").val()==""){
        $("#DlgModal_fecha_entrega").focus(); 
        return maquinge.notificaciones("Seleccione una fecha de entrega.", 'Sistema', 'warning');        
    }
    if($("#DlgModal_hora_entrega").val()==""){
        $("#DlgModal_hora_entrega").focus(); 
        return maquinge.notificaciones("Seleccione una fecha de entrega.", 'Sistema', 'warning');        
    }
    if($("#DlgModal_txt_id_cliente").val()==""){
        $("#DlgModal_txt_id_cliente").focus(); 
        return maquinge.notificaciones("Selecciona un Cliente ó ingresa un nuevo cliente.", 'Sistema', 'warning');        
    }   
    if($("#DlgModal_material").val()==""){
        $("#DlgModal_material").focus(); 
        return maquinge.notificaciones("Selecciona el Material.", 'Sistema', 'warning');        
    }
    if($("#DlgModal_plano").val()==""){
        $("#DlgModal_plano").focus(); 
        return maquinge.notificaciones("Selecciona si incluye plano.", 'Sistema', 'warning');        
    }
    if($("#DlgModal_des_gen").val()==""){
        $("#DlgModal_des_gen").focus(); 
        return maquinge.notificaciones("Agrega una descripción general a esta orden de servicio.", 'Sistema', 'warning');        
    }   

    if(table_length==0){
        $("#DlgModal_id_servicio").focus(); 
        return maquinge.notificaciones("Tabla de servicios esta vacia.", 'Sistema', 'warning');        
    }  

    if(!$('input[name="radio_material"]:checked').length){        
        return maquinge.notificaciones("Selecciona si incluye material.", 'Sistema', 'warning');
    }
    
    var formData = new FormData($("#form")[0]);
    formData.append('_token', $('input[name=_token]').val());
    formData.append('id_orden',id_orden);
    formData.append('procedencia',$("#DlgModal_procedencia").val());
    formData.append('fch_create',$("#DlgModal_fch_create").val());
    formData.append('fecha_entrega',$("#DlgModal_fecha_entrega").val());
    formData.append('hora_entrega',$("#DlgModal_hora_entrega").val());
    formData.append('id_cliente',$("#DlgModal_txt_id_cliente").val());   
    formData.append('des_gen',($("#DlgModal_des_gen").val()).toUpperCase());
    
    formData.append('igv',$("#chk_igv").val());
    formData.append('material',$("#chk_material").val());
    formData.append('plano',$("#chk_plano").val());
    formData.append('correccion',$("#chk_correccion").val());
    formData.append('rapido',$("#chk_rapido").val());
    formData.append('chek_material',$('input[name="radio_material"]:checked').val());
    
    jQuery.each(jQuery('#file')[0].files, function(i, file) {
        formData.append('file[]', file);
    });
    jQuery.each(jQuery('#file2')[0].files, function(i, file) {
        formData.append('file2[]', file);
    });
    jQuery.each(jQuery('#file3')[0].files, function(i, file) {
        formData.append('file3[]', file);
    });
    jQuery.each(jQuery('#file4')[0].files, function(i, file) {
        formData.append('file4[]', file);
    });
    jQuery.each(jQuery('#file5')[0].files, function(i, file) {
        formData.append('file5[]', file);
    });
    

    MsgDlgLoadAjaxForm("form");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'orden',
        type: 'POST',
        dataType: "html",
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
    }).done(function (data) { 
        if(data.msg==0){
            maquinge.notificaciones('Orden de servicio actualizada.', 'Sistema', 'success');
            MsgDlgLoadAjaxFinish("form");
            close_modal();
        }else{
            maquinge.notificaciones('Orden de servicio actualizada.', 'Sistema', 'success');
            $("#DlgModal_codigo").val((id_orden).padStart(6, "0"));
            setTimeout(function(){    
                MsgDlgLoadAjaxFinish("form"); close_modal();
            }, 500);
        }  
        $('#tabla').DataTable().ajax.url("{{ route('orden.list') }}").load();        
    }).fail(function (data,jqXHR, textStatus, errorThrown) {
        maquinge.notificaciones("* Error interno.", 'Sistema', 'error');        
        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
        MsgDlgLoadAjaxFinish("form"); 
    });    
}

function AbrirOrdenServicio(id_orden,id_user,rol,id_cliente){  
    MsgDlgLoadAjaxForm("form");
    if(rol=='ADMINISTRADOR' || rol=='CAJA'){        
        $("#DlgModal").modal('show');        
        $("#DlgModal_id_orden").val(id_orden);
        select_buscar_cliente(id_cliente);
        actualizar_tabla_dinamica();
        

        $.ajax({
            url: 'orden/'+id_orden,
            type: 'GET',        
        }).done(function(data){
            $("#DlgModal_codigo").val(data.codigo);
            $("#DlgModal_procedencia").val(data.procedencia);
            $("#DlgModal_fch_create").val(data.fch_create);
            $("#DlgModal_id_user").val(data.usuario);
            $("#DlgModal_fecha_entrega").val(data.fecha_entrega);
            $("#DlgModal_hora_entrega").val(data.hora_entrega);
            $("#DlgModal_des_gen").val(data.des_gen);

            if(data.igv==1) { $("#chk_igv").val("1"); $('#chk_igv').prop('checked', true);
            } else {  $("#chk_igv").val("0"); $('#chk_igv').prop('checked', false); }

            if(data.material==1) { $("#chk_material").val("1"); $('#chk_material').prop('checked', true);
            } else {  $("#chk_material").val("0"); $('#chk_material').prop('checked', false); }

            if(data.plano==1) { $("#chk_plano").val("1"); $('#chk_plano').prop('checked', true);
            } else {  $("#chk_plano").val("0"); $('#chk_plano').prop('checked', false); }

            if(data.correccion==1) { $("#chk_correccion").val("1"); $('#chk_correccion').prop('checked', true);
            } else {  $("#chk_correccion").val("0"); $('#chk_correccion').prop('checked', false);}

            if(data.rapido==1) { $("#chk_rapido").val("1"); $('#chk_rapido').prop('checked', true);
            } else {  $("#chk_rapido").val("0"); $('#chk_rapido').prop('checked', false); }
            
            
            if(data.ruta_url!=""){                
                $('#alert').html('Plano: '+data.ruta_url).removeAttr('class').addClass('xd2');
                $("#label_file").text('Seleccionar archivo 1...');
                $('#ver_archivo_1').attr('onClick', 'VerPLanoPdf("'+data.ruta_url+'");');
            }
            if(data.ruta_url2!=""){                
                $('#alert2').html('Plano: '+data.ruta_url2).removeAttr('class').addClass('xd2');
                $("#label_file2").text('Seleccionar archivo 2...');
                $('#ver_archivo_2').attr('onClick', 'VerPLanoPdf("'+data.ruta_url2+'");');
            }
            if(data.ruta_url3!=""){                
                $('#alert3').html('Plano: '+data.ruta_url3).removeAttr('class').addClass('xd2');
                $("#label_file3").text('Seleccionar archivo 3...');
                $('#ver_archivo_3').attr('onClick', 'VerPLanoPdf("'+data.ruta_url3+'");');
            }
            if(data.ruta_url4!=""){                
                $('#alert4').html('Plano: '+data.ruta_url4).removeAttr('class').addClass('xd2');
                $("#label_file4").text('Seleccionar archivo 4...');
                $('#ver_archivo_4').attr('onClick', 'VerPLanoPdf("'+data.ruta_url4+'");');
            }
            if(data.ruta_url5!=""){                
                $('#alert5').html('Plano: '+data.ruta_url5).removeAttr('class').addClass('xd2');
                $("#label_file5").text('Seleccionar archivo 5...');
                $('#ver_archivo_5').attr('onClick', 'VerPLanoPdf("'+data.ruta_url5+'");');
            }

            if(data.pago_caja!=0){
                $("form#form :input").prop("disabled", true);
            }

            if(data.chek_material!=""){   
                $('input[name="radio_material"][value="' + data.chek_material + '"]').prop('checked', true);
            }
            
        }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
            var errors = jqXHR.responseJSON; 
            if (jqXHR.status === 500) {               
                var errorMessage = (errors.msg && errors.msg.length > 80) ? errors.msg.substring(0, 80) + '...' : errors.msg || "Error interno del servidor.";
                maquinge.notificaciones(errorMessage, 'Sistema', 'error');
            } else if (jqXHR.status === 422) {               
                var validationErrors = errors.errors;
                var errorList = '';
                for (var key in validationErrors) {
                    if (validationErrors.hasOwnProperty(key)) {
                        errorList += validationErrors[key].join(', ') + '<br>';
                    }
                }
                maquinge.notificaciones(errorList || "Ocurrió un error inesperado.", 'Sistema', 'error');
            } else {               
                maquinge.notificaciones("Ocurrió un error inesperado: " + errorThrown, 'Sistema', 'error');
            }      
        });
        
    }else{  
        return maquinge.notificaciones("Acceso denegado.", 'Sistema', 'warning');
    }
    setTimeout(function(){ 
        MsgDlgLoadAjaxFinish("form"); 
    }, 1000);
    
}

function eliminar_plano(num_plano){
    id_orden=$("#DlgModal_id_orden").val();
    

    Swal.fire({
        title: "¿Esta seguro de eliminar el Plano.?",
        text: "Esta acción no se puede deshacer!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        target: document.getElementById('DlgModal'),
    }).then(function(result){
        if (result.value){
            $.ajax({
                url: 'orden/'+id_orden+'/edit',
                type: 'GET',
                data: {num_plano:num_plano}        
            }).done(function(data){
                if(num_plano==1){
                    if(data.msg==1){
                        $('#alert').html("Archivo "+num_plano+" eliminado").removeAttr('class').addClass('xd');
                        $("#label_file").text('Seleccionar archivo...');
                        $('#tabla').DataTable().ajax.url("{{ route('orden.list') }}").load();
                        maquinge.notificaciones("Archivo "+num_plano+" eliminado...", 'Sistema', 'success');
                    }else{
                        $('#alert').html("").removeAttr('class').addClass('xd');
                        $("#file").val('');
                        $("#label_file").text('Seleccionar archivo 1...');
                        maquinge.notificaciones("No tiene archivo...", 'Sistema', 'error');
                    }  
                }else{
                    if(data.msg==1){
                        $('#alert'+num_plano).html("Archivo "+num_plano+" eliminado").removeAttr('class').addClass('xd');
                        $("#label_file"+num_plano).text('Seleccionar archivo '+num_plano+'...');
                        $('#tabla').DataTable().ajax.url("{{ route('orden.list') }}").load();
                        maquinge.notificaciones("Archivo "+num_plano+" eliminado...", 'Sistema', 'success');
                    }else{
                        $('#alert'+num_plano).html("").removeAttr('class').addClass('xd');
                        $("#file"+num_plano).val('');
                        $("#label_file"+num_plano).text('Seleccionar archivo '+num_plano+'...');
                        maquinge.notificaciones("No tiene archivo...", 'Sistema', 'error');
                    } 
                }
               

            }).fail( function(error, jqXHR, textStatus, errorThrown ) {                   
                var errors = error.responseJSON;                        
                if (errors.status === 500) {
                    maquinge.notificaciones(error.msg, 'Sistema', 'error');
                }       
            });
        }
    });
}

function qr_code_gen(){
    id_orden= $("#DlgModal_id_orden").val();
    $("#myModal_print_ticket").modal('show');
    Swal.fire({
        customClass: 'sweetalert_editar_precio',     
        target: document.getElementById('DlgModal'),
        title: '.:: EXPORTAR EN PDF O IMAGEN ::.',        
        html: `<div class="form-row m-3 justify-content-center">
                <div class="row">
                <a target="_blank" href="print-ticket-qr-pdf/${id_orden}" class='btn' style='padding: 0px 0px 0px 0px;' title='Pdf'>
                        <img src='images/pdf.png' alt='Imagen del botón' width='75' height='70'>
                </a>
                <a target="_blank" href="print-ticket-qr-png/${id_orden}" class='btn ml-5' style='padding: 0px 0px 0px 0px;' title='Imagen'>
                        <img src='images/png.png' alt='Imagen del botón'  width='56' height='61'>
                </a></div></div>`,
        confirmButtonText: "Cancelar"
       
    });
    
    //var new_id= "{!! encrypt("+id_orden+") !!}";
    //window.open('print-ticket/'+id_orden, '_blank').focus();
}

function ticket_proforma(){
    id_orden= $("#DlgModal_id_orden").val();
    window.open('print-ticket-proforma/'+id_orden, '_blank').focus();
}

function hoja_produccion(id_orden){    
    window.open('print-hoja-produccion/'+id_orden, '_blank').focus();
}

function actualizar_tabla_dinamica(){
    id_orden=$("#DlgModal_id_orden").val();
    $('#tableDynamic_ittems').DataTable().ajax.url("{{ route('tabla_orden_detalle') }}"+"?id_orden="+id_orden).load();
}


function OpenModal(){
   
    global_modal=1;
    $("#DlgModal").modal('show');
    $("#DlgModal_spinner").block({ 
        message:"<div class='d-flex justify-content-center'>"
                +    "<div class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></div>"
                +    "<strong>.: Cargando :. </strong>"
                +"</div>",
        css: { background: 'white', width: '33%', opacity:'0.5', 'margin-top':'48%', 'margin-left':'35%'}
    });
    
    setTimeout(function(){
        GenerarOrdenTemporal();   
        $( "#DlgModal_spinner" ).unblock();
    }, 1000);
}

function GenerarOrdenTemporal(){
    var id_user = "{!! Auth::user()->id !!}";

    $.ajax({        
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'orden-detalle',
        type: 'POST',
        data:{
            "_token": "{{ csrf_token() }}",            
            id_user : id_user,
        }        
    }).done(function(data){
        $("#DlgModal_id_orden").val(data.id_orden);        
    }).fail( function(data, jqXHR, textStatus, errorThrown ) {
        maquinge.notificaciones(textStatus+" <br>* Base de Datos", 'Sistema', 'error');        
        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
    });
    
}

function close_modal(){
    MsgDlgLoadAjaxForm("form");
    if($("#DlgModal_codigo").val()==""){
        BorrarOrdenTemporal();
    }else{
        borrar_registros_orden_no();
    }
    
    setTimeout(function(){
        $("#DlgModal").modal('hide');
        $('.error_msg').addClass('d-none').text('');  
        $("#form")[0].reset();
        $("#file").val(null);
        $('#alert').html('').removeAttr('class');
        $("#label_file").html('');
        $("#chk_correccion,#chk_rapido,#chk_igv,#chk_material,#chk_plano").val("0");
        $("#DlgModal_id_orden,#DlgModal_codigo,#DlgModal_txt_id_cliente").val('');
        $('#tableDynamic_ittems').DataTable().ajax.url("{{ route('tabla_orden_detalle') }}"+"?id_orden=0").load(); 
        $("form#form :input").prop("disabled", false);     
        MsgDlgLoadAjaxFinish("form");        
    }, 1000);
}

function BorrarOrdenTemporal(){  // borrar Orden de servicio temporal
    id_orden= $("#DlgModal_id_orden").val();
    if($("#DlgModal_codigo").val()==''){
        $.ajax({        
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            url: 'borrar-orden-temporal/'+id_orden,
            type: 'GET',
            data:{
                "_token": "{{ csrf_token() }}",
            }            
        }).done(function(data){        
            
        }).fail( function(jqXHR, textStatus, errorThrown ) {
            maquinge.notificaciones(textStatus+" <br>* Base de Datos", 'Sistema', 'error');        
            initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
        });
    } 
    
}
function borrar_registros_orden_no(){// eliminar pedido orden no
    id_orden= $("#DlgModal_id_orden").val(); 
    $.ajax({        
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'borrar-orden-no/'+id_orden,
        type: 'GET',
        data:{
            "_token": "{{ csrf_token() }}",
        }                  
    }).done(function(data){        
        console.log(data.msg);
    }).fail( function(jqXHR, textStatus, errorThrown ) {
        maquinge.notificaciones(textStatus+" <br>* Base de Datos", 'Sistema', 'error');        
        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
    });
}

function VerPLanoPdf(url){
    window.open(url, '_blank').focus();
}


function print_boleta(){
    id_orden= $("#DlgModal_id_orden").val();
    window.open('print_boleta/'+id_orden);
}

function enviar_email(){
    id_orden= $("#DlgModal_id_orden").val(); 
    $.ajax({        
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'enviar-email/'+id_orden,
        type: 'GET',
        data:{
            "_token": "{{ csrf_token() }}",
        }                  
    }).done(function(data){    
        if(data.cod==1){
            maquinge.notificaciones(data.msg, 'Sistema.', 'success');
        }else{
            maquinge.notificaciones(data.msg, 'Sistema.', 'error');
        }
        
    }).fail( function(jqXHR, textStatus, errorThrown ) {
        maquinge.notificaciones(textStatus+" <br>* Interno", 'Sistema', 'error');        
        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
    });
}

function realizar_entrega(id_orden){

    Swal.fire({
        title: "¿Entregar este producto?",
        text: "Esta acción no se puede deshacer!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar"
    }).then(function(result){
        if (result.value){
            $.ajax({        
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                url: 'realizar_entrega_producto/'+id_orden,
                type: 'GET',
                data:{
                    "_token": "{{ csrf_token() }}",
                }                  
            }).done(function(data){  
                maquinge.notificaciones(data.msg, 'Sistema.', 'success');
                $('#tabla').DataTable().ajax.url("{{ route('orden.list') }}").load();
            }).fail( function(jqXHR, textStatus, errorThrown ) {
                maquinge.notificaciones(textStatus+" <br>* Interno", 'Sistema', 'error');        
                initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
            });
        }
    });
    
}

$('#DlgModal_des_serv').typeahead({    
    source: function(query, process) {
        objects = [];
        map = {};      
        $.getJSON('serv_autocompletar/' + query , null, function (jsonData) {
            $.each(jsonData, function(i, object) {
                var displayText = object.descripcion;
                // Opcional: mostrar el local en las sugerencias para confirmación
                if (object.local && object.local.trim() !== '') {
                    displayText += ' -- ' + object.local;
                }
                map[displayText] = object;              
                objects.push(displayText);
            });
            process(objects);
        }).fail(function() {
            process([]);
        });
    },
    items: 15,
    minLength: 2,
    delay: 300,
    highlighter: function (item) {
        var regex = new RegExp( '(' + this.query + ')', 'gi' );
        return item.replace( regex, "<strong>$1</strong>" );
    },
    updater: function(item) {
        $('#DlgModal_id_servicio').val(map[item].id);
        return map[item].descripcion;
        $('#DlgModal_precio_unid').focus();
    }
}); 

$('#DlgModal_des_producto').typeahead({
    source: function(query, process) {
        objects = [];
        map = {};
        $.getJSON('productos_autocompletar/'+query, null,function ( jsonData ){
            $.each(jsonData, function(i, object) {
                map[object.label] = object;
                objects.push(object.label);
            });
            process(objects);
        });
    },
    items: 15,
    minLength: 2,
    delay: 300,
    highlighter: function (item) {
        var regex = new RegExp( '(' + this.query + ')', 'gi' );
        return item.replace( regex, "<strong>$1</strong>" );
    },
    updater: function(item) {
        $('#DlgModal_id_producto').val(map[item].id);
        return map[item].label;        
    }
});

function Open_modal_Producto(){
    $("#DldModalProductos").modal('show');   
}

function close_modal_producto(){
    $("#dlg_form_producto")[0].reset();
    $("#DlgModal_id_producto").val('');
    $("#DldModalProductos").modal('hide');
}

function siguiente_orden(){
    id_producto=$("#DlgModal_id_producto").val();
    if($("#DlgModal_id_producto").val()==""){
        $("#DlgModal_id_producto").focus(); 
        return maquinge.notificaciones("Ingrese descripcion del Producto", 'Sistema', 'warning');        
    }
    if($("#DlgModal_des_producto").val()==""){
        $("#DlgModal_des_producto").focus(); 
        return maquinge.notificaciones("Ingrese descripcion del Producto", 'Sistema', 'warning');        
    }

    var id_user = "{!! Auth::user()->id !!}";
    $.ajax({        
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'orden-detalle',
        type: 'POST',
        data:{
            "_token": "{{ csrf_token() }}",            
            id_user : id_user,
        }        
    }).done(function(data){
        $("#DlgModal_id_orden").val(data.id_orden);

        global_modal=1;
        $("#DlgModal").modal('show');
        $("#DlgModal_spinner").block({ 
            message:"<div class='d-flex justify-content-center'>"
                    +    "<div class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></div>"
                    +    "<strong>.: Cargando :. </strong>"
                    +"</div>",
            css: { background: 'white', width: '33%', opacity:'0.5', 'margin-top':'48%', 'margin-left':'35%'}
        });

        var modal_dos = $("#DlgModal").modal();
        modal_dos.on('shown.bs.modal', function (e) {
            $('body').addClass('modal-open');
        });

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "autocompletar_orden/"+id_producto+"/"+data.id_orden,
            type: 'GET'        
        })
        .done(function(data) {
            actualizar_tabla_dinamica();
            $("#DlgModal_des_gen").val('Producto: '+data[0].desc+'.');
            $("#chk_igv,#chk_material,#chk_plano").val("1");
            $('#chk_igv,#chk_material,#chk_plano').prop('checked', true);
            $('#alert').html('Plano: '+data[0].ruta_url).removeAttr('class').addClass('xd2');
            $("#label_file").text('Seleccionar archivo 1...');
            $('#ver_archivo_1').attr('onClick', 'VerPLanoPdf("'+data[0].ruta_url+'");');                    
        });
        
        setTimeout(function(){
            $( "#DlgModal_spinner" ).unblock();
        }, 1000);
        
    })
    .fail(function(jqXHR, textStatus, errorThrown) { 
        var error = jqXHR.responseJSON;
        if(error){
            maquinge.notificaciones(error.message, 'Sistema', 'warning');
        }else{
            maquinge.notificaciones('Error desconocido', 'Sistema', 'warning');
        }
    })
    .always(function() {
        close_modal_producto();
    });
   
    
}


</script>
@endpush

