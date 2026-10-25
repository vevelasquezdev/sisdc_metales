
function OpenModalCliente(){
    var box = $("#DldModalCliente").modal('show');

    $('.modal').on('shown.bs.modal', function(e) {
        $('#clie_txt_nombres').focus();
    })

    box.on('hidden.bs.modal', function (e) {
        if($('.modal.in')){  $('body').addClass('modal-open');}
    });
}

$('#clie_txt_fch_nac').datepicker({
    todayHighlight: true,                    
    templates: controls,
    format: 'yyyy-mm-dd'
});

function guardar_cliente(){
    $('.error_msg').addClass('d-none').text('');
    MsgDlgLoadAjaxForm("form_cliente");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },        
        url: 'clientes',
        type: 'POST', 
        data:{
            "_token":       $("#_token").val(),
            id              : $("#clie_txt_id").val(),
            nombres         : ($("#clie_txt_nombres").val()).toUpperCase(),
            apellidos       : ($("#clie_txt_apellidos").val()).toUpperCase(),
            fch_nac         : $("#clie_txt_fch_nac").val(),
            dni             : $("#clie_txt_dni").val(),
            email           : $("#clie_txt_email").val(),
            ruc             : $("#clie_txt_ruc").val(),
            razon_social    : ($("#clie_txt_razon_social").val()).toUpperCase(),
            telefono        : $("#clie_txt_telefono").val(),
            direccion       : ($("#clie_txt_direccion").val()).toUpperCase()
        },         
    }).done(function(data){
        setTimeout(function(){    MsgDlgLoadAjaxFinish("form_cliente"); }, 500); 
        maquinge.notificaciones(data.msg, 'Sistema.', 'success');
        $('#tabla_buscar_cliente').DataTable().ajax.url("clientes-list").load();
        close_modal_cliente();
    }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
        var response = JSON.parse(error.responseText);   
        $.each( response.errors, function( key, value) {
            $('#error_clie_txt_' + key).removeClass('d-none').text(value);
        });

        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
        if (error.status === 500) {
            maquinge.notificaciones('Error interno comun&iacute;quese con el &aacute;rea de sistemas', 'Sistema', 'error');
        }        
        MsgDlgLoadAjaxFinish("form_cliente");            
    });
}

function close_modal_cliente(){  
    $("#DldModalCliente").modal('hide');
    $("#clie_txt_id").val('');
    $('.error_msg').addClass('d-none').text('');  
    $("#form_cliente")[0].reset();
}

// ---BUSCAR CLIENTE--------------------------------------------------
function Open_buscar_cliente(){
    var box = $("#DldModalBuscarCliente").modal('show');

    $('.modal').on('shown.bs.modal', function(e) {
        $('div.dataTables_filter input').focus();
    })
     
    // $('.dataTables_filter input').first().focus();
    
    box.on('hidden.bs.modal', function (e) {
        if($('.modal.in')){  $('body').addClass('modal-open');}
    });
}

function close_modal_buscar_cliente(){
    $("#DldModalBuscarCliente").modal('hide');
}

function select_buscar_cliente(id_cliente){
    
    if(id_cliente==0){
        id_cliente = $('#tabla_buscar_cliente').DataTable().rows( { selected: true } ).data()[0]['id'];
    }
    
    $.ajax({
        url: 'clientes/'+id_cliente+'/edit',
        type: 'GET',        
    }).done(function(data){
        $("#DlgModal_txt_id_cliente").val(data.id);
        $("#DlgModal_txt_name").val(data.name);
        $("#DlgModal_txt_surname").val(data.surname);
        $("#DlgModal_txt_ruc").val(data.ruc);
        $("#DlgModal_txt_email").val(data.email);
        $("#DlgModal_txt_raz_soc").val(data.raz_soc);
        $("#DlgModal_txt_telefono").val(data.telefono);
        $("#txt_origen_destino").val('DESTINO: '+data.name+' '+data.surname);
        close_modal_buscar_cliente();
    }).fail( function(error, jqXHR, textStatus, errorThrown ) {        
        var errors = error.responseJSON;                        
        if (errors.status === 500) {
            maquinge.notificaciones(error.msg, 'Sistema', 'error');
        }       
    }); 

   
   
}
