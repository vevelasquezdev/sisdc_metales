@extends('layouts.app')
@section('titulo') Almacen Rio Seco @endsection

@section('contenido')

<div class="row">
    <div class="col-md-12">
        <div id="panel-4" class="panel">
            <div class="panel-hdr">
                <h2> ALMACEN - RIO SECO</h2>                
                <div class="panel-toolbar">
                    <button class="btn btn-panel waves-effect waves-themed" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Minimizar"></button>
                    <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Maximizar"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="form-row ml-3 mr-3">
                        <div class="col-md-1">                           
                            <button data-toggle='modal' data-target='.default-example-modal-right-lg' data-type="1" type="button" class="btn btn-success btn-block btn-sm">INGRESO</button> 
                        </div>
                        <div class="col-md-1">                           
                            <button data-toggle='modal' data-target='.default-example-modal-right-lg' data-type="0" type="button" class="btn btn-success btn-block btn-sm">SALIDA</button> 
                        </div>
                       
                        <div class="form-group mb-3 mr-2 ml-2">
                            <input type="text" id="search_fromdate" class="form-control form-control-sm w-auto" style="width: 100px !important"
                                   value="{{ date('d-m-Y') }}" 
                                   placeholder="Desde...">
                        </div>
                       
                        <div class="form-group mb-3 mr-2">
                            <input type="text" id="search_todate" class="form-control form-control-sm w-auto" style="width: 100px !important"
                                   value="{{ date('d-m-Y') }}" placeholder="Hasta...">
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="col-form-label col-8 col-lg-5 form-label text-lg-right">Ingresos y Salidas:</label>
                                <div class="col-12 col-lg-6">
                                    <select id="select_ingreso_salida" class="form-control text-uppercase" required>
                                        <option value="2">Todo</option>
                                        <option value="1">Ingresos</option>
                                        <option value="0">Salidas</option>                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">                           
                            <button onclick="actualizar_tabla_ingreso_salidas();" class="btn btn-success btn-block btn-sm">Actualizar</button> 
                        </div>
                        <div class="col-md-2 text-right">
                            <button data-toggle='modal' 
                                    data-target='.default-example-modal-right-lg' 
                                    data-type="3" 
                                    type="button" class="btn btn-info btn-sm"><i class="fas fa-external-link"></i>&nbsp;MOVER A ZOLEZZI</button>
                        </div>                                            
                    </div>                    
                    <div class="tab-content p-3">
                        <div class="tab-pane fade show active" id="tab_justified-1" role="tabpanel">
                            <div class="row">
                                <div class="col-xl-12 col-md-12">                                                                                                                    
                                    <div class="table-responsive">
                                        <table id="tabla" class="table table-bordered table-hover table-striped w-100">
                                            <thead class="bg-primary-600">
                                                <tr>
                                                    <th>id</th>
                                                    <th>Descripción/Insumo</th>
                                                    <th>Cant</th>
                                                    <th>Tipo</th>                                                    
                                                    <th>Almacen</th>
                                                    <th>Pvc</th>
                                                    <th>Origen/Destino</th>
                                                    <th>P.Compra</th>
                                                    <th>% Gan.</th>
                                                    <th>P.Venta</th>
                                                    <th>Usuario</th>
                                                    <th>Fecha</th>
                                                    <th>Observación</th>
                                                    <th>...</th>                                  
                                                </tr>
                                            </thead>                    
                                        </table>                                             
                                    </div>                                          
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade default-example-modal-right-lg" id="DldModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-right modal-lg">
        <div class="modal-content">
            <form id="form">
                <div class="modal-header">
                    <h4 class="modal-title text-primary">.: FORMULARIO INGRESO / SALIDA :.</h4>
                    <button onclick="close_modal();" type="button" class="close" aria-label="Close"><span aria-hidden="true"><i class="fal fa-times"></i></span></button>
                </div>
                <div class="modal-body">
                    <div class="card mb-g">                        
                        <div class="card-body p-3">
                            <div class="form-row">
                                <input type="hidden" id="txt_id">
                                <input type="hidden" id="txt_tipo_movimiento">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="txt_almacen">Almacen: <span class="text-danger">*</span> </label>
                                    <select id="txt_almacen" class="form-control jquery_field text-uppercase" required>
                                        <option value="">Selecciona</option>
                                        @foreach ($almacenes as $almacen)
                                            <option value="{{$almacen->id}}">{{$almacen->desc}}</option>
                                        @endforeach                                        
                                    </select>
                                    <div id="error_txt_almacen" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="txt_tipo">Tipo: <span class="text-danger">*</span> </label>
                                    <select id="txt_tipo" class="form-control jquery_field text-uppercase" required>
                                        <option value="">Selecciona</option>
                                        <option value="1">INGRESO</option>
                                        <option value="0">SALIDA</option>                                        
                                    </select>
                                    <div id="error_txt_tipo" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label" for="txt_insumo">Descripción del Producto y/o Insumo (Planchas):<span class="text-danger">*</span></label>
                                    <input type="text" id="txt_insumo" placeholder="Buscar producto / insumo..." class="typeahead form-control" autocomplete="off"/>
                                    <input id="txt_id_insumo" type="hidden" class="form-control text-uppercase" value="">
                                    <div id="error_txt_id_insumo" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="txt_stock">Stock:<span class="text-danger">*</span></label>
                                    <input type="text" id="txt_stock" class="typeahead form-control" autocomplete="off" readonly/>
                                </div>

                                <div class="col-md-12" id="panel_cliente" style="display: none;">
                                    <div class="accordion accordion-outline" id="js_demo_accordion-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <a href="javascript:void(0);" class="card-title collapsed pt-2 pb-2" data-toggle="collapse" data-target="#js_demo_accordion-3b" aria-expanded="false">
                                                    <i class="fal fa-user"></i>&nbsp;&nbsp;Cliente
                                                    <span class="ml-auto">
                                                        <span class="collapsed-reveal"> <i class="fal fa-minus fs-xl"></i></span>
                                                        <span class="collapsed-hidden"> <i class="fal fa-plus fs-xl"></i></span>
                                                    </span>
                                                </a>
                                            </div>
                                            <div id="js_demo_accordion-3b" class="collapse" data-parent="#js_demo_accordion-3">
                                                <div class="card-body">
                                                    <form id="form_cliente">
                                                        <div class="form-row">
                                                            <input type="hidden" id="DlgModal_txt_id_cliente">
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label" for="DlgModal_txt_name">Nombres:<span class="text-danger">*</span></label>
                                                                <input id="DlgModal_txt_name" type="text" class="form-control text-uppercase" readonly>
                                                                <div id="error_DlgModal_txt_name" class="error_msg_cli text-danger d-none"> </div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label" for="DlgModal_txt_surname">Apellidos:<span class="text-danger">*</span></label>
                                                                <input id="DlgModal_txt_surname" type="text" class="form-control text-uppercase" readonly>
                                                                <div id="error_DlgModal_txt_surname" class="error_msg_cli text-danger d-none"> </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button onclick="Open_buscar_cliente();" type="button" class="btn btn-primary waves-effect waves-themed mt-4">
                                                                    Buscar
                                                                </button>
                                                                <button onclick="OpenModalCliente();" type="button" class="btn btn-primary waves-effect waves-themed mt-4">
                                                                    Agregar Cliente
                                                                </button>
                                                            </div>
                                                            <div class="col-md-2 mb-3">
                                                                <label class="form-label" for="DlgModal_txt_ruc">Ruc:<span class="text-danger">*</span></label>
                                                                <input id="DlgModal_txt_ruc" type="text" class="form-control text-uppercase" readonly>
                                                                <div id="error_DlgModal_txt_ruc" class="error_msg_cli text-danger d-none"> </div>
                                                            </div>
                                                            <div class="col-md-5 mb-3">
                                                                <label class="form-label" for="DlgModal_txt_raz_soc">Razon Social:<span class="text-danger">*</span></label>
                                                                <input id="DlgModal_txt_raz_soc" type="text" class="form-control text-uppercase" readonly>
                                                                <div id="error_DlgModal_txt_raz_soc" class="error_msg_cli text-danger d-none"> </div>
                                                            </div>
                                                            <div class="col-md-2 mb-3">
                                                                <label class="form-label" for="DlgModal_txt_telefono">Telefono:<span class="text-danger">*</span></label>
                                                                <input id="DlgModal_txt_telefono" type="text" class="form-control text-uppercase" readonly>
                                                                <div id="error_DlgModal_txt_telefono" class="error_msg_cli text-danger d-none"> </div>
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label class="form-label" for="DlgModal_txt_email">Email:<span class="text-danger">*</span></label>
                                                                <input id="DlgModal_txt_email" type="text" class="form-control" readonly>
                                                                <div id="error_DlgModal_txt_email" class="error_msg_cli text-danger d-none"> </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="error_txt_id_cliente" class="error_msg text-danger d-none mb-3"> </div>

                                <div class="col-md-12" id="panel_proveedor" style="display: none;">
                                    <div class="accordion accordion-outline" id="js_demo_accordion-1">
                                        <div class="card">
                                            <div class="card-header">
                                                <a href="javascript:void(0);" class="card-title collapsed pt-2 pb-2" data-toggle="collapse" data-target="#js_demo_accordion-1b" aria-expanded="false">
                                                    <i class="fal fa-user"></i>&nbsp;&nbsp;Proveedor
                                                    <span class="ml-auto">
                                                        <span class="collapsed-reveal"> <i class="fal fa-minus fs-xl"></i></span>
                                                        <span class="collapsed-hidden"> <i class="fal fa-plus fs-xl"></i></span>
                                                    </span>
                                                </a>
                                            </div>
                                            <div id="js_demo_accordion-1b" class="collapse" data-parent="#js_demo_accordion-1">
                                                <div class="card-body">
                                                    <form id="form_proveedor">
                                                        <div class="form-row">
                                                            <input type="hidden" id="txt_id_provee">
                                                            <div class="col-md-7 mb-3">
                                                                <label class="form-label" for="txt_provee_desc">Razon Social:<span class="text-danger">*</span></label>
                                                                <input id="txt_provee_desc" type="text" class="form-control text-uppercase" readonly>
                                                                <div id="error_txt_provee_desc" class="error_msg_cli text-danger d-none"> </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <button onclick="Open_buscar_proveedor();" type="button" class="btn btn-primary waves-effect waves-themed mt-4">
                                                                    Buscar
                                                                </button>
                                                                <button onclick="OpenModalProveedor();" type="button" class="btn btn-primary waves-effect waves-themed mt-4">
                                                                    Agregar Proveedor
                                                                </button>
                                                            </div>
                                                            <div class="col-md-2 mb-3">
                                                                <label class="form-label" for="txt_provee_tel">Telefono:<span class="text-danger">*</span></label>
                                                                <input id="txt_provee_tel" type="text" class="form-control text-uppercase" readonly>
                                                                <div id="error_txt_provee_tel" class="error_msg_cli text-danger d-none"> </div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label" for="txt_provee_ruc">Ruc:<span class="text-danger">*</span></label>
                                                                <input id="txt_provee_ruc" type="text" class="form-control text-uppercase" readonly>
                                                                <div id="error_txt_provee_ruc" class="error_msg_cli text-danger d-none"> </div>
                                                            </div>                                                                                                                      
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                <div id="error_txt_id_proveedor" class="error_msg text-danger d-none mb-3"> </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="txt_origen_destino">Origen y/o Destino:<span class="text-danger">*</span></label>
                                    <input id="txt_origen_destino" type="text" class="form-control text-uppercase">
                                    <div id="error_txt_origen_destino" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="txt_cantidad">Cantidad:<span class="text-danger">*</span></label>
                                    <input id="txt_cantidad" step="1" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" type="number" class="form-control text-uppercase">
                                    <div id="error_txt_cantidad" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="txt_pvc">Recubrimiento: <span class="text-danger">*</span> </label>
                                    <select id="txt_pvc" class="form-control jquery_field text-uppercase" required>
                                        <option value="">Selecciona</option>
                                        <option value="1">CON PVC</option>
                                        <option value="0">SIN PVC</option>                                        
                                    </select>
                                    <div id="error_txt_pvc" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="txt_pre_compra">Precio de Compra (Unidad):<span class="text-danger">*</span></label>
                                    <input id="txt_pre_compra" type="number" step="1" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" class="form-control text-uppercase">
                                    <div id="error_txt_pre_compra" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="txt_porcentaje_gana">% de Ganancia (Unidad):<span class="text-danger">*</span></label>
                                    <input id="txt_porcentaje_gana" type="number" class="form-control text-uppercase">
                                    <div id="error_txt_porcentaje_gana" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="txt_pre_venta">Precio de Venta (Unidad):<span class="text-danger">*</span></label>
                                    <input id="txt_pre_venta" step="1" type="number" class="form-control text-uppercase">
                                    <div id="error_txt_pre_venta" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="txt_obs">Observación: </label>
                                    <input id="txt_obs" class="form-control jquery_field text-uppercase" type="text" placeholder="Coloca aqui alguna observación...">
                                    <div id="error_txt_obs" class="error_msg text-danger d-none"> </div>
                                </div>                          
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="close_modal();" type="button" class="btn btn-secondary">Cerrar</button>
                    <button onclick="guardar();" id="DlgMovimiento_btn_guardar" type="button" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade default-example-modal" id="DldModalCliente" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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

