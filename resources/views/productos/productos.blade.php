@extends('layouts.app')
@section('titulo') Configuracion de Productos @endsection


@section('contenido')

<div class="row">
    {{-- <div class="col-md-12 col-xl-12 mb-0">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Configuración de</i></span>&nbsp;&nbsp;PRODUCTOS
                </h2>
            </div>
        </div>
    </div> --}}
    <div class="col-md-12 col-xl-7" >
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Tabla de</i></span>&nbsp;&nbsp;Productos Fabricados
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>                    
                </div>
            </div>
            <div class="panel-container show" id="panel_producto">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-6 mb-2">                        
                            <button class="btn btn-primary" onclick="open_producto();">
                                Nuevo Producto Fabricado
                            </button> 
                        </div>                       
                    </div>                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tableProductos" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>Cod.</th>                                    
                                            <th>Descripción de Producto</th>
                                            <th>Fecha creación</th>               
                                            <th>Plano</th>
                                            <th>Costo Tot.</th>                                         
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
    </div>
    <div class="col-md-12 col-xl-5">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Tabla de</i></span>&nbsp;&nbsp;Servicios por Producto Fabricado
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>                    
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-6 mb-2">                        
                            <button class="btn btn-primary" onclick="open_servicio();">
                                Nuevo Servicio
                            </button> 
                        </div>                       
                    </div>                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-primary mb-1 p-2" style="" role="alert">
                                <strong id="lbl_Producto" style="padding-left: 15px"></strong>
                            </div>
                            <div class="table-responsive">
                                <table id="tableServicios" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>Cod.</th>
                                            <th>Orden</th>                                  
                                            <th>Descripción del Servicio</th>
                                            <th>Precio</th>
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
    </div>
</div>

<div class="modal fade default-example-modal" id="DldModalProductos" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="dlg_form_producto">
                <div class="modal-header">
                    <h5 class="modal-title h4">.: Nuevo Producto :.</h5>
                    <button onclick="close_modal_producto();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">
                        <input type="hidden" id="DlgModal_id">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="DlgModal_desc">Descripción:<span class="text-danger">*</span></label>
                            <input id="DlgModal_desc" type="text" class="form-control text-uppercase" >
                            <div id="error_DlgModal_desc" class="error_msg text-danger d-none"> </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <input type="hidden" id="num_archivo_upload">
                                <label class="form-label">Archivo  (.pdf/.dwg):</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="file" accept="pdf|dwg" data-file_types="pdf|dwg">
                                    <label id="label_file" class="custom-file-label" for="file">Seleccionar archivo ...</label>
                                </div>
                                <div id="alert"></div>
                            </div>
                        </div>            
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="guardar_edit_producto();" type="button" class="btn btn-primary">Guardar</button>
                    <button onclick="close_modal_producto();" type="button" class="btn btn-secondary">Cerrar</button>
                </div>
            </form>            
        </div>
    </div>
</div>
<div class="modal fade default-example-modal" id="DldModalServicios" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="dlg_form_serv">
                <div class="modal-header">
                    <h5 class="modal-title h4">.: Nuevo Servicio :.</h5>
                    <button onclick="close_modal_servicio();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">
                        <input type="hidden" id="DlgModalSer_id">
                        <div class="col-md-6">
                            <label class="form-label" for="DlgModalSer_orden">Orden:</label>
                            <input id="DlgModalSer_orden" type="number" class="form-control text-uppercase" >
                            <div id="error_DlgModalSer_orden" class="error_msg text-danger d-none"> </div>
                        </div>                  
                           
                        <div class="col-md-12 mt-2">
                            <label class="form-label" for="DlgModalSer_id_serv">Descripción de Servicio:<span class="text-danger">*</span></label>
                            <select id="DlgModalSer_id_serv" class="form-control jquery_field" >
                                <option value="">Selecciona</option>
                                @foreach ($servicios as $servicio)
                                    <option value="{{$servicio->id}}">{{$servicio->descripcion}}</option>
                                @endforeach
                            </select>
                            <div id="error_DlgModalSer_id_serv" class="error_msg text-danger d-none"> </div>
                        </div> 
                        <div class="col-md-12 mt-2">
                            <label class="form-label" for="DlgModalSer_precio">Precio:<span class="text-danger">*</span></label>
                            <input id="DlgModalSer_precio" type="text" class="form-control text-uppercase" placeholder="00.00" onkeypress="return isNumberKey(event)">
                            <div id="error_DlgModalSer_precio" class="error_msg text-danger d-none"> </div>
                        </div>                               
                                           
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="guardar_edit_servicio();" type="button" class="btn btn-primary">Guardar</button>
                    <button onclick="close_modal_servicio();" type="button" class="btn btn-secondary">Cerrar</button>
                </div>
            </form>            
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/validar_planos.js') }}"></script>
<script>  
var id_prod_global=0;

