@extends('layouts.app')
@section('titulo') Lista de Trabajos @endsection


@section('contenido')

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Tabla</i></span>&nbsp;&nbsp;Lista de Trabajos
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>                    
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row ml-1 mb-3">
                        
                    </div>
                                  
                    <div class="table-responsive">
                        <table id="tabla" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>ID</th>
                                    <th>Cod.Orden</th>
                                    <th>Recepción</th>                
                                    <th>Cant</th>
                                    <th>Descripción del Trabajo</th>
                                    <th>Comentario y/o observación</th>
                                    <th>Estado</th>
                                    <th>Fecha Entrega</th>
                                    <th>Fecha Entrada</th>
                                    <th>Fecha Ejecución</th>
                                    <th>Plano</th> 
                                    <th>Comentario del Operador</th>        
                                    <th>Acciones</th>
                                </tr>
                            </thead>                    
                        </table>                                             
                    </div>
                    <div class="accordion accordion-outline mt-4" id="js_demo_accordion-3">
                        <div class="card">
                            <div class="card-header">
                                <a href="javascript:void(0);" class="card-title collapsed pt-2 pb-2" data-toggle="collapse" data-target="#js_demo_accordion-3b" aria-expanded="false">
                                    SERVICIOS TERMINADOS
                                    <span class="ml-auto">
                                        <span class="collapsed-reveal">
                                            <i class="fal fa-minus fs-xl"></i>
                                        </span>
                                        <span class="collapsed-hidden">
                                            <i class="fal fa-plus fs-xl"></i>
                                        </span>
                                    </span>
                                </a>
                            </div>
                            <div id="js_demo_accordion-3b" class="collapse" data-parent="#js_demo_accordion-3">
                                <div class="card-body">
                                    <div class="col-xl-12">                                                                                                                                        
                                        <div class="table-responsive">
                                            <table id="tablaTerminados" class="table table-bordered table-hover table-striped w-100">
                                                <thead class="bg-primary-600">
                                                    <tr>                                                        
                                                        <th>Cod.Orden</th>   
                                                        <th>Recepción</th>                                                             
                                                        <th>Cant</th>
                                                        <th>Descripción del Trabajo</th>
                                                        <th>Comentario y/o observación</th>
                                                        <th>Estado</th>
                                                        <th>Fecha Entrega</th>
                                                        <th>Fecha Entrada</th>
                                                        <th>Fecha Ejecución.</th>
                                                        <th>Fecha Salida</th>
                                                        <th>Plano</th>                     
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
</div>
<div class="modal fade default-example-modal" id="DldModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form">
                <div class="modal-header">
                    <h5 class="modal-title h4">.: Colaboradores :.</h5>
                    <button onclick="close_modal();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">
                        <input type="hidden" id="id_serv">
                        <div class="col-md-8 mb-3">
                            <label class="form-label" for="DlgModal_id_operador">Operadores:<span class="text-danger">*</span></label>
                            <select id="DlgModal_id_operador" class="form-control jquery_field text-uppercase">
                                <option value="">Selecciona</option>
                                @foreach ($operadores as $operador)
                                    <option value="{{$operador->id}}">{{$operador->name.' '.$operador->surname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mt-3">
                            <button onclick="add_colaborador();" type="button" class="btn btn-info">Agregar</button>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <div class="table-responsive mt-3">
                                <table id="tabla_colaboradores" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombres y Apellidos</th>
                                            <th>Tipo</th> {{-- encargado y vacio --}}
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>                    
                                </table>                                             
                            </div>
                        </div>                             
                                                       
                    </div>
                </div>
                <div class="modal-footer">                    
                    <button onclick="close_modal();" type="button" class="btn btn-secondary">Salir</button>
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
                                data-toggle="tooltip" data-original-title="Ver archivo 1"><i class="fal fa-file-image"></i>
                            </a>
                            <a onclick="modal_upload(1);" href="javascript:void(0);" id="subir_1" class="btn btn-sm btn-danger btn-icon waves-effect waves-themed" data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-dark&quot;></div></div>"
                                data-toggle="tooltip" data-original-title="Subir archivo 1"><i class="fal fa-upload"></i>
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
                            <a onclick="modal_upload(2);" href="javascript:void(0);" id="subir_2" class="btn btn-sm btn-danger btn-icon waves-effect waves-themed" data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-dark&quot;></div></div>"
                                data-toggle="tooltip" data-original-title="Subir archivo 2"><i class="fal fa-upload"></i>
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
                            <a onclick="modal_upload(3);" href="javascript:void(0);" id="subir_3" class="btn btn-sm btn-danger btn-icon waves-effect waves-themed" data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-dark&quot;></div></div>"
                                data-toggle="tooltip" data-original-title="Subir archivo 3"><i class="fal fa-upload"></i>
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
                            <a onclick="modal_upload(4);" href="javascript:void(0);" id="subir_4" class="btn btn-sm btn-danger btn-icon waves-effect waves-themed" data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-dark&quot;></div></div>"
                                data-toggle="tooltip" data-original-title="Subir archivo 4"><i class="fal fa-upload"></i>
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
                            <a onclick="modal_upload(5);" href="javascript:void(0);" id="subir_5" class="btn btn-sm btn-danger btn-icon waves-effect waves-themed" data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-dark&quot;></div></div>"
                                data-toggle="tooltip" data-original-title="Subir archivo 5"><i class="fal fa-upload"></i>
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

<div class="modal fade default-example-modal" id="DldModalUpload" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="dlg_form">
                <div class="modal-header">
                    <h5 class="modal-title h4">.: Subir Archivo :.</h5>
                    <button onclick="close_modal_upload();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">                        
                        
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group mb-0">
                                        <input type="hidden" id="num_archivo_upload">
                                        <label class="form-label">Archivo  (.pdf/.jpg/.jpeg/.dwg):</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file" name="file" accept="pdf|jpg|jpeg|dwg" data-file_types="pdf|jpg|jpeg|dwg">
                                            <label id="label_file" class="custom-file-label" for="file">Seleccionar archivo ...</label>
                                        </div>
                                        <div id="alert"></div>
                                    </div>
                                </div>                                
                            </div>
                        </div>                     
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="guardar_file();" type="button" class="btn btn-primary">Guardar</button>  
                    <button onclick="close_modal_upload();" type="button" class="btn btn-secondary">Cerrar</button>                    
                </div>
            </form>            
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script src="{{ asset('js/validar_planos.js') }}"></script>
<script>  
$(document).keyup(function(e){
    if(e.which==27) {
        close_modal();
    }
});
$(function () { 
    $("#menu_lista_trabajos").addClass("active");
    $('#txt_fch').datepicker({
                    todayHighlight: true,                    
                    templates: controls,
                    format: 'yyyy-mm-dd'
                }); 
    var table = $('#tabla').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "trabajos",
        columns: [
            {data: 'id',visible: false, searchable: false},                                     
            {data: 'codigo_orden',visible: false, },
            {data: 'id_user'},          
            {data: 'cant',className:'text-center'},
            {data: 'descripcion'},
            {data: 'comentario'},
            {data: 'estado_trab'},
            {data: 'fch_entrega'}, 
            {data: 'fch_ent'},
            {data: 'fch_hora_ejec'},            
            {data: 'ruta_url'},
            {data: 'comentario_operador'},
            {data: 'action', searchable: false,width: '21%',className:'text-center'},
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
        order: [
            [1, 'desc']
        ],
        rowGroup:{
            dataSrc: 'codigo_orden'
        }, 
        lengthMenu: [ [15,30, 50,100, -1], [15,30, 50,100, "All"] ],
        language: espanol,
        ordering: false,
        "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData.correccion == "1" || aData.correccion == 1) {
                $('td', nRow).css('background-color', '#FFFFCC');
            } 
        }
    });
    var table2 =  $('#tablaTerminados').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "trabajos-terminados",
        columns: [                         
            {data: 'codigo_orden', visible: false},
            {data: 'id_user'},           
            {data: 'cant',className:'text-center'},                
            {data: 'descripcion'},
            {data: 'comentario'},
            {data: 'estado_trab'},
            {data: 'fch_entrega'},  
            {data: 'fch_ent'},
            {data: 'fch_hora_ejec'}, 
            {data: 'fch_sal'},           
            {data: 'ruta_url'},
        ],
        dom: dom_buttons_table,
        buttons: [{
            extend: 'pdfHtml5',
            text: 'PDF',
            titleAttr: 'Generate PDF',
            className: 'btn-outline-danger btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9]
            }
        },{
            extend: 'excelHtml5',
            text: 'Excel',
            titleAttr: 'Generate Excel',
            className: 'btn-outline-success btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9]
            }
        }],
        rowGroup:{
            dataSrc: 'codigo_orden'
        },
        lengthMenu: [ [15, 30, 50,100, -1], [15, 30, 50,100, "All"] ],
        language: espanol,
        ordering: false,
    });

    $('#tabla_colaboradores').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "tabla-colaboradores/0",
        columns: [
            {data: 'id_operador'},
            {data: 'nombres_apellidos'},
            {data: 'tipo'},
            {data: 'action', searchable: false},
        ],        
        lengthMenu: [ [5, 10, -1], [5, 10, "All"] ],
        language: espanol,
        ordering: false,
        "info": false,
        "searching": false,
        "lengthChange": false,
        "bPaginate": false
    });  
    
    // setInterval( function() {       
    //     table.ajax.reload(null, false);
    //     table2.ajax.reload(null, false);
    // },20000);
});