<div class="modal fade default-example-modal" id="DldModalBuscarCliente" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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

<div class="modal fade default-example-modal" id="DldModalProveedor" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="form_provee-1">
                <input type="hidden" id='_token' name="_token" value="{{ csrf_token() }}" />
                <div class="modal-header">
                    <h5 class="modal-title h4">.: AGREGAR NUEVO PROVEEDOR :.</h5>
                    <button onclick="close_modal_proveedor();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">
                        <input type="hidden" id="provee_txt_id">
                        <div class="col-md-8 mb-3">
                            <label class="form-label" for="provee_txt_razon_social">Razón Social:<span class="text-danger">*</span></label>
                            <input id="provee_txt_razon_social" type="text" class="form-control jquery_field text-uppercase" required>
                            <div id="error_provee_txt_razon_social" class="error_msg text-danger d-none"> </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="provee_txt_telefono">Telefono:<span class="text-danger">*</span></label>
                            <input id="provee_txt_telefono" type="text" class="form-control jquery_field" required>
                            <div id="error_provee_txt_telefono" class="error_msg text-danger d-none"> </div>
                        </div>                       
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="provee_txt_ruc">RUC:<span class="text-danger">*</span></label>
                            <input id="provee_txt_ruc" type="text" class="form-control jquery_field" required>
                            <div id="error_provee_txt_ruc" class="error_msg text-danger d-none"> </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="guardar_proveedor();" type="button" class="btn btn-primary">Guardar</button>
                    <button onclick="close_modal_proveedor();" type="button" class="btn btn-secondary">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade default-example-modal" id="DldModalBuscarProveedor" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form_provee-2">
                <input type="hidden" id='_token' name="_token" value="{{ csrf_token() }}" />
                <div class="modal-header">
                    <h5 class="modal-title h4">.: TABLA PROVEEDORES :.</h5>
                    <button onclick="close_modal_buscar_proveedor();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">
                        <div class="table-responsive">
                            <table id="tabla_buscar_proveedor" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>ID</th>
                                        <th>Razon Social</th>
                                        <th>Ruc</th>
                                        <th>Telefono</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="select_buscar_proveedor(0);" type="button" class="btn btn-primary">Seleccionar</button>
                    <button onclick="close_modal_buscar_proveedor();" type="button" class="btn btn-secondary">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/cliente.js') }}"></script>
