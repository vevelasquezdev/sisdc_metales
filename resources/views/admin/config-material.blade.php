@extends('layouts.app')
@section('titulo') Materiales Retazos @endsection


@section('contenido')

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Tabla</i></span>&nbsp;&nbsp;Materiales para Retazos
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
                            Nuevo Material
                        </button>
                    </div>
                                  
                    <div class="table-responsive">
                        <table id="tabla" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>ID</th>
                                    <th>Descripci&oacute;n de Material</th>                                    
                                    <th>Abreviaci&oacute;n</th>
                                    <th>Precio (kg)</th>                                                                     
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
                    <h5 class="modal-title h4">FORM MATERIALES</h5>
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
                                    <label class="form-label" for="txt_desc">Descripci&oacute;n: <span class="text-danger">*</span> </label>
                                    <input id="txt_desc" type="text" class="form-control jquery_field text-uppercase" required>
                                    <div id="error_txt_desc" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="txt_abreviacion">Abreviaci&oacute;n:<span class="text-danger">*</span></label>
                                    <input id="txt_abreviacion" type="text" class="form-control jquery_field text-uppercase" required>
                                    <div id="error_txt_abreviacion" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="txt_precio">Precio:<span class="text-danger">*</span></label>
                                    <input id="txt_precio" type="text" placeholder="00.00" onkeypress="return isNumberKey(event)" class="form-control jquery_field text-uppercase" required>
                                    <div id="error_txt_precio" class="error_msg text-danger d-none"> </div>
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
    $("#submenu_confmaterial").addClass("active"); 
    $('#txt_fch_nac').datepicker({
                    todayHighlight: true,                    
                    templates: controls,
                    format: 'yyyy-mm-dd'
                }); 
    var table = $('#tabla').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('confmaterial.list') }}",
        columns: [
            {data: 'id',visible: false, searchable: false},                                     
            {data: 'desc'},
            {data: 'abreviacion'},
            {data: 'precio'},            
            {data: 'action', searchable: false},
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
        lengthMenu: [ [15, 50, -1], [15, 50, "All"] ],
        language: espanol,
        ordering: false,
    });
    
});

function getdata(id){ 
    $.ajax({
        url: 'confmaterial/'+id+'/edit',
        type: 'GET',        
    }).done(function(data){
        $("#txt_id").val(data.id);
        $("#txt_desc").val(data.desc);
        $("#txt_abreviacion").val(data.abreviacion);
        $("#txt_precio").val(data.precio);
        
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
        url: 'confmaterial',
        type: 'POST', 
        data:{
            "_token": "{{ csrf_token() }}",
            id           : $("#txt_id").val(),
            desc         : ($("#txt_desc").val()).toUpperCase(),
            abreviacion  : ($("#txt_abreviacion").val()).toUpperCase(),
            precio       : $("#txt_precio").val()
        },         
    }).done(function(data){
        setTimeout(function(){    MsgDlgLoadAjaxFinish("form"); }, 500);      
        
        $('#tabla').DataTable().ajax.url("{!! route('confmaterial.list') !!}").load();
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
                url: 'confmaterial/'+id,
                type: 'DELETE', 
                data:{
                    "_token": "{{ csrf_token() }}",
                    id      : id,           
                },         
            }).done(function(data){
                maquinge.notificaciones(data.msg, 'Sistema', 'success');
                $('#tabla').DataTable().ajax.url("{!! route('confmaterial.list') !!}").load();
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
