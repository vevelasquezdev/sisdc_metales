@extends('layouts.app')
@section('titulo') Productos de Almaden @endsection


@section('contenido')

<div class="row">    
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Productos/Insumos de</i></span>&nbsp;&nbsp;ALMACEN
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
                            Nuevo Producto 
                        </button>
                    </div>
                                  
                    <div class="table-responsive">
                        <table id="tabla" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>Id</th>
                                    <th>Descripción</th>                                                                        
                                    <th>Abrev.</th>
                                    <th>Calidad</th>
                                    <th>Dimensión (mm)</th>
                                    <th>Espesor (mm)</th>                                    
                                    <th>Acabado</th>
                                    <th>Peso (kg)</th>
                                    <th>Fecha</th>
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
                    <h5 class="modal-title h4">.: FORMULARIO DE PRODUCTOS ALMACEN :.</h5>
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
                                    <label class="form-label" for="txt_descripcion">Descripci&oacute;n: <span class="text-danger">*</span> </label>
                                    <input id="txt_descripcion" type="text" placeholder="Ejemplo: plancha inoxidable" class="form-control text-uppercase" required>
                                    <div id="error_txt_descripcion" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="txt_abreviacion">Abreviaci&oacute;n:<span class="text-danger">*</span></label>
                                    <input id="txt_abreviacion" type="text" placeholder="Ejemplo: pl" class="form-control text-uppercase" required>
                                    <div id="error_txt_abreviacion" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="txt_dimension">Dimensi&oacute;n (mm):<span class="text-danger">*</span> </label>
                                    <select id="txt_dimension" name="txt_dimension" class="form-control" required>
                                        <option value="">Selecciona</option>
                                        <option value="2440x1220">2440x1220</option>
                                        <option value="3000x1500">3000x1500</option>                                        
                                    </select>
                                    <div id="error_txt_dimension" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="txt_espesor">Espesor (mm):<span class="text-danger">*</span></label>
                                    <input id="txt_espesor" type="text" placeholder="Ejemplo: 0.5 / 1.0 / 6.5 / 25.0" onkeypress="return isNumberKey(event)" class="form-control" required>
                                    <div id="error_txt_espesor" class="error_msg text-danger d-none"> </div>
                                </div>                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="txt_calidad">Calidad:<span class="text-danger">*</span> </label>
                                    <select id="txt_calidad" name="txt_calidad" class="form-control" required>
                                        <option value="">Selecciona</option>
                                        <optgroup label="Acero Negro">
                                            <option value="LAF">LAF</option>
                                            <option value="LAC">LAC</option>
                                        </optgroup>
                                        <optgroup label="Acero Inoxidable">
                                            <option value="C201">C201</option>
                                            <option value="C304">C304</option>
                                            <option value="C430">C430</option>
                                            <option value="316L">316L</option>
                                        </optgroup>                                        
                                    </select>
                                    <div id="error_txt_calidad" class="error_msg text-danger d-none"> </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="txt_peso">Peso (kg):</label>
                                    <input id="txt_peso" type="text" placeholder="Ejemplo: 0.5 / 1.0 / 2.0" onkeypress="return isNumberKey(event)" class="form-control" required>
                                    <div id="error_txt_peso" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="txt_acabado">Acabado: </label>
                                    <select id="txt_acabado" name="txt_acabado" class="form-control" required>
                                        <option value="">Selecciona</option>
                                        <option value="BRILLO">BRILLO</option>
                                        <option value="SATINADO">SATINADO</option>
                                        <option value="MATE">MATE</option>
                                        <option value="INDUSTRIAL">INDUSTRIAL</option>                                        
                                    </select>
                                    <div id="error_txt_acabado" class="error_msg text-danger d-none"> </div>
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
    if(e.which==27) { close_modal(); }
    const density = 7850;
    function calculateWeight() {
       
        const dimensions = $("#txt_dimension").val();
        const thickness = parseFloat($("#txt_espesor").val());

        if (!dimensions || isNaN(thickness)) {
            $("#txt_peso").val(""); 
            return;
        }
    
        const [length, width] = dimensions.split("x").map(dim => parseFloat(dim) / 1000); // Convertir de mm a metros
        const volume = length * width * (thickness / 1000); // Convertir espesor de mm a metros
        const weight = volume * density;
        
        $("#txt_peso").val(weight.toFixed(2)); 
    }

    $("#txt_dimension").change(calculateWeight);
    $("#txt_espesor").on("input change", function () {
        calculateWeight(); 
    });
    
});