<script src="{{ asset('js/proveedor.js') }}"></script>
<script>


$(function () { 
    $("#menu_almacenes").addClass("active open");    
    $("#submenu_alm_rioseco").addClass("active");
    $('#fchselect,#search_fromdate,#search_todate').datepicker({
                    todayHighlight: true,
                    orientation: "bottom left",
                    templates: controls,
                    format: 'dd-mm-yyyy'
                });  

    var table = $('#tabla').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,       
        ajax: {
            url: "{{ route('ing.sal.insumos.list') }}?tipo="+$("#select_ingreso_salida").val()+"&idalm=1",
            data: function(data){
                var from_date = $('#search_fromdate').val();
                var to_date = $('#search_todate').val();
                data.from_date = from_date;
                data.to_date = to_date;
            }
        },
        columns: [
            {data: 'id', name: 'id', visible: false, searchable: false},
            {data: 'insumo', name: 'insumo',width: '30%'},
            {data: 'cant', name: 'cant', className:'text-center'},
            {data: 'tipo', name: 'tipo',className:'text-center',searchable: false},
            {data: 'almacen', name: 'almacen',className:'text-center',searchable: false},  
            {data: 'pvc', name: 'pvc',className:'text-center',searchable: false},   
            {data: 'origen_destino', name: 'origen_destino',searchable: false},
            {data: 'pre_compra', name: 'pre_compra',className:'text-center',searchable: false},
            {data: 'porcentaje_gana', name: 'porcentaje_gana',className:'text-center',searchable: false},
            {data: 'pre_venta', name: 'pre_venta',className:'text-center',searchable: false},
            {data: 'usuario', name: 'usuario',className:'text-center',searchable: false}, 
            {data: 'fch', name: 'fch',className:'text-center',searchable: false},
            {data: 'obs', name: 'obs'},
            {data: 'action', name: 'action',width: '20%', searchable: false},
        ],
        dom: dom_buttons_table,
        buttons: [{
            extend: 'pdfHtml5',
            text: 'PDF',
            titleAttr: 'Generate PDF',
            className: 'btn-outline-danger btn-sm mr-1',
            orientation: 'landscape',
            title:'REGISTRO DE INGRESOS Y SALIDAS ALMACEN RIO_SECO - '+ new Date().toLocaleDateString(),
            exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9,10,11]
            }
        },{
            extend: 'excelHtml5',
            text: 'Excel',
            titleAttr: 'Generate Excel',
            className: 'btn-outline-success btn-sm mr-1',
            title:'REGISTRO DE INGRESOS Y SALIDAS ALMACEN RIO_SECO - '+ new Date().toLocaleDateString(),
            exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9,10,11]
            }
        }],
        lengthMenu: [ [50, 100, 200], [50, 100,200] ],
        language: espanol,
        ordering: false,
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

    var table_buscar_proveedor = $('#tabla_buscar_proveedor').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        select: true,
        ajax: "{{ route('proveedores.list') }}",
        columns: [
            {data: 'id',visible: false, searchable: false},                                     
            {data: 'desc'},
            {data: 'ruc'},
            {data: 'telefono'}         
        ],        
        lengthMenu: [ [10, 20, -1], [10, 20, "All"] ],
        language: espanol,
        ordering: false,
        info: false       
    });

    function calcularPrecioVenta() {      
        let precioCompra = parseFloat($('#txt_pre_compra').val()) || 0; // Precio de compra
        let porcentajeGanancia = parseFloat($('#txt_porcentaje_gana').val()) || 0; // Porcentaje de ganancia
        let precioVenta = precioCompra + (precioCompra * (porcentajeGanancia / 100));
        $('#txt_pre_venta').val(precioVenta.toFixed(2)); // Formato con 2 decimales
    }   
    $('#txt_pre_compra, #txt_porcentaje_gana').on('input', calcularPrecioVenta);

    $('#select_ingreso_salida').on('change', function () {
        actualizar_tabla_ingreso_salidas();
    });   
    
});
var type=0;
$('.default-example-modal-right-lg').on('show.bs.modal', function (event) {   
    var button = $(event.relatedTarget);
    type = button.data('type');
    $("#txt_tipo").prop("disabled", true);
    $("#txt_almacen").prop("disabled", true);
    $("#txt_almacen").val(1); //almacen RIO_SECO (1)  Solezzi(2)
    if (type === 0) {
        $("#txt_tipo").val(0);
        $("#txt_origen_destino").val('DESTINO: ');
        $("#panel_cliente").show();
    } else if (type === 1) {
        $("#txt_tipo").val(1);
        $("#txt_origen_destino").val('ORIGEN: ');
        $("#panel_proveedor").show();
    } else{
        $("#txt_tipo").val(0);
        $("#txt_origen_destino").val('DESTINO: ALMACEN ZOLEZZI');
        $("#txt_tipo_movimiento").val(1);
        $("#txt_obs").val('MOVIMIENTO');
        $("#panel_cliente").show();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },        
            url: 'movimiento_buscar_almacen',
            type: 'POST', 
            data:{
                "_token": "{{ csrf_token() }}",
                alm : 'zolezzi'                         
            },         
        }).done(function(data){
            $("#DlgModal_txt_id_cliente").val(data.id);
            var personaParts = data.persona.split(',');
            $("#DlgModal_txt_name").val(personaParts[0]?.trim() || '');
            $("#DlgModal_txt_surname").val(personaParts[1]?.trim() || '');

        }).fail(function(jqXHR, textStatus, errorThrown) {
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
    }
});