function InicioTrabajo(id){ 
    Swal.fire({
        title: "¿ Quieres empezar este trabajo ?",
        text: "Si aceptas el tiempo empezará a correr...!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar"
    }).then(function(result){
        if (result.value){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },        
                url: 'lista-trabajo/'+id+'/edit',
                type: 'GET', 
                data:{
                    "_token": "{{ csrf_token() }}",                          
                },         
            }).done(function(data){                
                $('#tabla').DataTable().ajax.url("{!! route('trabajos') !!}").load(); 
            }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
                maquinge.notificaciones(textStatus+" interno.", 'Sistema', 'error');        
                initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');            
            });
        }
    });
   
    
    
}

function enviarSgteServicio(id_list_trab,id_servicio,id_orden,numeracion,servicio,llave,grupal){//der por terminado el trabajo

    if(llave==0){
        return maquinge.notificaciones("* Solo el encargado puede dar por terminado este trabajo...", 'Sistema', 'warning');
    }

    Swal.fire({
        title: "¿Esta seguro de dar por terminado este trabajo?",
        text: "Esta acción no se puede deshacer!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar"
    }).then(function(result){
        if (result.value){
            $.ajax({        
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                url: 'enviar-sgte-servicio/'+id_list_trab+'/'+id_servicio+'/'+id_orden+'/'+numeracion,
                type: 'GET',
                data:{
                    "_token": "{{ csrf_token() }}",                    
                }                  
            }).done(function(data){        
                $('#tabla').DataTable().ajax.url("{!! route('trabajos') !!}").load();
                $('#tablaTerminados').DataTable().ajax.url("trabajos-terminados").load();       
            }).fail( function(jqXHR, textStatus, errorThrown ) {
                maquinge.notificaciones(textStatus+" interno.", 'Sistema', 'error');        
                initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
            });
        }
    });
   
}

