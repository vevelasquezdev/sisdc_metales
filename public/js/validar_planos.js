$('#file').on('change', function() {    
    $('#alert').show();
    var numb = $(this)[0].files[0].size/1024/1024; //count file size
    var resultid = $(this).val().split(".");
    var gettypeup  = resultid [resultid.length-1]; // take file type uploaded file
    var filetype = $(this).attr('data-file_types'); // take allowed files from input
    var allowedfiles = filetype.replace(/\|/g,', '); // string allowed file
    var filesize = 3; //2MB
    var onlist = $(this).attr('data-file_types').indexOf(gettypeup) > -1;
    var checkinputfile = $(this).attr('type');
    numb = numb.toFixed(2);

    if (onlist && numb <= filesize) {
        $('#alert').html('El Plano está listo para subir.').removeAttr('class').addClass('xd2'); //file OK
    }else{
        if(numb >= filesize && onlist){
            $(this).val(''); //remove uploaded file
            $('#alert').html('El archivo agregado es demasiado grande \(' + numb + ' MB\) - Tamaño máximo de archivo '+ filesize +' MB').removeAttr('class').addClass('xd'); //alert that file is too big, but type file is ok
        } else if(numb < filesize && !onlist) {
            $(this).val(''); //remove uploaded file
            $('#alert').html('Se ha agregado un formato de archivo no permitido \('+ gettypeup +') - formato permitido: ' + allowedfiles).removeAttr('class').addClass('xd'); //wrong type file
        } else if(!onlist) {
            $(this).val(''); //remove uploaded file
            $('#alert').html('Se ha agregado un formato de archivo no permitido \('+ gettypeup +') - formato permitido: ' + allowedfiles).removeAttr('class').addClass('xd'); //wrong type file
        }
    }
});

$('#file2').on('change', function() {    
    $('#alert2').show();
    var numb = $(this)[0].files[0].size/1024/1024; //count file size
    var resultid = $(this).val().split(".");
    var gettypeup  = resultid [resultid.length-1]; // take file type uploaded file
    var filetype = $(this).attr('data-file_types'); // take allowed files from input
    var allowedfiles = filetype.replace(/\|/g,', '); // string allowed file
    var filesize = 3; //2MB
    var onlist = $(this).attr('data-file_types').indexOf(gettypeup) > -1;
    var checkinputfile = $(this).attr('type');
    numb = numb.toFixed(2);

    if (onlist && numb <= filesize) {
        $('#alert2').html('El Plano está listo para subir.').removeAttr('class').addClass('xd2'); //file OK
    }else{
        if(numb >= filesize && onlist){
            $(this).val(''); //remove uploaded file
            $('#alert2').html('El archivo agregado es demasiado grande \(' + numb + ' MB\) - Tamaño máximo de archivo '+ filesize +' MB').removeAttr('class').addClass('xd'); //alert that file is too big, but type file is ok
        } else if(numb < filesize && !onlist) {
            $(this).val(''); //remove uploaded file
            $('#alert2').html('Se ha agregado un formato de archivo no permitido \('+ gettypeup +') - formato permitido: ' + allowedfiles).removeAttr('class').addClass('xd'); //wrong type file
        } else if(!onlist) {
            $(this).val(''); //remove uploaded file
            $('#alert2').html('Se ha agregado un formato de archivo no permitido \('+ gettypeup +') - formato permitido: ' + allowedfiles).removeAttr('class').addClass('xd'); //wrong type file
        }
    }
});

$('#file3').on('change', function() {    
    $('#alert3').show();
    var numb = $(this)[0].files[0].size/1024/1024; //count file size
    var resultid = $(this).val().split(".");
    var gettypeup  = resultid [resultid.length-1]; // take file type uploaded file
    var filetype = $(this).attr('data-file_types'); // take allowed files from input
    var allowedfiles = filetype.replace(/\|/g,', '); // string allowed file
    var filesize = 3; //2MB
    var onlist = $(this).attr('data-file_types').indexOf(gettypeup) > -1;
    var checkinputfile = $(this).attr('type');
    numb = numb.toFixed(2);

    if (onlist && numb <= filesize) {
        $('#alert3').html('El Plano está listo para subir.').removeAttr('class').addClass('xd2'); //file OK
    }else{
        if(numb >= filesize && onlist){
            $(this).val(''); //remove uploaded file
            $('#alert3').html('El archivo agregado es demasiado grande \(' + numb + ' MB\) - Tamaño máximo de archivo '+ filesize +' MB').removeAttr('class').addClass('xd'); //alert that file is too big, but type file is ok
        } else if(numb < filesize && !onlist) {
            $(this).val(''); //remove uploaded file
            $('#alert3').html('Se ha agregado un formato de archivo no permitido \('+ gettypeup +') - formato permitido: ' + allowedfiles).removeAttr('class').addClass('xd'); //wrong type file
        } else if(!onlist) {
            $(this).val(''); //remove uploaded file
            $('#alert3').html('Se ha agregado un formato de archivo no permitido \('+ gettypeup +') - formato permitido: ' + allowedfiles).removeAttr('class').addClass('xd'); //wrong type file
        }
    }
});