function guardar(){
    if (type === 0) {
        if($("#txt_stock").val()<=0){        
            return maquinge.notificaciones("Stock <= 0 <br>No puede elegir un producto con stock menor o igual a cero.", 'Sistema', 'warning');  
            $("#txt_insumo").focus();      
        }
    }
   

    $('.error_msg').addClass('d-none').text('');
    MsgDlgLoadAjaxForm("form");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },        
        url: 'ing_sal_insumos',
        type: 'POST', 
        data:{
            "_token": "{{ csrf_token() }}",
            id              : $("#txt_id").val(),
            id_insumo       : $("#txt_id_insumo").val(),
            cantidad        : $("#txt_cantidad").val(),
            tipo            : $("#txt_tipo").val(),
            obs             : $("#txt_obs").val(),
            pre_compra      : $("#txt_pre_compra").val(),
            porcentaje_gana : $("#txt_porcentaje_gana").val(),
            pre_venta       : $("#txt_pre_venta").val(),            
            id_almacen      : $("#txt_almacen").val(),
            pvc             : $("#txt_pvc").val(),
            origen_destino  : ($("#txt_origen_destino").val()).toUpperCase(),
            id_proveedor    : $("#txt_id_provee").val(),
            id_cliente      : $("#DlgModal_txt_id_cliente").val(),
            movimiento      : $("#txt_tipo_movimiento").val()
        },         
    }).done(function(data){
        setTimeout(function(){    MsgDlgLoadAjaxFinish("form"); }, 500);      
        
        actualizar_tabla_ingreso_salidas();
        maquinge.notificaciones(data.msg, 'Sistema.', 'success');
        close_modal();

    }).fail( function(error, jqXHR, textStatus, errorThrown ) { 
             
        var response = JSON.parse(error.responseText);   
        $.each( response.errors, function( key, value) {
            $('#error_txt_' + key).removeClass('d-none').text(value);
        });

        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
        if (error.status === 500) {
            maquinge.notificaciones(response.msg||'Error interno comun&iacute;quese con el &aacute;rea de sistemas', 'Sistema', 'error');
        }   

        MsgDlgLoadAjaxFinish("form");            
    });
}

 
let map = {};
let objects = [];
$('#txt_insumo').typeahead({
    source: function(query, process) {
        objects = [];
        map = {};       
        $.getJSON('insumos_autocompletar/' + query+ '/' + 1, null, function(jsonData) {
            $.each(jsonData, function(i, object) {              
                map[object.label] = object;              
                objects.push(object.label + " -- (Stock: " + object.stock + ")");
            });
            process(objects);
        });
    },
    items: 15,
    minLength: 2,
    delay: 300,
    highlighter: function(item) {
        var regex = new RegExp('(' + this.query + ')', 'gi');
        return item.replace(regex, "<strong>$1</strong>");
    },
    updater: function(item) {      
        let label = item.split(" -- (Stock: ")[0];       
        $('#txt_id_insumo').val(map[label].id); 
        $('#txt_stock').val(map[label].stock);
         
        if($("#txt_tipo").val()==0){
            $('#txt_pre_venta').val(map[label].precio);  
        }             
        return map[label].label;
    }
});