$(function () { 
    $("#menu_administracion").addClass("active open");
    $("#submenu_productos").addClass("active"); 
    
    
    $('#lbl_Producto').text('Seleccionar producto...');
    
    var tablaProductos = $('#tableProductos').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,        
        ajax: "{{ route('tabla_productos_get') }}",
        columns: [
            {data: 'id', visible: false, searchable: false},
            {data: 'desc'},
            {data: 'fch_create',className:'text-center'},
            {data: 'ruta_url',className:'text-center'},
            {data: 'costo_tot',className:'text-center'},           
            {data: 'action', name: 'action', searchable: false},
        ],                
        language: espanol,
        ordering: false,
        select: {
            style: "api",
            blurable: true,
            selector: "td:first-child"
        },
        createdRow: function(row, data, dataIndex) {            
            $(row).on("click", function() {  
                if(data.id!=id_prod_global){
                    tablaProductos.rows().deselect();
                    tablaProductos.row(this).select();              
                    // alert(data.id);
                }else{
                    return false;
                }
            });
        }        
    });
   
    var tablaServicios = $('#tableServicios').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,        
        ajax: "tabla-admin-servicios-por-prod/0",
        columns: [
            {data: 'id', visible: false, searchable: false},
            {data: 'orden', width:'20px',className:'text-center'},
            {data: 'descripcion'},
            {data: 'precio',className:'text-center'},
            {data: 'action', width:'100px', className:'text-center', searchable: false},
        ],                      
        language: espanol,
        ordering: false,
        info: false,
        paging: false,
        searching: false     
    });

    

    $('#tableProductos tbody').on('click', 'tr', function(e) {
        MsgDlgLoadAjaxForm("panel_producto");
        //var table = $('#tableProductos').DataTable();
        var row = tablaProductos.row(this);
        var rowData = row.data();
        id_prod_global=rowData.id;
        
        setTimeout(function(){
            $('#tableServicios').DataTable().ajax.url("tabla-admin-servicios-por-prod/"+id_prod_global).load();
            $('#lbl_Producto').text(rowData.desc);
            MsgDlgLoadAjaxFinish("panel_producto"); 
        }, 100);
    });

    
});


function open_producto(){
    $("#DldModalProductos").modal('show');    
}
function close_modal_producto(){
    $("#DlgModal_id").val('');
    $("#file").val(null);
    $("#dlg_form_producto")[0].reset();
    $("#label_file").html('');
    $('#alert').html('').removeAttr('class');
    $('.error_msg').addClass('d-none').text(''); 
    $("#DldModalProductos").modal('hide');
}

function guardar_edit_producto(){
    if($("#DlgModal_desc").val()==""){
        $("#DlgModal_desc").focus(); 
        return maquinge.notificaciones("*Ingrese: Descripción del Producto.", 'Sistema', 'warning');        
    }

    var formData = new FormData($("#dlg_form_producto")[0]);
    formData.append('_token', $('input[name=_token]').val());
    formData.append('id',$("#DlgModal_id").val());
    formData.append('descripcion',($("#DlgModal_desc").val()).toUpperCase());    
    jQuery.each(jQuery('#file')[0].files, function(i, file) {
        formData.append('file[]', file);
    });

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: "{{ url('productos') }}",
        type: 'POST',
        dataType: "html",
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
    }).done(function (data) { 
        var selectedRow = $('#tableProductos').DataTable().row('.selected');       
        $('#tableProductos').DataTable().ajax.url("{{ route('tabla_productos_get') }}").load(function(){
            if (selectedRow) {
                $('#tableProductos').DataTable().row(selectedRow).select();
            }
        });
        close_modal_producto();       
    }).fail(function (data,jqXHR, textStatus, errorThrown) {        
        maquinge.notificaciones("* Error interno.", 'Sistema', 'error');        
        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
    }); 
}

function editar_producto(id_producto){    
    $.ajax({        
        url: 'productos/'+id_producto+'/edit',
        type: 'GET',
        success: function (data) {
            open_producto();
            $("#DlgModal_id").val(data.id);
            $("#DlgModal_desc").val(data.desc);
            if(data.ruta_url!=""){                
                $('#alert').html('Plano: '+data.ruta_url).removeAttr('class').addClass('xd2');
                $("#label_file").text('Seleccionar archivo 1...');
                $('#ver_archivo_1').attr('onClick', 'VerPLanoPdf("'+data.ruta_url+'");');
            }
        },
        error: function (error) {            
            var errors = error.responseJSON;                        
            if (error.status === 500) {
                maquinge.notificaciones(errors.message, 'DHYMARK', 'error');
            }
        }
    });
}