function UltimoServicio(id_servicio,id_orden,llave){

    if(llave==0){
        return maquinge.notificaciones("* No tiene permiso para esta acción.", 'Sistema', 'warning');
    }
    
    Swal.fire({
        title: "¿Esta seguro de terminar este trabajo?",
        text: "Esta acción no se puede deshacer!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar"
    }).then(function(result){
        if (result.value){
            $.ajax({        
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                url: 'ultimo-servicio/'+id_servicio+'/'+id_orden,
                type: 'GET',
                data:{
                    "_token": "{{ csrf_token() }}",           
                }                  
            }).done(function(data){        
                $('#tabla').DataTable().ajax.url("{!! route('trabajos') !!}").load();    
                $('#tablaTerminados').DataTable().ajax.url("trabajos-terminados").load();    
            }).fail( function(jqXHR, textStatus, errorThrown ) {
                maquinge.notificaciones(textStatus+" interno.", 'Sistema', 'error');        
                initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
            });
        }
    });
}

function VerPLanoPdf(url){
    window.open(url, '_blank').focus();
}


function add_colaborador(){
    if($("#DlgModal_id_operador").val()==""){
        $("#DlgModal_id_operador").focus(); 
        return maquinge.notificaciones("Seleccione un operador...", 'Sistema', 'warning');        
    }
    $.ajax({        
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'add-colaborador/'+$("#id_serv").val(),
        type: 'GET',
        data:{
            "_token": "{{ csrf_token() }}",
            id_operario: $("#DlgModal_id_operador").val()
        }                  
    }).done(function(data){ 
        if(data.msg==0){
            return maquinge.notificaciones("* No cuenta con permiso para agregar colaborador.<br>* Solo el encargado puede realizar esta acción.", 'Sistema', 'warning');
        }else{
            maquinge.notificaciones("Colaborador agregado.", 'Sistema', 'success');
            $('#tabla_colaboradores').DataTable().ajax.url("tabla-colaboradores/"+$("#id_serv").val()).load();
        }
        
    }).fail( function(jqXHR, textStatus, errorThrown ) {
        maquinge.notificaciones(textStatus+" interno.", 'Sistema', 'error');        
        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
    });
}