$('#file4').on('change', function() {    
    $('#alert4').show();
    var numb = $(this)[0].files[0].size/1024/1024; //count file size
    var resultid = $(this).val().split(".");
    var gettypeup  = resultid [resultid.length-1]; // take file type uploaded file
    var filetype = $(this).attr('data-file_types'); // take allowed files from input
    var allowedfiles = filetype.replace(/\|/g,', '); // string allowed file
    var filesize = 3; //2MB
    var onlist = $(this).attr('data-file_types').indexOf(gettypeup) > -1;
    var checkinputfile = $(this).attr('type');
    numb = numb.toFixed(2);

    if (onlist && numb <= filesize) {
        $('#alert4').html('El Plano está listo para subir.').removeAttr('class').addClass('xd2'); //file OK
    }else{
        if(numb >= filesize && onlist){
            $(this).val(''); //remove uploaded file
            $('#alert4').html('El archivo agregado es demasiado grande \(' + numb + ' MB\) - Tamaño máximo de archivo '+ filesize +' MB').removeAttr('class').addClass('xd'); //alert that file is too big, but type file is ok
        } else if(numb < filesize && !onlist) {
            $(this).val(''); //remove uploaded file
            $('#alert4').html('Se ha agregado un formato de archivo no permitido \('+ gettypeup +') - formato permitido: ' + allowedfiles).removeAttr('class').addClass('xd'); //wrong type file
        } else if(!onlist) {
            $(this).val(''); //remove uploaded file
            $('#alert4').html('Se ha agregado un formato de archivo no permitido \('+ gettypeup +') - formato permitido: ' + allowedfiles).removeAttr('class').addClass('xd'); //wrong type file
        }
    }
});

$('#file5').on('change', function() {    
    $('#alert5').show();
    var numb = $(this)[0].files[0].size/1024/1024; //count file size
    var resultid = $(this).val().split(".");
    var gettypeup  = resultid [resultid.length-1]; // take file type uploaded file
    var filetype = $(this).attr('data-file_types'); // take allowed files from input
    var allowedfiles = filetype.replace(/\|/g,', '); // string allowed file
    var filesize = 3; //2MB
    var onlist = $(this).attr('data-file_types').indexOf(gettypeup) > -1;
    var checkinputfile = $(this).attr('type');
    numb = numb.toFixed(2);

    if (onlist && numb <= filesize) {
        $('#alert5').html('El Plano está listo para subir.').removeAttr('class').addClass('xd2'); //file OK
    }else{
        if(numb >= filesize && onlist){
            $(this).val(''); //remove uploaded file
            $('#alert5').html('El archivo agregado es demasiado grande \(' + numb + ' MB\) - Tamaño máximo de archivo '+ filesize +' MB').removeAttr('class').addClass('xd'); //alert that file is too big, but type file is ok
        } else if(numb < filesize && !onlist) {
            $(this).val(''); //remove uploaded file
            $('#alert5').html('Se ha agregado un formato de archivo no permitido \('+ gettypeup +') - formato permitido: ' + allowedfiles).removeAttr('class').addClass('xd'); //wrong type file
        } else if(!onlist) {
            $(this).val(''); //remove uploaded file
            $('#alert5').html('Se ha agregado un formato de archivo no permitido \('+ gettypeup +') - formato permitido: ' + allowedfiles).removeAttr('class').addClass('xd'); //wrong type file
        }
    }
});