function eliminar_producto(id){ 
    Swal.fire({
        title: "¿ Está seguro que desea eliminar el Produto  ?",
        text: "Esta acción no se puede deshacer!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar"
    }).then(function(result){
        if (result.value){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },        
                url: 'productos/'+id,
                type: 'DELETE', 
                data:{
                    "_token": "{{ csrf_token() }}",
                    id      : id,           
                },         
            }).done(function(data){
                maquinge.notificaciones(data.msg, 'Sistema', 'success');
                $('#tableProductos').DataTable().ajax.url("{{ route('tabla_productos_get') }}").load();
                
            }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
                var errors = error.responseJSON;                        
                    if (error.status === 500) {
                        maquinge.notificaciones(errors.message, 'Sistema', 'error');
                    }               
            });
        }
    });
    
}

function VerPLano(url){
    window.open(url, '_blank').focus();
}



/*SERVICIOS*/

function open_servicio(){
    if(id_prod_global=="" || id_prod_global==0){         
        return maquinge.notificaciones("Seleccione un producto de la tabla PRODUCTOS.", 'Sistema', 'warning');        
    }
    $("#DldModalServicios").modal('show');    
}
function close_modal_servicio(){
    $("#DlgModalSer_id").val('');
    $("#dlg_form_serv")[0].reset(); 
    $('.error_msg').addClass('d-none').text('');
    $("#DldModalServicios").modal('hide');    
}

function guardar_edit_servicio(){
    
    if($("#DlgModalSer_id_serv").val()==""){
        $("#DlgModalSer_id_serv").focus(); 
        return maquinge.notificaciones("Seleccione un servicio.", 'Sistema', 'warning');        
    }
    if($("#DlgModalSer_precio").val()==""){
        $("#DlgModalSer_precio").focus(); 
        return maquinge.notificaciones("Ingrese el precio.", 'Sistema', 'warning');        
    }
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "{{ url('producto-detalle') }}",
        type: 'POST',
        data: {
            id:$("#DlgModalSer_id").val(),
            id_producto:id_prod_global,
            id_serv:$("#DlgModalSer_id_serv").val(),
            precio:$("#DlgModalSer_precio").val(),
            orden:$("#DlgModalSer_orden").val()
        }
    })
    .done(function(data) {
        close_modal_servicio();
        $('#tableServicios').DataTable().ajax.url("tabla-admin-servicios-por-prod/"+id_prod_global).load();
        var selectedRow = $('#tableProductos').DataTable().row('.selected');       
        $('#tableProductos').DataTable().ajax.url("{{ route('tabla_productos_get') }}").load(function(){
            if (selectedRow) {
                $('#tableProductos').DataTable().row(selectedRow).select();
            }
        });
    })
    .fail(function(jqXHR, textStatus, errorThrown) {        
        var error = jqXHR.responseJSON;
        if (error) {
            maquinge.notificaciones(error.message, 'Sistema', 'warning');
        } else {
            maquinge.notificaciones('Error desconocido', 'Sistema', 'warning');
        }
    })
    .always(function() {
        // Ocultar spinner o indicador de carga
    });
}

function editar_servicio(id_prod_det){
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: 'producto-detalle/'+id_prod_det+'/edit',
        type: 'GET',
        
    })
    .done(function(data) {
        open_servicio();
        
        $("#DlgModalSer_id").val(data.id);
        $("#DlgModalSer_orden").val(data.orden);
        $("#DlgModalSer_id_serv").val(data.id_serv);
        $("#DlgModalSer_precio").val(data.precio);
        
        $('#tableServicios').DataTable().ajax.url("tabla-admin-servicios-por-prod/"+id_prod_global).load();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {        
        var error = jqXHR.responseJSON;
        if (error) {
            maquinge.notificaciones(error.message, 'Sistema', 'warning');
        } else {
            maquinge.notificaciones('Error desconocido', 'Sistema', 'warning');
        }
    })
    .always(function() {
        // Ocultar spinner o indicador de carga
    });
}

function eliminar_servicio(id_prod_det){ 
    Swal.fire({
        title: "¿ Está seguro que desea eliminar el Servicio  ?",
        text: "Esta acción no se puede deshacer!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar"
    }).then(function(result){
        if (result.value){
            $.ajax({                
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },       
                url: 'producto-detalle/'+id_prod_det,
                type: 'DELETE',
            }).done(function(data){
                maquinge.notificaciones(data.msg, 'Sistema', 'success');

                $('#tableServicios').DataTable().ajax.url("tabla-admin-servicios-por-prod/"+id_prod_global).load();
                var selectedRow = $('#tableProductos').DataTable().row('.selected');       
                $('#tableProductos').DataTable().ajax.url("{{ route('tabla_productos_get') }}").load(function(){
                    if (selectedRow) {
                        $('#tableProductos').DataTable().row(selectedRow).select();
                    }
                });
                
            }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
                var error = jqXHR.responseJSON;
                if (error) {
                    maquinge.notificaciones(error.message, 'Sistema', 'warning');
                } else {
                    maquinge.notificaciones('Error desconocido', 'Sistema', 'warning');
                }             
            });
        }
    });
    
}



</script>
@endpush