function getdata(id, id_tipo,id_cliente,id_proveedor){ 
    if(id_tipo==0){
        select_buscar_cliente(id_cliente);
    }else if(id_tipo==1){
        select_buscar_proveedor(id_proveedor);
    }
    
    
    $.ajax({
        url: 'ing_sal_insumos/'+id+'/edit',        
        type: 'GET',        
    }).done(function(data){
        $("#txt_id").val(data.id);
        $("#txt_insumo").val(data.insumo);
        $("#txt_id_insumo").val(data.id_insumo);

        $.ajax({
            url: 'stock/'+data.id_insumo+'/edit',
            data: {id_almacen: $("#txt_almacen").val()},
            type: 'GET',        
        }).done(function(data){
            $("#txt_stock").val(data.stock);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            var errors = jqXHR.responseJSON; 
            if (jqXHR.status === 500) {
                maquinge.notificaciones(errors?.msg || "Error interno del servidor.", 'Sistema', 'error'); // Maneja el caso de que 'errors.msg' no exista
            } else {
                maquinge.notificaciones("Ocurrió un error inesperado.", 'Sistema', 'error'); // Manejo genérico
            }
        });
        
        $("#txt_cantidad").val(data.cant);
        $("#txt_pvc").val(data.id_pvc);
        $("#txt_pre_compra").val(data.pre_compra);
        $("#txt_porcentaje_gana").val(data.porcentaje_gana);
        $("#txt_pre_venta").val(data.pre_venta);
        $("#txt_obs").val(data.obs);
        
        if(data.id_movimiento){            
            $("#panel_cliente").css("pointer-events", "none").attr("disabled", true);
            $("#txt_origen_destino").attr("disabled", true);
            $("#txt_tipo_movimiento").val(1);           
        }

    }).fail(function(jqXHR, textStatus, errorThrown) {
        var errors = jqXHR.responseJSON; 
        if (jqXHR.status === 500) {
            // Aquí se muestra el mensaje de error específico de la base de datos
            var errorMessage = (errors.msg && errors.msg.length > 80) ? errors.msg.substring(0, 80) + '...' : errors.msg || "Error interno del servidor.";
            maquinge.notificaciones(errorMessage, 'Sistema', 'error');
        } else if (jqXHR.status === 422) {
            // Manejo de errores de validación (si aplica)
            var validationErrors = errors.errors;
            var errorList = '';
            for (var key in validationErrors) {
                if (validationErrors.hasOwnProperty(key)) {
                    errorList += validationErrors[key].join(', ') + '<br>';
                }
            }
            maquinge.notificaciones(errorList || "Ocurrió un error inesperado.", 'Sistema', 'error');
        } else {
            // Manejo genérico para otros errores
            maquinge.notificaciones("Ocurrió un error inesperado: " + errorThrown, 'Sistema', 'error');
        }
    });
}