function open_modal_ver_archivos(id_orden){
    $("#DldModalVerArchivos").modal('show');
    $.ajax({ 
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'ver_archivos_subidos/'+id_orden,
        type: 'GET'                    
    }).done(function (data) {
        
        if(data.ruta_url=="" || data.ruta_url==null){                
            $('#alertArchivo1').html('Plano: '+data.ruta_url).removeAttr('class').addClass('xd');           
        }else{
            $('#alertArchivo1').html('Plano: '+data.ruta_url).removeAttr('class').addClass('xd2');           
            $('#ver_archivo_11').attr('onClick', 'VerPLanoPdf("'+data.ruta_url+'");');           
        }

        if(data.ruta_url2=="" || data.ruta_url2==null){                
            $('#alertArchivo2').html('Plano: '+data.ruta_url2).removeAttr('class').addClass('xd');
        }else{
            $('#alertArchivo2').html('Plano: '+data.ruta_url2).removeAttr('class').addClass('xd2');         
            $('#ver_archivo_22').attr('onClick', 'VerPLanoPdf("'+data.ruta_url2+'");');
        }

        if(data.ruta_url3=="" || data.ruta_url3==null){                
            $('#alertArchivo3').html('Plano: '+data.ruta_url3).removeAttr('class').addClass('xd');
        }else{
            $('#alertArchivo3').html('Plano: '+data.ruta_url3).removeAttr('class').addClass('xd2');         
            $('#ver_archivo_33').attr('onClick', 'VerPLanoPdf("'+data.ruta_url3+'");');
        }

        if(data.ruta_url4=="" || data.ruta_url4==null){                
            $('#alertArchivo4').html('Plano: '+data.ruta_url4).removeAttr('class').addClass('xd');
        }else{
            $('#alertArchivo4').html('Plano: '+data.ruta_url4).removeAttr('class').addClass('xd2');         
            $('#ver_archivo_44').attr('onClick', 'VerPLanoPdf("'+data.ruta_url4+'");');
        }

        if(data.ruta_url5=="" || data.ruta_url5==null){                
            $('#alertArchivo5').html('Plano: '+data.ruta_url5).removeAttr('class').addClass('xd');
        }else{
            $('#alertArchivo5').html('Plano: '+data.ruta_url5).removeAttr('class').addClass('xd2');         
            $('#ver_archivo_55').attr('onClick', 'VerPLanoPdf("'+data.ruta_url5+'");');
        }
        
    }).fail(function (error, jqXHR, textStatus,errorThrown) {
        var errors = error.responseJSON;                        
        if (errors.status === 500) {
            maquinge.notificaciones(error.msg, 'Sistema', 'error');
        }                                       
    }); 
}

function close_modal_ver_archivos(){
    
    $('#ver_archivo_11,#ver_archivo_22,#ver_archivo_33,#ver_archivo_44,#ver_archivo_55').attr('onClick', '');
    $("#DldModalVerArchivos").modal('hide');
}


function ver_archivos_cotizacion(id_orden){
    $("#DldModalVerArchivos").modal('show');
    $.ajax({ 
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'ver_archivos_cotizacion/'+id_orden,
        type: 'GET'                    
    }).done(function (data) {
        
        if(data.ruta_url=="" || data.ruta_url==null){                
            $('#alertArchivo1').html('Plano: '+data.ruta_url).removeAttr('class').addClass('xd');           
        }else{
            $('#alertArchivo1').html('Plano: '+data.ruta_url).removeAttr('class').addClass('xd2');           
            $('#ver_archivo_11').attr('onClick', 'VerPLanoPdf("'+data.ruta_url+'");');           
        }
        
    }).fail(function (error, jqXHR, textStatus,errorThrown) {
        var errors = error.responseJSON;                        
        if (errors.status === 500) {
            maquinge.notificaciones(error.msg, 'Sistema', 'error');
        }                                       
    }); 
}


// material editar///////////////////////////
function editar_precio_mat(id_orden_det){
    $("#DldModalEditMaterial").modal('show');
    $.ajax({         
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'consumomaterial/'+id_orden_det+'/edit',
        type: 'GET',
        data:{  
            "_token"        : "{{ csrf_token() }}"
        },
        success: function(data){
            $("#DlgModal_cant_mat2").val(data.cant);
            $("#DlgModal_largo2").val(data.largo_cm);
            $("#DlgModal_ancho2").val(data.ancho_cm);
            $("#DlgModal_espesor2").val(data.espesor_mm);
            $("#DlgModal_id_mat2").val(data.id_tipo_mat);
            $("#DlgModal_txt_unidad_mat2").text("S/."+data.precio_unid);
            $("#DlgModal_txt_ttotal_mat2").text("S/."+(data.precio_unid*data.cant*1.00).toFixed(2));   
            $("#edit_material_id_orden_detalle").val(id_orden_det);   
        },
        error : function(xhr, status) {
            console.log('Error al agregar material...');
        }
    });
}

