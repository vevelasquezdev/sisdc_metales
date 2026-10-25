@extends('layouts.app')
@section('titulo') Cotizacion de servicio @endsection


@section('contenido')
<div class="row">
      
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Tabla</i></span>&nbsp;&nbsp;Cotizaciones
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
                            Nueva Cotización
                        </button>
                        <table class="ml-3 mt-0 mb-1">
                            <tr>
                              <td>
                                 <input type='text'  id='search_fromdate' class="form-control form-control-sm" value="{{ date( "d-m-Y", strtotime( "-5 day", strtotime( date( "d-m-Y" ) ) ) ) }}" placeholder='desde...'>
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
                                    <th>Procedencia</th>
                                    <th>Fecha</th>
                                    <th>Cliente - Nombres y Apellidos</th>
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">.: COTIZACIÓN :.</h5>
                <button onclick="close_modal();" type="button" class="close" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
               <form id="form" enctype="multipart/form-data">

                    {{-- HIDDEN --}}
                    <input type="hidden" id="DlgModal_id_orden">
                    <input type="hidden" id="DlgModal_txt_id_cliente">
                    <input type="hidden" id="DlgModal_id_servicio" class="form-control text-uppercase" value="">

                    <style>
                        :root { --dc-red: #CC1E1E; --dc-lbl: #f5f5f5; --dc-border: #d0d0d0; }

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
                            padding: 7px 14px;
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

                        .dc-ficha { width: 100%; border-collapse: collapse; font-size: 12.5px; }
                        .dc-ficha td { padding: 5px 10px; border: 1px solid var(--dc-border); vertical-align: middle; }
                        .dc-ficha .lbl { background: var(--dc-lbl); font-weight: 700; white-space: nowrap; color: #333; width: 1%; }
                        .dc-ficha .sep { height: 4px; background: #f0f0f0; padding: 0; }

                        #tableDynamic_ittems thead th {
                            background: #1a1a1a !important;
                            color: #fff !important;
                            font-size: 11.5px;
                            font-weight: 600;
                            border-color: #333 !important;
                            white-space: nowrap;
                        }

                        .dc-file-row {
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            padding: 8px 14px;
                        }
                        .dc-file-label {
                            min-width: 110px;
                            font-size: 11.5px;
                            font-weight: 700;
                            color: #555;
                            white-space: nowrap;
                        }
                        .dc-file-row .custom-file { flex: 1; }
                        .dc-file-row .custom-file-label { font-size: 12px; }

                        .dc-switches {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 10px 24px;
                            padding: 8px 14px;
                            border-top: 1px solid var(--dc-border);
                            align-items: center;
                        }
                        .dc-total { font-size: 15px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
                        .dc-total .badge { font-size: 14px; padding: 4px 10px; }

                        /* bloque servicio cliente */
                        .bloque-servicio { background: #fafafa; border: 1px solid var(--dc-border); border-radius: 4px; padding: 8px 10px; margin-bottom: 8px; }
                    </style>

                    <div class="form-row pl-2 pr-2">

                        {{-- ══ Botón lateral ══════════════════════════════════════ --}}
                        <div class="col-md-1 col-lg-1 col-xl-1 d-flex flex-column align-items-center pt-2">
                            <a href="javascript:void(0);" onclick="print_boleta();"
                            class="btn btn-primary btn-icon waves-effect waves-themed mb-2"
                            data-toggle="tooltip" title="Imprimir Cotización">
                            <i class="fal fa-print"></i>
                            </a>
                        </div>

                        {{-- ══ COLUMNA PRINCIPAL ══════════════════════════════════ --}}
                        <div class="col-md-11 col-lg-11 col-xl-11">

                            {{-- ┌─────────────────────────────────────────────────┐
                                │  1. CABECERA                                    │
                                └─────────────────────────────────────────────────┘ --}}
                            <div class="dc-card">
                                <div class="dc-card-body py-2">
                                    <div class="d-flex align-items-center flex-wrap" style="gap:12px;">

                                        <img src="{{ asset('smartadmin/dist/img/DC_PNG.jpg') }}"
                                            style="height:46px;" alt="Logo">

                                        <div style="min-width:130px;">
                                            <div style="font-size:10px;font-weight:700;color:#888;text-transform:uppercase;margin-bottom:2px;">
                                                N° Cotización <span class="text-danger">*</span>
                                            </div>
                                            <input id="DlgModal_codigo" name="DlgModal_codigo" type="text"
                                                class="form-control text-uppercase"
                                                style="font-weight:700;font-size:20px;height:36px;border:0;background:ghostwhite;color:#CC1E1E;text-align:center;"
                                                readonly>
                                        </div>

                                        <div style="min-width:130px;">
                                            <div style="font-size:10px;font-weight:700;color:#888;text-transform:uppercase;margin-bottom:2px;">
                                                Procedencia <span class="text-danger">*</span>
                                            </div>
                                            <select id="DlgModal_procedencia" name="DlgModal_procedencia"
                                                class="form-control form-control-sm jquery_field text-uppercase" required>
                                                <option value="">Selecciona</option>
                                                <option value="RIO_SECO">RIO SECO</option>
                                                <option value="ZOLEZZI">ZOLEZZI</option>
                                            </select>
                                            <div id="error_DlgModal_procedencia" class="error_msg text-danger d-none"></div>
                                        </div>

                                        <div style="flex:1;min-width:180px;">
                                            <div style="font-size:10px;font-weight:700;color:#888;text-transform:uppercase;margin-bottom:2px;">
                                                Asesor <span class="text-danger">*</span>
                                            </div>
                                            <input id="DlgModal_id_user" name="DlgModal_id_user" type="text"
                                                class="form-control form-control-sm text-uppercase"
                                                value="{!! Auth::user()->name !!}" readonly>
                                            <div id="error_DlgModal_id_user" class="error_msg text-danger d-none"></div>
                                        </div>

                                        <div style="min-width:140px;">
                                            <div style="font-size:10px;font-weight:700;color:#888;text-transform:uppercase;margin-bottom:2px;">
                                                Fecha <span class="text-danger">*</span>
                                            </div>
                                            <input id="DlgModal_fch_create" name="DlgModal_fch_create" type="text"
                                                class="form-control form-control-sm text-uppercase"
                                                value="{{ date('d-m-Y') }}">
                                            <div id="error_DlgModal_fch_create" class="error_msg text-danger d-none"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- ┌─────────────────────────────────────────────────┐
                                │  2. FICHA DE COTIZACIÓN                         │
                                └─────────────────────────────────────────────────┘ --}}
                            <div class="dc-card">
                                <div class="dc-card-header">
                                    <i class="fal fa-file-alt"></i> Datos de la Cotización
                                </div>
                                <div class="p-0">
                                    <table class="dc-ficha">

                                        {{-- Nombre completo --}}
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

                                        {{-- RUC · Razón Social --}}
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

                                        {{-- Teléfono · Email --}}
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

                                        {{-- Separador --}}
                                        <tr><td colspan="4" class="sep"></td></tr>

                                        {{-- Fecha Entrega · Hora Entrega --}}
                                        <tr>
                                            <td class="lbl">FECHA ENTREGA <span class="text-danger">*</span></td>
                                            <td>
                                                <input id="DlgModal_fecha_entrega" name="DlgModal_fecha_entrega"
                                                    type="text" class="form-control form-control-sm text-uppercase"
                                                    value="{{ date('d-m-Y') }}"
                                                    style="font-weight:700;color:#CC1E1E;max-width:160px;">
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

                                        {{-- Descripción General --}}
                                        <tr>
                                            <td class="lbl">DESCRIPCIÓN GENERAL <span class="text-danger">*</span></td>
                                            <td colspan="3">
                                                <input id="DlgModal_des_gen" type="text"
                                                    class="form-control form-control-sm text-uppercase"
                                                    placeholder="Descripción general...">
                                            </td>
                                        </tr>

                                        {{-- Observaciones --}}
                                        <tr>
                                            <td class="lbl">OBSERVACIONES</td>
                                            <td colspan="3">
                                                <textarea id="DlgModal_observacion" rows="2"
                                                    class="form-control form-control-sm text-uppercase"
                                                    style="resize:vertical;"></textarea>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>

                            {{-- ┌─────────────────────────────────────────────────┐
                                │  3. SERVICIOS PARA EL CLIENTE                   │
                                └─────────────────────────────────────────────────┘ --}}
                            <div class="dc-card">
                                <div class="dc-card-header">
                                    <i class="fal fa-list"></i> Descripción de Servicio para el Cliente
                                </div>
                                <div class="dc-card-body">
                                    <form id="form_pdf_servicio_cliente">
                                        <div id="contenedor_servicios">
                                            <!-- BLOQUE 1 -->
                                            <div class="bloque-servicio form-row mb-2" data-index="1">
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label mb-1" style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;">Descripción Servicio</label>
                                                    <input type="text" name="descripcion[]"
                                                        class="form-control form-control-sm text-uppercase des_serv"
                                                        placeholder="Servicio..." autocomplete="off">
                                                </div>
                                                <div class="col-md-1 mb-2">
                                                    <label class="form-label mb-1" style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;">Cant.</label>
                                                    <input type="number" min="1" name="cantidad[]"
                                                        class="form-control form-control-sm cantidad" value="1">
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label mb-1" style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;">Precio Unit.</label>
                                                    <input type="text" name="precio_unitario[]"
                                                        class="form-control form-control-sm precio_unitario"
                                                        onkeypress="return isNumberKey(event)" placeholder="0.00">
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label mb-1" style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;">SubTotal</label>
                                                    <input type="text" name="subtotal[]"
                                                        class="form-control form-control-sm subtotal"
                                                        placeholder="0.00" readonly>
                                                </div>
                                                <div class="col-md-1 mb-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-sm btn-danger btn-block eliminar-bloque">
                                                        <i class="fal fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" id="btnAgregarServicio" class="btn btn-sm btn-info">
                                                <i class="fal fa-plus mr-1"></i> Agregar Servicio
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- ┌─────────────────────────────────────────────────┐
                                │  4. SERVICIOS INTERNOS DC METALES               │
                                └─────────────────────────────────────────────────┘ --}}
                            <div class="dc-card">
                                <div class="dc-card-header">
                                    <i class="fal fa-cogs"></i> Servicios Internos DC Metales
                                </div>
                                <div class="dc-card-body">

                                    {{-- Fila agregar ítem --}}
                                    <div class="d-flex flex-wrap align-items-end mb-3" style="gap:10px;">
                                        <div style="min-width:80px;">
                                            <label class="form-label mb-1" style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;">
                                                Cant. <span class="text-danger">*</span>
                                            </label>
                                            <input id="DlgModal_cant" type="number" min="1"
                                                class="form-control form-control-sm text-uppercase" value="1" required>
                                            <div id="error_DlgModal_cant" class="error_msg text-danger d-none"></div>
                                        </div>
                                        <div style="flex:2;min-width:200px;">
                                            <label class="form-label mb-1" style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;">
                                                Descripción Servicio <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="DlgModal_des_serv"
                                                placeholder="Buscar servicio..."
                                                class="typeahead form-control form-control-sm text-uppercase"
                                                autocomplete="off"/>
                                            <div id="error_DlgModal_des_serv" class="error_msg text-danger d-none"></div>
                                        </div>
                                        <div style="min-width:110px;">
                                            <label class="form-label mb-1" style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;">
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
                                    
                                    <div class="dc-total">
                                        TOTAL &nbsp;<strong id="lbl-moneda" style="color:#CC1E1E;">S/.</strong>
                                        <span id="DlgModal_txt_ttotal" class="badge badge-info"></span>
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
                                    
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="chk_usd" value="0">
                                        <label class="custom-control-label" for="chk_usd">Mostrar en Dólares (USD)</label>
                                    </div>
                                </div>

                            </div>

                            {{-- ┌─────────────────────────────────────────────────┐
                                │  5. ARCHIVO ADJUNTO                             │
                                └─────────────────────────────────────────────────┘ --}}
                            <div class="dc-card">
                                <div class="dc-card-header">
                                    <i class="fal fa-paperclip"></i> Archivo Adjunto
                                    <small class="ml-1" style="font-weight:400;text-transform:none;color:#888;">(.pdf / .jpg / .jpeg / .dwg / .png)</small>
                                </div>
                                <div class="dc-file-row">
                                    <span class="dc-file-label">
                                        <i class="fal fa-file mr-1" style="color:#CC1E1E;"></i> Archivo 1
                                    </span>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input"
                                            id="file" name="file"
                                            accept="pdf|jpg|jpeg|dwg|png"
                                            data-file_types="pdf|jpg|jpeg|dwg|png">
                                        <label id="label_file" class="custom-file-label" for="file">
                                            Seleccionar archivo 1...
                                        </label>
                                    </div>
                                    <a href="javascript:void(0);" onclick="eliminar_plano(1)"
                                        class="btn btn-sm btn-outline-danger btn-icon waves-effect waves-themed"
                                        data-toggle="tooltip" title="Eliminar archivo 1">
                                        <i class="fal fa-trash"></i>
                                    </a>
                                    <a href="javascript:void(0);" id="ver_archivo_1"
                                        class="btn btn-sm btn-outline-info btn-icon waves-effect waves-themed"
                                        data-toggle="tooltip" title="Ver archivo 1">
                                        <i class="fal fa-eye"></i>
                                    </a>
                                    <div id="alert" style="font-size:11px;"></div>
                                </div>
                            </div>

                        </div>{{-- /col-md-11 --}}
                    </div>
                </form>
            </div>

            <div class="modal-footer border-faded">                
                <button id="BtnGuardarpedido" onclick="guardar_orden();" type="button" class="btn btn-primary">GUARDAR</button>
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
                    <button onclick="select_buscar_cliente(0);" type="button" class="btn btn-primary">Seleccionar</button>
                    <button onclick="close_modal_buscar_cliente();" type="button" class="btn btn-secondary">Cerrar</button>
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
                    <button onclick="guardar_edit_material_cotizacion();" type="button" class="btn btn-primary">Guardar</button>
                    <button onclick="close_modal_edit_material();" type="button" class="btn btn-secondary">Cerrar</button>
                </div>
            </form>            
        </div>
    </div>
