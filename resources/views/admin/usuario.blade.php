@extends('layouts.app')
@section('titulo') {{__('Users')}} @endsection


@section('contenido')

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Tabla</i></span>&nbsp;&nbsp;USUARIOS 
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
                            Agregar Nuevo Usuario
                        </button>
                    </div>
                                  
                    <div class="table-responsive">
                        <table id="tableUsers" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>ID</th>
                                    <th>FOTO</th>                                    
                                    <th>NOMBRES</th>
                                    <th>APELLIDOS</th>
                                    <th>FECHA NAC.</th>
                                    <th>TELEFONO</th>
                                    <th>EMAIL</th>                                   
                                    <th>ROL</th>
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
<div class="modal fade default-example-modal-right" id="DldModalUser" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-right">
        <div class="modal-content">
            <form id="dlguser_form" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title h4">FORMULARIO DE USUARIOS</h5>
                    <button onclick="close_modal();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">               
                    <div class="card mb-g">
                        <div class="card-body p-3">
                            <div class="form-row">
                                <input type="hidden" id="dlguser_txt_id">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="dlguser_txt_name">Nombres <span class="text-danger">*</span> </label>
                                    <input id="dlguser_txt_name" type="text" class="form-control jquery_field text-uppercase" required>
                                    <div id="error_dlguser_txt_name" class="error_msg text-danger d-none">sdsd </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="dlguser_txt_surname">Apellidos<span class="text-danger">*</span></label>
                                    <input id="dlguser_txt_surname" type="text" class="form-control jquery_field text-uppercase" required>
                                    <div id="error_dlguser_txt_surname" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="dlguser_txt_fch_nac">Fecha Nacimiento<span class="text-danger">*</span></label>
                                    <input id="dlguser_txt_fch_nac" type="text" class="form-control jquery_field" required>
                                    <div id="error_dlguser_txt_fch_nac" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="dlguser_txt_telefono">Telefono<span class="text-danger">*</span></label>
                                    <input id="dlguser_txt_telefono" type="text" class="form-control jquery_field" required>
                                    <div id="error_dlguser_txt_telefono" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="dlguser_txt_email">Email<span class="text-danger">*</span></label>
                                    <input id="dlguser_txt_email" type="text" class="form-control jquery_field" required>
                                    <div id="error_dlguser_txt_email" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="dlguser_txt_rol">Rol<span class="text-danger">*</span></label>
                                    <select id="dlguser_txt_rol" class="form-control jquery_field" required>
                                        <option value="">Selecciona</option>
                                        <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                                        <option value="OPERARIO">OPERARIO</option>
                                        <option value="CAJA">CAJA</option>
                                        <option value="ALMACEN">ALMACEN</option>                                                                     
                                    </select>                                    
                                    <div id="error_dlguser_txt_rol" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="dlguser_txt_password">Contraseña:<span class="text-danger">*</span></label>
                                    <input id="dlguser_txt_password" type="text" class="form-control jquery_field" required>
                                    <div id="error_dlguser_txt_password" class="error_msg text-danger d-none"> </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="dlguser_txt_image">Imagen:<span class="text-danger">*</span></label>
                                    <input type="file" id="dlguser_txt_image" accept="image/*">
                                    <div id="error_dlguser_txt_image" class="error_msg text-danger d-none"> </div>
                                    <img id="imagenPrevisualizacion" class="profile-image-md-user-my rounded-circle">
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="close_modal();" type="button" class="btn btn-secondary">Cerrar</button>
                    <button onclick="guardar_user();" type="button" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script> 

const $seleccionArchivos = document.querySelector("#dlguser_txt_image"), $imagenPrevisualizacion = document.querySelector("#imagenPrevisualizacion");

$seleccionArchivos.addEventListener("change", () => {  
  const archivos = $seleccionArchivos.files;
  
  if (!archivos || !archivos.length) {
    $imagenPrevisualizacion.src = "";
    return;
  }
 
  const primerArchivo = archivos[0];  
  const objectURL = URL.createObjectURL(primerArchivo);  
  $imagenPrevisualizacion.src = objectURL;
});


