

function realizarPago(){
    id_orden = $("#DlgModal_codigo").val();
    if(id_orden==""){ return maquinge.notificaciones("No hay nada para pagar...", 'Sistema', 'warning'); } 


    if(user_rol=='ADMINISTRADOR' ||  user_rol=='CAJA'){ //modal show
        
                
       
        $("#DldModalPago_txt_descripcion").val("PAGO ORDEN "+$("#DlgModal_codigo").val());

        if($("#DlgModal_txt_raz_soc").val()){
            $("#DldModalPago_txt_razon_social").val($("#DlgModal_txt_raz_soc").val()); //llenar razon social del cliente
        }else{
            $("#DldModalPago_txt_razon_social").val($("#DlgModal_txt_name").val()+" "+$("#DlgModal_txt_surname").val());
        }

        $("#DldModalPago_txt_id_orden").val($("#DlgModal_codigo").val());
        
        $.ajax({        
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            url: 'get_saldo/'+$("#DlgModal_id_orden").val(),
            type: 'GET',
            data:{
                "_token": "{{ csrf_token() }}",                
            }                  
        }).done(function(data){   
            var monto_pagado=data.msg; 
            var monto_total=parseFloat($("#DlgModal_txt_ttotal").text()); 
            var saldo = monto_total.toFixed(2) - monto_pagado;
            if(saldo==0.00){
                return maquinge.notificaciones("Esta Orden no tiene saldos a pagar.", 'Sistema', 'info');
                return false;
            }else{
                $("#DldModalPago").modal('show');
            }
            $("#DldModalPago_txt_saldo").val(saldo.toFixed(2));        
        }).fail( function(jqXHR, textStatus, errorThrown ) {
            maquinge.notificaciones(textStatus+" interno en movimientos de caja.", 'Sistema', 'error');        
            initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
        });
        
        if(user_name=="CAJA1"){
            $("#DldModalPago_txt_caja").val(1);
            $("#DldModalPago_txt_caja").attr("disabled", true);
        }else if(user_name=="CAJA2"){
            $("#DldModalPago_txt_caja").val(2);
            $("#DldModalPago_txt_caja").attr("disabled", true);
        }else if(user_name=="CAJA3"){
            $("#DldModalPago_txt_caja").val(3);
            $("#DldModalPago_txt_caja").attr("disabled", true);
        }

                  
       
    }else{
        return maquinge.notificaciones("No tienes permiso para Pagar.", 'Sistema', 'warning');
    }
}


function forma_pago(id_forma_pago){   
    $("#fp_1, #fp_2, #fp_3, #fp_4, #fp_5, #fp_6, #fp_7, #fp_8, #fp_9, #fp_10").hide();
    $("#DldModalPago_txt_efectivo,#DldModalPago_txt_tarjeta,#DldModalPago_txt_yape,#DldModalPago_txt_transferencia,#DldModalPago_txt_credito,#DldModalPago_txt_cupon").val('');
    $("#DldModalPago_txt_efectivo2,#DldModalPago_txt_tarjeta2,#DldModalPago_txt_yape2,#DldModalPago_txt_transferencia2,#DldModalPago_txt_credito2").val('');
    $("#DldModalPago_txt_efectivo3,#DldModalPago_txt_efectivo4,#DldModalPago_txt_efectivo5").val('');
    switch (id_forma_pago) {
        case '1': $("#fp_1").show(); $("#DldModalPago_txt_efectivo").val($("#DlgOrdenPedido_txt_ttotal").text()); break;
        case '2': $("#fp_2").show(); $("#DldModalPago_txt_tarjeta").val($("#DlgOrdenPedido_txt_ttotal").text()); break;
        case '3': $("#fp_3").show(); $("#DldModalPago_txt_yape").val($("#DlgOrdenPedido_txt_ttotal").text()); break;
        case '4': $("#fp_4").show(); $("#DldModalPago_txt_transferencia").val($("#DlgOrdenPedido_txt_ttotal").text()); break;
        case '5': $("#fp_5").show(); $("#DldModalPago_txt_credito").val($("#DlgOrdenPedido_txt_ttotal").text()); break;
        case '6': $("#fp_6").show(); break;
        case '7': $("#fp_7").show(); break;
        case '8': $("#fp_8").show(); break;
        case '9': $("#fp_9").show(); break;
        case '10': $("#fp_10").show(); $("#DldModalPago_txt_cupon").val($("#DlgOrdenPedido_txt_ttotal").text()); break;
        default:  
            return false;
    }
}


function insert_pago_orden(){
    $('.error_msg_pago').addClass('d-none').text('');
    initApp.playSound(asset+'smartadmin/dist/media/sound', 'bigbox'); 
    Swal.fire({
        title: "¿ Esta seguro de realizar el pago ?",
        text: "Esta acción no se puede deshacer!",
        target: document.getElementById('DldModalPago'),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar"
    }).then(function(result){
        if (result.value){
            confirmar_insert();
        }
    });
}