$(function () { 
    $("#menu_administracion").addClass("active open");
    $("#submenu_prod_insumos").addClass("active"); 
     

    var table = $('#tabla').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('insumos.list') }}",
        columns: [
            {data: 'id', name: 'id', className:'text-center',searchable: false},
            {data: 'desc', name: 'desc'},
            {data: 'abrev', name: 'abrev'},
            {data: 'calidad', name: 'calidad',className:'text-center',searchable: false},
            {data: 'dimension', name: 'dimension',className:'text-center',searchable: false},  
            {data: 'espesor', name: 'espesor',className:'text-center',searchable: false},
            {data: 'acabado', name: 'acabado',className:'text-center',searchable: false}, 
            {data: 'peso', name: 'peso',className:'text-center', searchable: false},  
            {data: 'fch', name: 'fch',className:'text-center', searchable: false},             
            {data: 'action', name: 'action', searchable: false},
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
        lengthMenu: [ [50, 100, -1], [50, 100, "All"] ],
        language: espanol,
        ordering: false,
    });
    
});

function guardar(){
    $('.error_msg').addClass('d-none').text('');
    MsgDlgLoadAjaxForm("form");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },        
        url: 'insumos',
        type: 'POST', 
        data:{
            "_token": "{{ csrf_token() }}",
            id              : $("#txt_id").val(),
            descripcion     : ($("#txt_descripcion").val()).toUpperCase(),
            abreviacion     : ($("#txt_abreviacion").val()).toUpperCase(),
            dimension       : $("#txt_dimension").val(),
            espesor         : $("#txt_espesor").val(),
            calidad         : $("#txt_calidad").val(),
            peso            : $("#txt_peso").val(),
            acabado         : $("#txt_acabado").val()
        },         
    }).done(function(data){
        setTimeout(function(){    MsgDlgLoadAjaxFinish("form"); }, 500);      
        
        $('#tabla').DataTable().ajax.url("{!! route('insumos.list') !!}").load();
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
function getdata(id){ 
    $.ajax({
        url: 'insumos/'+id+'/edit',
        type: 'GET',        
    }).done(function(data){
        $("#txt_id").val(data.id);
        $("#txt_descripcion").val(data.desc);
        $("#txt_abreviacion").val(data.abrev);
        $("#txt_dimension").val(data.dimension),
        $("#txt_espesor").val(data.espesor),
        $("#txt_calidad").val(data.calidad)
        $("#txt_peso").val(data.peso);
        $("#txt_acabado").val(data.acabado)
        
    }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
        var errors = error.responseJSON;                        
        if (error.status === 500) {
            maquinge.notificaciones(errors.msg, 'Sistema', 'error');
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
                url: 'insumos/'+id,
                type: 'DELETE', 
                data:{
                    "_token": "{{ csrf_token() }}",
                    id      : id,           
                },         
            }).done(function(data){
                maquinge.notificaciones(data.msg, 'Sistema', 'success');
                $('#tabla').DataTable().ajax.url("{!! route('insumos.list') !!}").load();
            }).fail(function(jqXHR, textStatus, errorThrown) {
                var errors = jqXHR.responseJSON; 
                if (jqXHR.status === 500) {
                    // Aquí se muestra el mensaje de error específico de la base de datos
                    var errorMessage = (errors.message && errors.message.length > 80) ? errors.message.substring(0, 80) + '...' : errors.message || "Error interno del servidor.";
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