function deleteColaborador(id_lista_trabajo,id_orden_det){ 
    
    $.ajax({        
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'check-encargado-trabajo/'+id_lista_trabajo,
        type: 'GET',
        data:{"_token": "{{ csrf_token() }}", }                  
    }).done(function(data){ 
        if(data.llave==1){
            return maquinge.notificaciones("No se puede eliminar al encargado del trabajo.", 'Sistema', 'warning');
        }else{

            Swal.fire({
                title: "¿ Está seguro de eliminar este colaborador?",
                text: "Esta acción no se puede deshacer!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar",
                target: document.getElementById('DldModal')
            }).then(function(result){
                if (result.value){
                    $.ajax({        
                        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                        url: 'delete-colaborador/'+id_lista_trabajo,
                        type: 'GET',
                        data:{
                            "_token": "{{ csrf_token() }}",
                            id_orden_det: id_orden_det          
                        }                  
                    }).done(function(data){ 
                        if(data.msg==0){
                            return maquinge.notificaciones("No cuenta con permiso para eliminar<br>* Solo el encargado puede realizar esta acción.", 'Sistema', 'warning');
                        }else{
                            $('#tabla_colaboradores').DataTable().ajax.url("tabla-colaboradores/"+$("#id_serv").val()).load();
                            return maquinge.notificaciones("Colaborador Eliminado..", 'Sistema', 'success');
                        }
                        
                    }).fail( function(jqXHR, textStatus, errorThrown ) {
                        maquinge.notificaciones(textStatus+" interno.", 'Sistema', 'error');        
                        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
                    });
                }
            });

        }
        
    }).fail( function(jqXHR, textStatus, errorThrown ) {
        maquinge.notificaciones(textStatus+" interno.", 'Sistema', 'error');        
        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
    });
    
}

function add_coment(id){  /// comentario por parte del operario  
    bootbox.prompt({
        title: "Escribe aqui comentario y/o observación...", 
        centerVertical: true,
        backdrop: false,        
        callback: function(result){            
            if(result){
                $.ajax({ 
                    headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                    url: 'add_coment_trabajo',
                    type: 'GET',
                    data:{                    
                        id : id,
                        comentario : result          
                    },
                }).done(function (data) {
                    $('#tabla').DataTable().ajax.url("{!! route('trabajos') !!}").load();
                }).fail(function (error, jqXHR, textStatus,errorThrown) {
                    var errors = error.responseJSON;                        
                    if (errors.status === 500) {
                        maquinge.notificaciones(error.msg, 'Sistema', 'error');
                    }                                       
                });                
            }        
        },
        
    });
}


function pausa_tiempo(id_lista_trabajo){
    Swal.fire({
        title: "¿Seguro que quieres pausar este trabajo?",
        text: "Se dejará de contar el tiempo.",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar",               
    }).then(function(result){
        if (result.value){
            $.ajax({        
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                url: 'pausar-trabajo',
                type: 'GET',
                data:{
                    "_token": "{{ csrf_token() }}",
                    id_lista_trabajo: id_lista_trabajo          
                }                  
            }).done(function(data){ 
                $('#tabla').DataTable().ajax.url("{!! route('trabajos') !!}").load();
            }).fail( function(jqXHR, textStatus, errorThrown ) {
                maquinge.notificaciones(textStatus+" interno.", 'Sistema', 'error');        
                initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
            });
        }
    });
}

function reanudar_tiempo(id_lista_trabajo){
    Swal.fire({
        title: "¿Seguro que quieres reanudar este trabajo?",
        text: "El tiempo volvera a contabilizarse.",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar",               
    }).then(function(result){
        if (result.value){            
            $.ajax({        
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                url: 'reanudar-trabajo',
                type: 'GET',
                data:{
                    "_token": "{{ csrf_token() }}",
                    id_lista_trabajo: id_lista_trabajo          
                }                  
            }).done(function(data){ 
                $('#tabla').DataTable().ajax.url("{!! route('trabajos') !!}").load();
            }).fail( function(jqXHR, textStatus, errorThrown ) {
                maquinge.notificaciones(textStatus+" interno.", 'Sistema', 'error');        
                initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
            });
        }
    });
}