function confirmar_insert(){
    //MsgDlgLoadAjaxForm("dlg_form_pago");
    var monto_a_pagar=0;
    var fp = $('#DldModalPago_txt_id_forma_pago').val();

    var formDataPago = new FormData($("#dlg_form_pago")[0]);
    
    formDataPago.append('_token', $('input[name=_token]').val());
    formDataPago.append('caja',$("#DldModalPago_txt_caja").val()); 
    formDataPago.append('razon_social',($("#DldModalPago_txt_razon_social").val()).toUpperCase());
    formDataPago.append('descripcion',($("#DldModalPago_txt_descripcion").val()).toUpperCase());
    formDataPago.append('id_forma_pago',$('#DldModalPago_txt_id_forma_pago').val());
    formDataPago.append('fch_emi',$('#DldModalPago_txt_fch_emi').val());
    formDataPago.append('id_orden',$('#DldModalPago_txt_id_orden').val());
    
    if(fp==1){
        formDataPago.append('efectivo',$('#DldModalPago_txt_efectivo').val());
        monto_a_pagar = parseFloat($('#DldModalPago_txt_efectivo').val()).toFixed(2);
    }else if(fp==2){
        formDataPago.append('tarjeta',$('#DldModalPago_txt_tarjeta').val());
        monto_a_pagar = parseFloat($('#DldModalPago_txt_tarjeta').val()).toFixed(2);
    }else if(fp==3){
        formDataPago.append('yape',$('#DldModalPago_txt_yape').val());
        monto_a_pagar = parseFloat($('#DldModalPago_txt_yape').val()).toFixed(2);
    }else if(fp==4){
        formDataPago.append('transferencia',$('#DldModalPago_txt_transferencia').val());
        monto_a_pagar = parseFloat($('#DldModalPago_txt_transferencia').val()).toFixed(2);
    }else if(fp==5){
        formDataPago.append('credito',$('#DldModalPago_txt_credito').val());
        monto_a_pagar = parseFloat($('#DldModalPago_txt_credito').val()).toFixed(2);
    }else if(fp==6){
        formDataPago.append('efectivo',$('#DldModalPago_txt_efectivo2').val());
        formDataPago.append('tarjeta',$('#DldModalPago_txt_tarjeta2').val());
        monto_a_pagar = parseFloat($('#DldModalPago_txt_efectivo2').val())+parseFloat($('#DldModalPago_txt_tarjeta2').val());
    }else if(fp==7){
        formDataPago.append('efectivo',$('#DldModalPago_txt_efectivo3').val());
        formDataPago.append('yape',$('#DldModalPago_txt_yape2').val()); 
        monto_a_pagar = parseFloat($('#DldModalPago_txt_efectivo3').val())+parseFloat($('#DldModalPago_txt_yape2').val());
    }else if(fp==8){  
        formDataPago.append('efectivo',$('#DldModalPago_txt_efectivo4').val());
        formDataPago.append('transferencia',$('#DldModalPago_txt_transferencia2').val());
        monto_a_pagar = parseFloat($('#DldModalPago_txt_efectivo4').val())+parseFloat($('#DldModalPago_txt_transferencia2').val());
    }else if(fp==9){
        formDataPago.append('efectivo',$('#DldModalPago_txt_efectivo5').val());
        formDataPago.append('credito',$('#DldModalPago_txt_credito2').val());
        monto_a_pagar = parseFloat($('#DldModalPago_txt_efectivo5').val())+parseFloat($('#DldModalPago_txt_credito2').val());    
    }

    var saldo = parseFloat($("#DldModalPago_txt_saldo").val()).toFixed(2);
    var montopago = parseFloat(monto_a_pagar).toFixed(2);

    console.log("saldo:"+saldo+"<br>monto_a_pagar:"+montopago);
    formDataPago.append('monto',montopago);
    formDataPago.append('saldo',saldo);

    // if(montopago>saldo){ return maquinge.notificaciones("Monto no puede exceder al saldo...", 'Sistema', 'warning'); }
    // else{ return 'adelante'}
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'movimientosctrl',
        type: 'POST',
        data: formDataPago,
        cache:false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        if(data.msg==0){
            MsgDlgLoadAjaxFinish("dlg_form_pago");
            maquinge.notificaciones("Monto no debe ser mayor al saldo...!", 'Sistema', 'warning');
        }else{
            setTimeout(function(){    
                MsgDlgLoadAjaxFinish("dlg_form_pago");
                close_pago();                 
                close_modal(); 
                $('#tabla').DataTable().ajax.url("orden-list").load();              
            }, 1000);
        }
        
          
    }).fail(function (data, jqXHR, textStatus) {
        var response = JSON.parse(data.responseText);            
        $.each( response.errors, function( key, value) {
            $('#error_DldModalPago_txt_' + key).removeClass('d-none').text(value);
        });
        
        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
        MsgDlgLoadAjaxFinish("dlg_form_pago");
    });
}

function close_pago(){
    $('.error_msg_pago').addClass('d-none').text('');  
    $("#DldModalPago_txt_caja").attr("disabled", false);  
    $("#DldModalPago").modal('hide');
    $('#DldModalPago').on('hidden.bs.modal', function () {  
        if($('.modal.in')){  $('body').addClass('modal-open');}
    });
    $("#dlg_form_pago")[0].reset();

    $("#fp_1, #fp_2, #fp_3, #fp_4, #fp_5, #fp_6, #fp_7, #fp_8, #fp_9, #fp_10").hide();
    $("#DldModalPago_txt_efectivo,#DldModalPago_txt_tarjeta,#DldModalPago_txt_yape,#DldModalPago_txt_transferencia,#DldModalPago_txt_credito,#DldModalPago_txt_cupon").val('');
}





// $.ajax({
//     headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
//     url: 'clientes',
//     type: 'post',
//     data: {},
//     cache:false,
//     contentType: false,
//     processData: false,
// }).done(function (data) {  
//     $("#DlgOrdenPedido_txt_id_cli").val(data.id_cli);      
//     maquinge.notificaciones(data.msg, 'OceanClub', 'success');        
// }).fail(function (data, jqXHR, textStatus) {
//     var response = JSON.parse(data.responseText);            
//     $.each( response.errors, function( key, value) {
//         $('#error_DlgOrdenPedido_txt_' + key).removeClass('d-none').text(value);
//     });
//     initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
// });