</div>


@endsection
@push('scripts')

<script src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>

<script src="{{ asset('js/cliente.js') }}"></script>
<script src="{{ asset('js/validar_planos.js') }}"></script>
<script>

var global_modal=0; 
var user_rol = "{!! Auth::user()->rol !!}"; 
var user_name = "{!! Auth::user()->name !!}";
window.onbeforeunload = function(e) { /// al actualizar
    if(global_modal==0){
        console.log('modal cerrado');
    }else{
        console.log('modal abierto'); close_modal();
    }    
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

let maxBloques = 3;

$("#btnAgregarServicio").click(function () {
    let total = $(".bloque-servicio").length;
    if(total >= maxBloques){
        return maquinge.notificaciones("Solo se permiten 3 servicios.",'Sistema','warning');
    }

    let nuevo = `
        <div class="form-row bloque-servicio mb-2">
            <div class="col-md-6 mb-3">
                <label class="form-label">Descripcion Servicio:</label>
                <input type="text" name="descripcion[]" class="form-control text-uppercase des_serv" placeholder="Servicio..." autocomplete="off">
            </div>

            <div class="col-md-1 mb-3">
                <label class="form-label">Cantidad:</label>
                <input type="number" min="1" name="cantidad[]" class="form-control cantidad" value="1">
            </div>

            <div class="col-md-2 mb-3">
                <label class="form-label">Precio Unid.</label>
                <input type="text" name="precio_unitario[]" class="form-control precio_unitario" onkeypress="return isNumberKey(event)" placeholder="00.00">
            </div>

            <div class="col-md-2 mb-3">
                <label class="form-label">SubTotal.</label>
                <input type="text" name="subtotal[]" class="form-control subtotal" onkeypress="return isNumberKey(event)" placeholder="00.00">
            </div>

            <div class="col-md-1 mb-3">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-danger btn-block eliminar-bloque"><i class="fal fa-trash"></i></button>
            </div>
        </div>
    `;

    $("#contenedor_servicios").append(nuevo);

});

$(document).on("click", ".eliminar-bloque", function(){
    if($(".bloque-servicio").length == 1){
        return maquinge.notificaciones("Debe existir al menos un servicio.", 'Sistema','warning');
    }
    $(this).closest(".bloque-servicio").remove();
});
$(document).on('keyup change', '.cantidad, .precio_unitario', function(){
    let fila = $(this).closest('.bloque-servicio');
    calcular_subtotal(fila);
});


$(function () { 
    $("#menu_cotizacion").addClass("active");
    $('#DlgModal_fch_create').datepicker({ todayHighlight: true,orientation: "bottom left", templates: controls,format: 'dd-mm-yyyy' });
    $('#DlgModal_fecha_entrega').datepicker({ todayHighlight: true,orientation: "bottom left", templates: controls,format: 'dd-mm-yyyy' });

    $('#search_fromdate').datepicker({ todayHighlight: true,orientation: "bottom left",templates: controls, format: 'dd-mm-yyyy'  });
    $('#search_todate').datepicker({ todayHighlight: true,orientation: "bottom left",templates: controls, format: 'dd-mm-yyyy'  });

 
    $('#chk_igv').on('change', function(){ this.value = this.checked ? 1 : 0; }).change();
    $('#chk_material').on('change', function(){ this.value = this.checked ? 1 : 0; }).change();
    $('#chk_plano').on('change', function(){ this.value = this.checked ? 1 : 0; }).change();

   
    // let _pen = null;

    $('#chk_usd').off('change').on('change', function () {
        this.value = this.checked ? 1 : 0;

        // if (this.checked) {
        //     _pen = parseFloat($('#DlgModal_txt_ttotal').text()) || 0;

        //     $.get('{{ route("tipo.cambio") }}')
        //     .done(function (r) {
        //         $('#DlgModal_txt_ttotal').text((_pen / parseFloat(r.precio_venta)).toFixed(2));
        //         $('#lbl-moneda').text('$');
        //     })
        //     .fail(function () {
        //         $('#chk_usd').prop('checked', false).val('0');
        //         maquinge.notificaciones('No se pudo obtener el tipo de cambio.', 'Sistema', 'warning');
        //     });

        // } else {
        //     if (_pen !== null) $('#DlgModal_txt_ttotal').text(_pen.toFixed(2));
        //     $('#lbl-moneda').text('S/.');
        //     _pen = null;
        // }
    });

     
    var table = $('#tabla').DataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        responsive: true,       
        ajax: {
            url:"{{ route('cotizacion.list') }}",
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
            {data: 'estado',className:'text-center'},
            {data: 'procedencia',className:'text-center'},                                     
            {data: 'fch_create',className:'text-center'},
            {data: 'cliente'},            
            {data: 'telefono'},  
            {data: 'fecha_entrega',className:'text-center'},
            {data: 'usuario',className:'text-center'},
            {data: 'action',width: '10%', searchable: false},
        ], 
        dom: dom_buttons_table,
        buttons: [{
            extend: 'pdfHtml5',
            text: 'PDF',
            titleAttr: 'Generate PDF',
            className: 'btn-outline-danger btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8]
            }
        },{
            extend: 'excelHtml5',
            text: 'Excel',
            titleAttr: 'Generate Excel',
            className: 'btn-outline-success btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8]
            }
        }],
        
        lengthMenu: [ [30, 50,100, -1], [30, 50,100, "All"] ],
        language: espanol,
        ordering: false,
        initComplete: function () {  
            this.api().columns(2).every(function () {
                var column = this;
                var select = $(
                    "<select class='form-control' style='padding:0px;font-size:11px;height:20px;'>" +
                    "<option value=''>ESTADO</option>" +
                    "<option value='PENDIENTE'>PENDIENTE</option>" +
                    "<option value='SEGUIMIENTO'>SEGUIMIENTO</option>" +
                    "<option value='POR CONFIRMAR'>POR CONFIRMAR</option>" +
                    "<option value='VENTA CERRADA'>VENTA CERRADA</option>" +
                    "<option value='PERDIDA'>PERDIDA</option>" +
                    "</select>"
                )
                .appendTo($(column.header()).empty())
                .on('change', function () {
                    column.search($(this).val()).draw();
                });
            });
            this.api().columns(5).every( function () {
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
            this.api().columns(8).every( function () {
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


    MsgDlgLoadAjaxForm("form");
    var table_dinamica = $('#tableDynamic_ittems').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        ajax : "{{ route('tabla_cotizacion_detalle') }}"+"?id_cotizacion=0",  
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


function cambiar_estado(id_cotizacion){
    let html = `
            <div style=" display:flex;flex-direction:column; gap:12px; padding:10px 5px;">
                <span class="badge badge-secondary badge-pill btnEstadoModal"
                    data-id="${id_cotizacion}"
                    data-estado="0"
                    style="cursor:pointer; padding:12px; font-size:15px; font-weight:600; ">
                    <i class="fal fa-clock mr-2"></i>
                    PENDIENTE
                </span>
                <span class="badge badge-warning badge-pill btnEstadoModal"
                    data-id="${id_cotizacion}"
                    data-estado="1"
                    style=" cursor:pointer;padding:12px; font-size:15px; font-weight:600; ">
                    <i class="fal fa-search mr-2"></i>
                    SEGUIMIENTO
                </span>
                <span class="badge badge-info badge-pill btnEstadoModal"
                    data-id="${id_cotizacion}"
                    data-estado="2"
                    style="cursor:pointer; padding:12px; font-size:15px; font-weight:600;">
                    <i class="fal fa-question-circle mr-2"></i>
                    POR CONFIRMAR
                </span>
                <span class="badge badge-success badge-pill btnEstadoModal"
                    data-id="${id_cotizacion}"
                    data-estado="3"
                    style=" cursor:pointer; padding:12px; font-size:15px; font-weight:600;">
                    <i class="fal fa-check-circle mr-2"></i>
                    VENTA CERRADA
                </span>
                <span class="badge badge-danger badge-pill btnEstadoModal"
                    data-id="${id_cotizacion}"
                    data-estado="4"
                    style="cursor:pointer; padding:12px;font-size:15px;font-weight:600; ">
                    <i class="fal fa-times-circle mr-2"></i>
                    PERDIDA
                </span>
            </div>

            `;

    let box = bootbox.dialog({
        title: ".:: CAMBIAR ESTADO ::.",
        message: html,
        size: 'medium',
        centerVertical: true,
        backdrop: false,
        closeButton: true
    }); 

   
    box.init(function () {
        box.attr("id", "bootbox_cambiar_estado");
        $("#bootbox_cambiar_estado .btnEstadoModal").click(function(){
            let estado = $(this).data('estado');
            let textoEstado = $(this).text().trim();
            bootbox.confirm({
                title: ".:: CONFIRMAR ::.",
                message:
                    "<center style='font-size:15px'>" +
                    "¿Cambiar estado a:<br><br>" +
                    "<b>" + textoEstado + "</b> ?" +
                    "</center>",
                centerVertical: true,
                backdrop: false,
                buttons: {
                    confirm: {
                        label: 'SI',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'NO',
                        className: 'btn-danger'
                    }
                },
                callback: function(result){
                    if(result){
                        $.ajax({
                            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                            url: 'cotizacion-cambiar_estado',
                            type: 'GET',
                            data: {
                                id: id_cotizacion,
                                estado: estado
                            },
                        })
                        .done(function(data){
                            maquinge.notificaciones('Estado actualizado.', 'Sistema','success' );
                            $('#bootbox_cambiar_estado').modal('hide');
                            $('#tabla').DataTable().ajax.reload(null, false);
                        })
                        .fail(function(){
                            maquinge.notificaciones('Error al actualizar.','Sistema','error' );
                        });
                    }
                }
            });
        });
    });
    

}



function OpenModal(){
    global_modal=1;
    $("#DlgModal").modal('show');
    GenerarOrdenTemporal();    
}

function GenerarOrdenTemporal(){
    var id_user = "{!! Auth::user()->id !!}";
    $.ajax({        
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'cotizacion-detalle',
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
        url: 'cotizacion-detalle/create',
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
            url: 'cotizacion-detalle/'+id_detalle,
            type: 'PUT',
            data:{ 
                "_token": "{{ csrf_token() }}",
                cantidad : result.value.cantidad,
                precio: result.value.precio        
            },
        }).done(function (data) {
            actualizar_tabla_dinamica();
        }).fail(function (error, jqXHR, textStatus,errorThrown) {
            var errors = error.responseJSON;                        
            if (errors.status === 500) {
                maquinge.notificaciones(error.msg, 'Sistema', 'error');
            }                                       
        });  
       
    })
   
   
}

function del_ittem(id_orden_detalle){
    id_orden=$("#DlgModal_id_orden").val();
    
    $.ajax({ 
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'cotizacion-detalle/'+id_orden_detalle,
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
    
    if($("#DlgModal_observacion").val()==""){
        $("#DlgModal_observacion").focus(); 
        return maquinge.notificaciones("Agrega especificaciones o observaciones.", 'Sistema', 'warning');        
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
    formData.append('observacion',($("#DlgModal_observacion").val()).toUpperCase());
    
    formData.append('igv',$("#chk_igv").val());
    formData.append('material',$("#chk_material").val());
    formData.append('plano',$("#chk_plano").val()); 
    formData.append('chk_usd',$("#chk_usd").val());      
    // formData.append('total_usd',$("#DlgModal_txt_ttotal").html());      
    
    jQuery.each(jQuery('#file')[0].files, function(i, file) {
        formData.append('file[]', file);
    });

    let descripciones = [];
    let cantidades = [];
    let precios = [];
    let subtotales = [];

    $(".des_serv").each(function(){ descripciones.push($(this).val());});
    $(".cantidad").each(function(){ cantidades.push($(this).val());  });
    $(".precio_unitario").each(function(){ precios.push($(this).val()); });
    $(".subtotal").each(function(){ subtotales.push($(this).val()); });

    formData.append('descripcion', JSON.stringify(descripciones).toUpperCase());
    formData.append('cantidad', JSON.stringify(cantidades));
    formData.append('precio_unitario', JSON.stringify(precios));
    formData.append('subtotal', JSON.stringify(subtotales));

    MsgDlgLoadAjaxForm("form");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'cotizacion',
        type: 'POST',
        dataType: "html",
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
    }).done(function (data) { 
        if(data.msg==0){
            maquinge.notificaciones('Cotizacion actualizada.', 'Sistema', 'success');
            MsgDlgLoadAjaxFinish("form");
            close_modal();
        }else{
            maquinge.notificaciones('Cotizacion actualizada.', 'Sistema', 'success');
            $("#DlgModal_codigo").val((id_orden).padStart(6, "0"));
            setTimeout(function(){    
                MsgDlgLoadAjaxFinish("form"); close_modal();
            }, 500);
        }  
        $('#tabla').DataTable().ajax.url("{{ route('cotizacion.list') }}").load();        
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
            url: 'cotizacion/'+id_orden,
            type: 'GET',        
        }).done(function(data){
            $("#DlgModal_codigo").val(data.codigo);
            $("#DlgModal_procedencia").val(data.procedencia);
            $("#DlgModal_fch_create").val(data.fch_create);
            $("#DlgModal_id_user").val(data.usuario);
            $("#DlgModal_fecha_entrega").val(data.fecha_entrega);
            $("#DlgModal_hora_entrega").val(data.hora_entrega); 
            $("#DlgModal_des_gen").val(data.des_gen);
            $("#DlgModal_observacion").val(data.observacion);

            cargar_servicios_cliente(data);

            if(data.igv==1) { $("#chk_igv").val("1"); $('#chk_igv').prop('checked', true);
            } else {  $("#chk_igv").val("0"); $('#chk_igv').prop('checked', false); }

            if(data.material==1) { $("#chk_material").val("1"); $('#chk_material').prop('checked', true);
            } else {  $("#chk_material").val("0"); $('#chk_material').prop('checked', false); }

            if(data.plano==1) { $("#chk_plano").val("1"); $('#chk_plano').prop('checked', true);
            } else {  $("#chk_plano").val("0"); $('#chk_plano').prop('checked', false); }

            if(data.chk_usd==1) { 
                $("#chk_usd").val("1"); 
                $('#chk_usd').prop('checked', true);
                // $("#DlgModal_txt_ttotal").html(data.total_usd);
            } else {  
                $("#chk_usd").val("0"); 
                $('#chk_usd').prop('checked', false); 
            }

            if(data.ruta_url!=""){                
                $('#alert').html('Plano: '+data.ruta_url).removeAttr('class').addClass('xd2');
                $("#label_file").text('Seleccionar archivo 1...');
                $('#ver_archivo_1').attr('onClick', 'VerPLanoPdf("'+data.ruta_url+'");');
            }            
           
            
        }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
            var errors = error.responseJSON;                        
            if (errors.status === 500) {
                maquinge.notificaciones(error.msg, 'Sistema', 'error');
            }       
        });
        
    }else{  
        return maquinge.notificaciones("Acceso denegado.", 'Sistema', 'warning');
    }
    setTimeout(function(){ 
        MsgDlgLoadAjaxFinish("form"); 
    }, 1000);
    
}

function cargar_servicios_cliente(data){  
    $("#contenedor_servicios").html('');

    for(let i = 1; i <= 3; i++){

        let descripcion = data['descripcion_'+i];
        let cantidad = data['cantidad_'+i];
        let precio = data['pre_unitario_'+i];
        let subtotal = data['subtot_'+i];

        // si no existe descripcion no crear bloque
        if(descripcion == null || descripcion == "" ){
            continue;
        }

        let bloque = `
            <div class="form-row bloque-servicio mb-2">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Descripcion Servicio:</label>
                    <input type="text" name="descripcion[]" class="form-control text-uppercase des_serv" value="${descripcion}" autocomplete="off">
                </div>
                <div class="col-md-1 mb-3">
                    <label class="form-label"> Cantidad:  </label>
                    <input type="number" min="1" name="cantidad[]" class="form-control cantidad" value="${cantidad ?? 1}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label"> Precio Unid. </label>
                    <input type="text" name="precio_unitario[]" class="form-control precio_unitario" onkeypress="return isNumberKey(event)" value="${precio ?? ''}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label"> SubTotal. </label>
                    <input type="text" name="subtotal[]" class="form-control subtotal" value="${subtotal ?? ''}" readonly="readonly">
                </div>
                <div class="col-md-1 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-block eliminar-bloque"> <i class="fal fa-trash"></i></button>
                </div>
            </div>
        `;

        $("#contenedor_servicios").append(bloque);
    }

    // si no tiene datos crear uno vacío
    if($(".bloque-servicio").length == 0){
        $("#btnAgregarServicio").click();
    }
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
        reiniciar_servicios_cliente();
        $("#file").val(null);
        $('#alert').html('').removeAttr('class');
        $("#label_file").html('');
        $("#chk_igv,#chk_material,#chk_plano").val("0");
        $("#DlgModal_id_orden,#DlgModal_codigo,#DlgModal_txt_id_cliente").val('');
        $('#tableDynamic_ittems').DataTable().ajax.url("{{ route('tabla_cotizacion_detalle') }}"+"?id_orden=0").load();      
        MsgDlgLoadAjaxFinish("form"); 
    }, 1000);
}

function reiniciar_servicios_cliente(){
    $("#contenedor_servicios").html(`
        <div class="form-row bloque-servicio mb-2" data-index="1">
            <div class="col-md-6 mb-3">
                <label class="form-label">Descripcion Servicio:</label>
                <input type="text" name="descripcion[]" 
                    class="form-control text-uppercase des_serv"
                    placeholder="Servicio..." autocomplete="off">
            </div>
            <div class="col-md-1 mb-3">
                <label class="form-label">Cantidad:</label>
                <input type="number" min="1"
                    name="cantidad[]"
                    class="form-control cantidad"
                    value="1">
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Precio Unid.</label>
                <input type="text"
                    name="precio_unitario[]"
                    class="form-control precio_unitario"
                    onkeypress="return isNumberKey(event)"
                    placeholder="00.00">
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">SubTotal.</label>
                <input type="text"
                    name="subtotal[]"
                    class="form-control subtotal"                    
                    placeholder="00.00" readonly="readonly">
            </div>
            <div class="col-md-1 mb-3">
                <label class="form-label">&nbsp;</label>
                <button type="button"
                    class="btn btn-danger btn-block eliminar-bloque">
                    <i class="fal fa-trash"></i>
                </button>
            </div>
        </div>
    `);
}

function calcular_subtotal(fila){
    let cantidad = parseFloat(
        fila.find('.cantidad').val()
    ) || 0;

    let precio = parseFloat(
        fila.find('.precio_unitario').val()
    ) || 0;

    let subtotal = cantidad * precio;

    fila.find('.subtotal').val(
        subtotal.toFixed(2)
    );
}

function BorrarOrdenTemporal(){  // borrar Orden de servicio temporal
    id_orden= $("#DlgModal_id_orden").val();
    if($("#DlgModal_codigo").val()==''){       
        $.ajax({        
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            url: 'borrar-cotizacion-temporal/'+id_orden,
            type: 'GET',
            data:{
                "_token": "{{ csrf_token() }}",
                id_orden: 1
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
        url: 'borrar-cotizacion-no/'+id_orden,
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

function actualizar_tabla_dinamica(){
    id_orden=$("#DlgModal_id_orden").val();
    $('#tableDynamic_ittems').DataTable().ajax.url("{{ route('tabla_cotizacion_detalle') }}"+"?id_orden="+id_orden).load();
}

function VerPLanoPdf(url){
    window.open(url, '_blank').focus();
}

function ticket_proforma(){
    id_orden= $("#DlgModal_id_orden").val();
    window.open('print-cotizacion-pequeno/'+id_orden, '_blank').focus();
}

function print_boleta(id_orden){
    // SI NO VIENE PARAMETRO
    if(id_orden == null || id_orden == '' || typeof id_orden === 'undefined'){
        id_orden = $("#DlgModal_id_orden").val();
    }
    // VALIDAR
    if(id_orden == null || id_orden == ''){
        alert("No existe ID de orden");
        return;
    }
    window.open('print_cotizacion/' + id_orden);
}


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
        url: 'cotizacion-detalle/create',
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
            url: 'consumomaterial-cotizacion/create',
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
            $("#DlgModal_id_servicio").focus(); 
        }, 300);
        
        actualizar_tabla_dinamica();
    }).fail(function (jqXHR, textStatus,errorThrown) {
        maquinge.notificaciones(textStatus, 'Sistema', 'error');     
        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
        MsgDlgLoadAjaxFinish("form"); 
    });
}


function cal_costo_auto(){
   
   var mmm = $('select[name="DlgModal_id_mat"] option:selected').text();
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
  
   pre_unid=valor1*factor_k*precio;
   p_unid=Math.ceil(pre_unid)*1.00;
  
   cant=parseFloat($("#DlgModal_cant_mat").val());
   precio_total=p_unid*cant;
   console.log("precio: "+precio);  
   $("#DlgModal_txt_unidad_mat").text("S/."+p_unid.toFixed(2));
   $("#DlgModal_txt_ttotal_mat").text("S/."+precio_total.toFixed(2));
}

function guardar_edit_material_cotizacion(){
    id_detalle=$("#edit_material_id_orden_detalle").val();
    var unid_mat2 = $("#DlgModal_txt_unidad_mat2").text();
    var unid_mat22 = parseFloat(unid_mat2.replace("S/.", ""));
    $.ajax({
        url: 'cotizacion-detalle/'+id_detalle,
        type: 'PUT',
        data:{ 
            "_token": $('input[name=_token]').val(),
            cantidad : $("#DlgModal_cant_mat2").val(),
            precio: unid_mat22
        },
        success: function(data){
            $.ajax({
                url: 'consumomaterial-cotizacion/'+id_detalle,
                type: 'PUT',
                data:{  
                    "_token"        : $('input[name=_token]').val(),  
                    largo           : $("#DlgModal_largo2").val(),
                    ancho           : $("#DlgModal_ancho2").val(),
                    espesor         : $("#DlgModal_espesor2").val(),
                    id_tip_mat      : $("#DlgModal_id_mat2").val(),
                    id_orden_det    : id_detalle,         
                    cant            : $("#DlgModal_cant_mat2").val(),                   
                    precio_unid     : unid_mat22.toFixed(2)     
                },
                success: function(data2){
                    console.log(data2.msg);
                    actualizar_tabla_dinamica();
                    close_modal_edit_material();
                },
                error : function(xhr, status) {
                    console.log('Error al agregar material...');
                }
            }); 
        },
        error : function(xhr, status) {
            console.log('Error al agregar material...');
        }
    });
}


$('#DlgModal_des_serv').typeahead({
    source: function(query, process) {
        objects = [];
        map = {};      
        $.getJSON('serv_autocompletar/' + query,null , function (jsonData) {
            $.each(jsonData, function(i, object) {
                var displayText = object.descripcion;                
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
</script>
@endpush

