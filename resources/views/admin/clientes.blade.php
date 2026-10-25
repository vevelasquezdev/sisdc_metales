@extends('layouts.app')
@section('titulo') Clientes @endsection


@section('contenido')

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Tabla</i></span>&nbsp;&nbsp;Clientes 
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
                            Nuevo Cliente
                        </button>
                    </div>
                                  
                    <div class="table-responsive">
                        <table id="tabla" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre y Apellidos</th>                                    
                                    <th>Dni</th>
                                    <th>Ruc</th>
                                    <th>Razon Social</th>
                                    <th>Telefono</th>
                                    <th>Fecha Nac</th> 
                                    <th>Direccion</th>                                  
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
    <div class="modal-dialog modal-dialog-right modal-lg">
        <div class="modal-content">
            <form id="form" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title h4">FORMULARIO DE CLIENTE</h5>
                    <button onclick="close_modal();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">               
                    <div class="card mb-g">
                        <div class="card-body p-3">
                            <div class="form-row">
                                <input type="hidden" id="txt_id">
                                <div class="col-md-5 mb-3">
                                    <label class="form-label" for="txt_nombres">Nombres: <span class="text-danger">*</span> </label>
                                    <input id="txt_nombres" type="text" class="form-control jquery_field text-uppercase" required>
                                    <div id="error_txt_nombres" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label class="form-label" for="txt_apellidos">Apellidos:<span class="text-danger">*</span></label>
                                    <input id="txt_apellidos" type="text" class="form-control jquery_field text-uppercase" required>
                                    <div id="error_txt_apellidos" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label" for="txt_fch_nac">Fecha Nac.:<span class="text-danger">*</span></label>
                                    <input id="txt_fch_nac" type="text" class="form-control jquery_field text-uppercase" required>
                                    <div id="error_txt_fch_nac" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label" for="txt_dni">DNI:<span class="text-danger">*</span></label>
                                    <input id="txt_dni" type="text" class="form-control jquery_field" required>
                                    <div id="error_txt_dni" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label class="form-label" for="txt_email">Email:<span class="text-danger">*</span></label>
                                    <input id="txt_email" type="text" class="form-control jquery_field" required>
                                    <div id="error_txt_email" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="txt_telefono">Telefono:<span class="text-danger">*</span></label>
                                    <input id="txt_telefono" type="text" class="form-control jquery_field" required>
                                    <div id="error_txt_telefono" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="txt_direccion">Dirección:<span class="text-danger">*</span></label>
                                    <input id="txt_direccion" type="text" class="form-control jquery_field" required>
                                    <div id="error_txt_direccion" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="txt_ruc">RUC:<span class="text-danger">*</span></label>
                                    <input id="txt_ruc" type="text" class="form-control jquery_field" required>
                                    <div id="error_txt_ruc" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label" for="txt_razon_social">Razón Social:<span class="text-danger">*</span></label>
                                    <input id="txt_razon_social" type="text" class="form-control jquery_field" required>
                                    <div id="error_txt_razon_social" class="error_msg text-danger d-none"> </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="guardar();" type="button" class="btn btn-primary">Guardar</button>
                    <button onclick="close_modal();" type="button" class="btn btn-secondary">Cerrar</button>
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
    $("#menu_cliente").addClass("active");
    $('#txt_fch_nac').datepicker({
                    todayHighlight: true,                    
                    templates: controls,
                    format: 'yyyy-mm-dd'
                }); 
    var table = $('#tabla').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('clientes.list') }}",
        columns: [
             {data: 'id',searchable: false,visible: false},                                     
            {data: 'persona'},
            {data: 'dni'},
            {data: 'ruc'},
            {data: 'raz_soc'},
            {data: 'telefono'},  
            {data: 'fch_nac'}, 
            {data: 'direccion',searchable: false},   
            {data: 'action', searchable: false},
        ],
        dom: dom_buttons_table,
        buttons: [{
            extend: 'pdfHtml5',
            text: 'PDF',
            titleAttr: 'Generate PDF',
            className: 'btn-outline-danger btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3,4,5,6]
            }
        },{
            extend: 'excelHtml5',
            text: 'Excel',
            titleAttr: 'Generate Excel',
            className: 'btn-outline-success btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3,4,5,6]
            }
        }],
        lengthMenu: [ [50, 100, -1], [50, 100, "All"] ],
        language: espanol,
        ordering: false,
    });
    
});

function getdata(id){ 
    $.ajax({
        url: 'clientes/'+id+'/edit',
        type: 'GET',        
    }).done(function(data){
        $("#txt_id").val(data.id);
        $("#txt_nombres").val(data.name);
        $("#txt_apellidos").val(data.surname);
        $("#txt_fch_nac").val(data.fch_nac);
        $("#txt_dni").val(data.dni);
        $("#txt_email").val(data.email);
        $("#txt_ruc").val(data.ruc);
        $("#txt_razon_social").val(data.raz_soc);
        $("#txt_telefono").val(data.telefono);
        $("#txt_direccion").val(data.direccion);
    }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
        var errors = error.responseJSON;                        
        if (error.status === 500) {
            maquinge.notificaciones(errors.msg, 'Sistema', 'error');
        }       
    }); 
}

function guardar(){
    $('.error_msg').addClass('d-none').text('');
    MsgDlgLoadAjaxForm("form");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },        
        url: 'clientes',
        type: 'POST', 
        data:{
            "_token": "{{ csrf_token() }}",
            id              : $("#txt_id").val(),
            nombres         : ($("#txt_nombres").val()).toUpperCase(),
            apellidos       : ($("#txt_apellidos").val()).toUpperCase(),
            fch_nac         : $("#txt_fch_nac").val(),
            dni             : $("#txt_dni").val(),
            email           : $("#txt_email").val(),
            ruc             : $("#txt_ruc").val(),
            razon_social    : ($("#txt_razon_social").val()).toUpperCase(),
            telefono        : $("#txt_telefono").val(),
            direccion       : ($("#txt_direccion").val()).toUpperCase()
        },         
    }).done(function(data){
        setTimeout(function(){    MsgDlgLoadAjaxFinish("form"); }, 500);      
        
        $('#tabla').DataTable().ajax.url("{!! route('clientes.list') !!}").load();
        maquinge.notificaciones(data.msg, 'Sistema.', 'success');
        close_modal();
    }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
        var response = JSON.parse(error.responseText);   
        $.each( response.errors, function( key, value) {
            $('#error_txt_' + key).removeClass('d-none').text(value);
        });

        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
        if (error.status === 500) {
            maquinge.notificaciones('Error interno comun&iacute;quese con el &aacute;rea de sistemas', 'Sistema', 'error');
        }        
        MsgDlgLoadAjaxFinish("form");            
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
                url: 'clientes/'+id,
                type: 'DELETE', 
                data:{
                    "_token": "{{ csrf_token() }}",
                    id      : id,           
                },         
            }).done(function(data){
                maquinge.notificaciones(data.msg, 'Sistema', 'success');
                $('#tabla').DataTable().ajax.url("{!! route('clientes.list') !!}").load();
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
