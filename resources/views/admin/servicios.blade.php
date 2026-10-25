@extends('layouts.app')
@section('titulo') Servicios @endsection


@section('contenido')

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Tabla</i></span>&nbsp;&nbsp;SERVICIOS 
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>                    
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row ml-1 mb-3">
                        <button class="btn btn-primary" data-toggle='modal' data-target='.default-example-modal-right'>
                            Agregar Nuevo Servicio
                        </button>
                    </div>
                                  
                    <div class="table-responsive">
                        <table id="tabla" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>ID</th>
                                    <th>Orden</th>
                                    <th>Descripción</th>                                    
                                    <th>Máquina</th>
                                    <th>Local</th>
                                    <th>Automatizado</th>
                                    <th>Operador</th>                              
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
<div class="modal fade default-example-modal-right" id="DldModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-right">
        <div class="modal-content">
            <form id="form" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title h4">FORMULARIO DE SERVICIOS</h5>
                    <button onclick="close_modal();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">               
                    <div class="card mb-g">
                        <div class="card-body p-3">
                            <div class="form-row">
                                <input type="hidden" id="txt_id">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="txt_descripcion">Descripcion: <span class="text-danger">*</span> </label>
                                    <input id="txt_descripcion" type="text" class="form-control jquery_field text-uppercase" required>
                                    <div id="error_txt_descripcion" class="error_msg text-danger d-none">sdsd </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="txt_maquina">Máquina<span class="text-danger">*</span></label>
                                    <input id="txt_maquina" type="text" class="form-control jquery_field text-uppercase" required>
                                    <div id="error_txt_maquina" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="txt_local">Local<span class="text-danger">*</span></label>
                                    <select id="txt_local" name="txt_local" class="form-control jquery_field text-uppercase" required>
                                        <option value="">Selecciona</option>
                                        <option value="RIO_SECO">RIO SECO</option>
                                        <option value="ZOLEZZI">ZOLEZZI</option>
                                    </select>
                                    <div id="error_txt_local" class="error_msg text-danger d-none"> </div>
                                    
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="txt_orden">Orden de Prioridad:<span class="text-danger">*</span></label>
                                    <input id="txt_orden" type="number" class="form-control jquery_field" required>
                                    <div id="error_txt_orden" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label" for="txt_id_operador">Derivar automaticamente al Operador:<span class="text-danger">*</span></label>
                                    <select id="txt_id_operador" class="form-control jquery_field text-uppercase">
                                        <option value="">Selecciona:</option>
                                        @foreach ($operadores as $operador)
                                            <option value="{{$operador->id}}">{{$operador->name.' '.$operador->surname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="close_modal();" type="button" class="btn btn-secondary">Cerrar</button>
                    <button onclick="guardar();" type="button" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>  
$(document).keyup(function(e){
    if(e.which==27) {
        close_modal();
    }
});
$(function () { 
    $("#menu_administracion").addClass("active open");
    $("#submenu_servicios").addClass("active");   
    var table = $('#tabla').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('vwservicios') }}",
        columns: [
            {data: 'id', className:'text-center',visible: false, searchable: false},
            {data: 'orden', className:'text-center'},                                  
            {data: 'descripcion'},
            {data: 'maquina'},  
            {data: 'local', className:'text-center'},
            {data: 'auto',className:'text-center'},
            {data: 'id_operador'},
            {data: 'action', name: 'action', searchable: false},
        ],
        dom: dom_buttons_table,
        buttons: [{
            extend: 'pdfHtml5',
            text: 'PDF',
            titleAttr: 'Generate PDF',
            className: 'btn-outline-danger btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3]
            }
        },{
            extend: 'excelHtml5',
            text: 'Excel',
            titleAttr: 'Generate Excel',
            className: 'btn-outline-success btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3]
            }
        }],
        lengthMenu: [ [50, 100, -1], [50, 100, "All"] ],
        language: espanol,
        ordering: false,
    });
    
});

function getdata(id){  
    $.ajax({        
        url: 'servicios/'+id+'/edit',
        type: 'GET',
        success: function (data) {
            $("#txt_id").val(data.id);
            $("#txt_descripcion").val(data.descripcion);
            $("#txt_maquina").val(data.maquina);
            $("#txt_local").val(data.local);
            $("#txt_orden").val(data.orden);
            $("#txt_id_operador").val(data.id_operador);
        },
        error: function (error) {            
            var errors = error.responseJSON;                        
            if (error.status === 500) {
                maquinge.notificaciones(errors.msg, 'DHYMARK', 'error');
            }
        }
    });
}

function guardar(){
    $('.error_msg').addClass('d-none').text('');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'servicios',
        type: 'POST',
        data:{
            "_token": "{{ csrf_token() }}",
            id      : $("#txt_id").val(),
            descripcion: ($("#txt_descripcion").val()).toUpperCase(),
            maquina : ($("#txt_maquina").val()).toUpperCase(),
            local : $("#txt_local").val(),
            orden : $("#txt_orden").val(),
            id_operador : $("#txt_id_operador").val()

        },
        success: function (data) {
            $('#tabla').DataTable().ajax.url("{{ route('vwservicios') }}").load();
            maquinge.notificaciones(data.msg, 'DHYMARK', 'success');
            close_modal();
        },
        error: function (data) {
            var response = JSON.parse(data.responseText);            
            $.each( response.errors, function( key, value) {
                $('#error_txt_' + key).removeClass('d-none').text(value);
            });   
            initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
            var errors = data.responseJSON;                        
            if (error.status === 500) {
                maquinge.notificaciones(errors.msg, 'Sistema', 'error');
            } 
        }
    });
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
                url: 'servicios/'+id,
                type: 'DELETE', 
                data:{
                    "_token": "{{ csrf_token() }}",
                    id      : id,           
                },         
            }).done(function(data){
                maquinge.notificaciones(data.msg, 'Sistema', 'success');
                $('#tabla').DataTable().ajax.url("{{ route('vwservicios') }}").load();
                
            }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
                var errors = error.responseJSON;                        
                    if (error.status === 500) {
                        maquinge.notificaciones(errors.msg, 'Sistema', 'error');
                    }               
            });
        }
    });
    
}

function close_modal(){  
    $("#DldModal").modal('hide');
    $("#txt_id").val('');
    $('.error_msg').addClass('d-none').text('');  
    $("#form")[0].reset();
}




</script>
@endpush