$(document).keyup(function(e){
    if(e.which==27) {
        close_modal();
    }
});
$(function () { 
    $("#menu_administracion").addClass("active open");
    $("#submenu_usuarios").addClass("active"); 
    $('#dlguser_txt_fch_nac').datepicker({
                    todayHighlight: true,
                    orientation: "bottom left",
                    templates: controls,
                    format: 'yyyy-mm-dd'
                });   
    var table = $('#tableUsers').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('vwuser') }}",
        columns: [
            {data: 'id', name: 'id', className: 'user_id', visible: false, searchable: false},
            {data: 'profile_photo_path', name: 'profile_photo_path', searchable: false, align:'center'},                           
            {data: 'name', name: 'name', className: 'user_name'},
            {data: 'surname', name: 'surname'},  
            {data: 'fch_nac', name: 'fch_nac'},  
            {data: 'telefono', name: 'telefono'},  
            {data: 'email', name: 'email'},  
             {data: 'rol', name: 'rol'},
            {data: 'action', name: 'action', searchable: false},
        ],
        dom: dom_buttons_table,
        buttons: [{
            extend: 'pdfHtml5',
            text: 'PDF',
            titleAttr: 'Generate PDF',
            className: 'btn-outline-danger btn-sm mr-1',
            exportOptions: {
                columns: [2,3,4,5,6]
            }
        },{
            extend: 'excelHtml5',
            text: 'Excel',
            titleAttr: 'Generate Excel',
            className: 'btn-outline-success btn-sm mr-1',
            exportOptions: {
                columns: [2,3,4,5,6]
            }
        }],
        lengthMenu: [ [50, 100, -1], [50, 100, "All"] ],
        language: espanol,
        ordering: false,
    });
    
});

function getdatauser(id){   
     
    $.ajax({        
        url: 'users/'+id+'/edit',
        type: 'GET',
        success: function (data) {
            $("#dlguser_txt_id").val(data.id);
            $("#dlguser_txt_name").val(data.name);
            $("#dlguser_txt_surname").val(data.surname);
            $("#dlguser_txt_fch_nac").val(data.fch_nac);
            $("#dlguser_txt_telefono").val(data.telefono);
            $("#dlguser_txt_email").val(data.email);
            $("#dlguser_txt_rol").val(data.rol);
            $('#imagenPrevisualizacion').attr('src','/storage/'+data.profile_photo_path);
        },
        error: function (error) {            
            var errors = error.responseJSON;                        
            if (error.status === 500) {
                maquinge.notificaciones(errors.msg, 'Maquingenieros', 'error');
            }
        }
    });
}

function guardar_user(){
    
    var id = $("#dlguser_txt_id").val();
    var name = $("#dlguser_txt_name").val();
    var surname = $("#dlguser_txt_surname").val();
    var fch_nac = $("#dlguser_txt_fch_nac").val();
    var telefono = $("#dlguser_txt_telefono").val();
    var email = $("#dlguser_txt_email").val();
    var rol = $("#dlguser_txt_rol").val();
    var password = $("#dlguser_txt_password").val();  
    
    
    var formData = new FormData();    
    formData.append('id', id);
    formData.append('name', name);
    formData.append('surname', surname);
    formData.append('fch_nac', fch_nac);
    formData.append('telefono', telefono);
    formData.append('email', email);
    formData.append('rol', rol);
    formData.append('password', password);    
    jQuery.each(jQuery('#dlguser_txt_image')[0].files, function(i, file) {
        formData.append('file[]', file);
    });
   
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
            'Accept': 'application/json'
        },
        url: 'users',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,        
        success: function (data) {            
            maquinge.notificaciones(data.msg, 'Sistema', 'success');
            $('#tableUsers').DataTable().ajax.url("{{ route('vwuser') }}").load();
            close_modal();
        },
        error: function (error) {            
            console.log(error); //            
            if (error.status === 422) {
                var errors = error.responseJSON.errors;
                $('.error_msg').addClass('d-none').text(''); // limpiar                
                $.each(errors, function(key, value){
                    $('#error_dlguser_txt_'+key).removeClass('d-none').text(value[0]); 
                });
            } else if (error.status === 500) {
                maquinge.notificaciones('Error del servidor', 'Sistema', 'error');
            }
        }
    });

 
    
}

function delete_user(id){
    
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
                url: 'users/'+id,
                type: 'DELETE',
                data:{
                    "_token": "{{ csrf_token() }}",
                    id      : id,           
                },        
            }).done(function(data){
                maquinge.notificaciones(data.msg, 'Sistema', 'success');
                $('#tableUsers').DataTable().ajax.url("{{ route('vwuser') }}").load();
                
            }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
                var errors = error.responseJSON;                        
                    if (error.status === 500) {
                        maquinge.notificaciones(textStatus, 'Sistema', 'error');
                    }               
            });
        }
    });
     
   
}

function close_modal(){  
    $("#DldModalUser").modal('hide');
    $("#dlguser_txt_id").val('');
    $('.error_msg').addClass('d-none').text('');  
    $("#dlguser_form")[0].reset();
    $("#dlguser_txt_image").val('');
    $('#imagenPrevisualizacion').attr('src','');
}




</script>
@endpush