function close_modal(){  
    $("#DldModal").modal('hide');
    $("#txt_id").val('');
    $('.error_msg').addClass('d-none').text('');  
    $("#form")[0].reset();
    $("#panel_cliente,#panel_proveedor").hide();
    $("#form_proveedor")[0].reset();
    $("#provee_txt_id,#clie_txt_id").val('');
    $("#txt_id_provee,#DlgModal_txt_id_cliente").val('');
    $("#txt_tipo_movimiento").val('0');
    $("#panel_cliente").removeAttr("disabled").css("pointer-events", "");
    $("#txt_origen_destino").removeAttr("disabled");
}


function del(id){ 
    Swal.fire({
        title: "¿ Está seguro que desea eliminar este registro  ?",
        text: "Esta acción no se puede deshacer!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar"
    }).then(function(result){
        if (result.value){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },        
                url: 'ing_sal_insumos/'+id,
                type: 'DELETE', 
                data:{
                    "_token": "{{ csrf_token() }}",
                    id      : id,           
                },         
            }).done(function(data){
                maquinge.notificaciones(data.msg, 'Sistema', 'success');
                actualizar_tabla_ingreso_salidas();
            }).fail(function(jqXHR, textStatus, errorThrown) {
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
        }
    });
    
}


function actualizar_tabla_ingreso_salidas(){
    $('#tabla').DataTable().ajax.url("{{ route('ing.sal.insumos.list') }}?tipo="+$("#select_ingreso_salida").val()+"&fch="+$("#fchselect").val()+"&idalm=1").load();
}

</script>
@endpush