function OpenModalProveedor(){
    var box = $("#DldModalProveedor").modal('show');

    $('.modal').on('shown.bs.modal', function(e) {
        $('#provee_txt_razon_social').focus();
    })

    box.on('hidden.bs.modal', function (e) {
        if($('.modal.in')){  $('body').addClass('modal-open');}
    });
}

function guardar_proveedor(){
    $('.error_msg').addClass('d-none').text('');
    MsgDlgLoadAjaxForm("form_provee-1");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },        
        url: 'proveedores',
        type: 'POST', 
        data:{
            "_token":       $("#_token").val(),
            id              : $("#provee_txt_id").val(),
            razon_social    : ($("#provee_txt_razon_social").val()).toUpperCase(),
            telefono        : $("#provee_txt_telefono").val(),
            ruc             : $("#provee_txt_ruc").val(),
        },         
    }).done(function(data){
        setTimeout(function(){    MsgDlgLoadAjaxFinish("form_provee-1"); }, 500); 
        maquinge.notificaciones(data.msg, 'Sistema.', 'success'); 
        $('#tabla_buscar_proveedor').DataTable().ajax.url("proveedores-list").load();       
        close_modal_proveedor();
    }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
        var response = JSON.parse(error.responseText);   
        $.each( response.errors, function( key, value) {
            $('#error_provee_txt_' + key).removeClass('d-none').text(value);
        });

        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
        if (error.status === 500) {
            maquinge.notificaciones('Error interno comun&iacute;quese con el &aacute;rea de sistemas', 'Sistema', 'error');
        }        
        MsgDlgLoadAjaxFinish("form_provee-1");            
    });
}

function close_modal_proveedor(){  
    $("#DldModalProveedor").modal('hide');
    $("#provee_txt_id").val('');
    $('.error_msg').addClass('d-none').text('');  
    $("#form_proveedor")[0].reset();
}



// ---BUSCAR PROVEEDOR--------------------------------------------------

function Open_buscar_proveedor(){
    var box = $("#DldModalBuscarProveedor").modal('show');

    $('.modal').on('shown.bs.modal', function(e) {
        $('div.dataTables_filter input').focus();
    })

    box.on('hidden.bs.modal', function (e) {
        if($('.modal.in')){  $('body').addClass('modal-open');}
    });
}

function close_modal_buscar_proveedor(){
    $("#DldModalBuscarProveedor").modal('hide');
}

function select_buscar_proveedor(id_proveedor){    
    if(id_proveedor==0){
        id_proveedor = $('#tabla_buscar_proveedor').DataTable().rows( { selected: true } ).data()[0]['id'];
    }
    
    $.ajax({
        url: 'proveedores/'+id_proveedor+'/edit',
        type: 'GET',        
    }).done(function(data){
        $("#txt_id_provee").val(data.id);
        $("#txt_provee_desc").val(data.desc);
        $("#txt_provee_tel").val(data.telefono);
        $("#txt_provee_ruc").val(data.ruc);
        $("#txt_origen_destino").val('ORIGEN: '+data.desc);
        close_modal_buscar_proveedor();
    }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
        var errors = error.responseJSON;                        
        if (errors.status === 500) {
            maquinge.notificaciones(error.msg, 'Sistema', 'error');
        }       
    }); 
   
}