function open_modal(id){
    $("#DldModal").modal('show');
    $("#id_serv").val(id);
    $('#tabla_colaboradores').DataTable().ajax.url("tabla-colaboradores/"+id).load();
}

function close_modal(){  
    $("#DldModal").modal('hide');    
    $('.error_msg').addClass('d-none').text('');  
    $("#form")[0].reset();
}
var global_id_orden;
function subir_archivo(id_orden, id_detalle){
    $("#DldModalVerArchivos").modal('show');
    global_id_orden=id_orden;
    $.ajax({ 
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'ver_archivos_subidos/'+id_orden,
        type: 'GET'                    
    }).done(function (data) {
        
        if(data.ruta_url=="" || data.ruta_url==null){                
            $('#alertArchivo1').html('Plano: '+data.ruta_url).removeAttr('class').addClass('xd');
            $("#subir_1").show();           
        }else{
            $('#alertArchivo1').html('Plano: '+data.ruta_url).removeAttr('class').addClass('xd2');           
            $('#ver_archivo_11').attr('onClick', 'VerPLanoPdf("'+data.ruta_url+'");'); 
            $("#subir_1").hide();          
        }

        if(data.ruta_url2=="" || data.ruta_url2==null){                
            $('#alertArchivo2').html('Plano: '+data.ruta_url2).removeAttr('class').addClass('xd');
            $("#subir_2").show(); 
        }else{
            $('#alertArchivo2').html('Plano: '+data.ruta_url2).removeAttr('class').addClass('xd2');         
            $('#ver_archivo_22').attr('onClick', 'VerPLanoPdf("'+data.ruta_url2+'");');
            $("#subir_2").hide(); 
        }

        if(data.ruta_url3=="" || data.ruta_url3==null){                
            $('#alertArchivo3').html('Plano: '+data.ruta_url3).removeAttr('class').addClass('xd');
            $("#subir_3").show();
        }else{
            $('#alertArchivo3').html('Plano: '+data.ruta_url3).removeAttr('class').addClass('xd2');         
            $('#ver_archivo_33').attr('onClick', 'VerPLanoPdf("'+data.ruta_url3+'");');
            $("#subir_3").hide();
        }

        if(data.ruta_url4=="" || data.ruta_url4==null){                
            $('#alertArchivo4').html('Plano: '+data.ruta_url4).removeAttr('class').addClass('xd');
            $("#subir_4").show();
        }else{
            $('#alertArchivo4').html('Plano: '+data.ruta_url4).removeAttr('class').addClass('xd2');         
            $('#ver_archivo_44').attr('onClick', 'VerPLanoPdf("'+data.ruta_url4+'");');
            $("#subir_4").hide();
        }

        if(data.ruta_url5=="" || data.ruta_url5==null){                
            $('#alertArchivo5').html('Plano: '+data.ruta_url5).removeAttr('class').addClass('xd');
            $("#subir_5").show();
        }else{
            $('#alertArchivo5').html('Plano: '+data.ruta_url5).removeAttr('class').addClass('xd2');         
            $('#ver_archivo_55').attr('onClick', 'VerPLanoPdf("'+data.ruta_url5+'");');
            $("#subir_5").hide();
        }

        
    }).fail(function (error, jqXHR, textStatus,errorThrown) {
        var errors = error.responseJSON;                        
        if (errors.status === 500) {
            maquinge.notificaciones(error.msg, 'Sistema', 'error');
        }                                       
    }); 
}

function modal_upload(num){
    $("#DldModalUpload").modal('show');
    $("#num_archivo_upload").val(num);
}

function guardar_file(){
    var archivo = $('#file')[0].files[0];
    var formData = new FormData();
    var num = $("#num_archivo_upload").val();
    formData.append('_token', $('input[name=_token]').val());
    formData.append('archivo', archivo);
    formData.append('id_orden',global_id_orden);
    formData.append('num',num);
   
    $.ajax({       
        type: 'POST',
        dataType: "html",
        url: "subir-archivo-operador",
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: function(data) {
            
            close_modal_upload();
            close_modal_ver_archivos();
        }
    });
}

function close_modal_upload(){
    $("#DldModalUpload").modal('hide');
    $("#file").val('');
    $("#label_file").html('');
    $('#alert').html('').removeAttr('class');
}


</script>
@endpush