function editar_precio_mat_cotizacion(id_orden_det){
    $("#DldModalEditMaterial").modal('show');
    $.ajax({         
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'consumomaterial-cotizacion/'+id_orden_det+'/edit',
        type: 'GET',
        data:{  
            "_token"        : "{{ csrf_token() }}"
        },
        success: function(data){
            $("#DlgModal_cant_mat2").val(data.cant);
            $("#DlgModal_largo2").val(data.largo_cm);
            $("#DlgModal_ancho2").val(data.ancho_cm);
            $("#DlgModal_espesor2").val(data.espesor_mm);
            $("#DlgModal_id_mat2").val(data.id_tipo_mat);
            $("#DlgModal_txt_unidad_mat2").text("S/."+data.precio_unid);
            $("#DlgModal_txt_ttotal_mat2").text("S/."+(data.precio_unid*data.cant*1.00).toFixed(2));   
            $("#edit_material_id_orden_detalle").val(id_orden_det);   
        },
        error : function(xhr, status) {
            console.log('Error al agregar material...');
        }
    });
}

function close_modal_edit_material(){
    $("#dlg_form_edit_material")[0].reset();
    $("#DldModalEditMaterial").modal('hide');
    $('#DldModalEditMaterial').on('hidden.bs.modal', function () {  
        if($('.modal.in')){  $('body').addClass('modal-open');}
    });
}

function editar_cal_costo_auto(){
    var mmm= $('select[name="DlgModal_id_mat2"] option:selected').text();
    var mmm2 = mmm.split('--',2);
    var precio = parseFloat((mmm2[1]).slice(4));
    var factor_k=0.0008;
    var add = 1.0;
  
   
    $('.matcal2').each(function() {
        if (!isNaN($(this).val())) {            
            if (add < 0) { add = add * -1;}
            if (add == 0) { add = 1;}
            add = add * parseFloat($(this).val());
            valor1 = add;
            // console.log("val: "+add);        
        }
    });
    
    pre_unid=valor1*factor_k*precio;
    p_unid=Math.ceil(pre_unid)*1.00;
   
    cant=parseFloat($("#DlgModal_cant_mat2").val());
    precio_total=p_unid*cant;
    // console.log("precio: "+precio);
    
    $("#DlgModal_txt_unidad_mat2").text("S/."+p_unid.toFixed(2));
    $("#DlgModal_txt_ttotal_mat2").text("S/."+precio_total.toFixed(2));
}

function guardar_edit_material(){
    id_detalle=$("#edit_material_id_orden_detalle").val();
    var unid_mat2 = $("#DlgModal_txt_unidad_mat2").text();
    var unid_mat22 = parseFloat(unid_mat2.replace("S/.", ""));
    $.ajax({
        url: 'orden-detalle/'+id_detalle,
        type: 'PUT',
        data:{ 
            "_token": $('input[name=_token]').val(),
            cantidad : $("#DlgModal_cant_mat2").val(),
            precio: unid_mat22
        },
        success: function(data){
            $.ajax({
                url: 'consumomaterial/'+id_detalle,
                type: 'PUT',
                data:{  
                    "_token"        : $('input[name=_token]').val(),  
                    largo           : $("#DlgModal_largo2").val(),
                    ancho           : $("#DlgModal_ancho2").val(),
                    espesor         : $("#DlgModal_espesor2").val(),
                    id_tip_mat      : $("#DlgModal_id_mat2").val(),
                    id_orden_det    : id_detalle,         
                    cant            : $("#DlgModal_cant_mat2").val(),                   
                    precio_unid     : unid_mat22.toFixed(2)     
                },
                success: function(data2){
                    console.log(data2.msg);
                    actualizar_tabla_dinamica();
                    close_modal_edit_material();
                },
                error : function(xhr, status) {
                    console.log('Error al agregar material...');
                }
            }); 
        },
        error : function(xhr, status) {
            console.log('Error al agregar material...');
        }
    });
}





