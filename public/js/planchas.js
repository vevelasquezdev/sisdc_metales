


    
let map = {};
let objects = [];
let id_almacen=0;
$('#txt_insumo').typeahead({
    
    source: function(query, process) {
        if($("#DlgModal_procedencia").val()=='RIO_SECO'){
            id_almacen=1;
        }else if($("#DlgModal_procedencia").val()=='ZOLEZZI'){
            id_almacen=2;
        }else{
            if($("#DlgModal_procedencia").val()==""){
                $("#DlgModal_procedencia").focus(); 
                return maquinge.notificaciones("Elija un Local...", 'Sistema', 'warning');        
            }
        }
        objects = [];
        map = {};       
        $.getJSON('insumos_autocompletar/' + query+ '/' + id_almacen, null, function(jsonData) {
            $.each(jsonData, function(i, object) {              
                map[object.label] = object;              
                objects.push(object.label + " -- (Stock: " + object.stock + ")");
            });
            process(objects);
        });
    },
    items: 15,
    minLength: 2,
    delay: 300,
    highlighter: function(item) {
        var regex = new RegExp('(' + this.query + ')', 'gi');
        return item.replace(regex, "<strong>$1</strong>");
    },
    updater: function(item) {      
        let label = item.split(" -- (Stock: ")[0];       
        $('#txt_id_insumo').val(map[label].id); 
        $('#txt_stock').val(map[label].stock);                
        $('#txt_precio').val(map[label].precio);  
                
        return map[label].label;
    }
});




function add_item_material_insumos(){
    $('.error_msg').addClass('d-none').text('');
    
    if($("#DlgModal_procedencia").val()==""){
        return maquinge.notificaciones("Elija un Local", 'Sistema', 'warning');        
    } 
    if($("#DlgModal_txt_id_cliente").val()==""){
        return maquinge.notificaciones("Elija un cliente...", 'Sistema', 'warning');        
    } 
    if($("#txt_id_insumo").val()==""){
        $("#txt_insumo").get(0)?.focus();
        return maquinge.notificaciones("Ingrese descripción del Producto y/o Insumo ...", 'Sistema', 'warning');        
    }   
    if($("#txt_cantidad").val()==""){
        $("#txt_cantidad").get(0)?.focus();
        return maquinge.notificaciones("Ingrese Cantidad del Producto y/o Insumo...", 'Sistema', 'warning');        
    }
    if($("#txt_precio").val()==""){
        $("#txt_precio").get(0)?.focus(); 
        return maquinge.notificaciones("Ingrese el Precio del Producto y/o Insumo...", 'Sistema', 'warning');        
    }  
    if($("#txt_stock").val()<=0){        
        return maquinge.notificaciones("Stock <= 0 <br>No puede elegir un producto con stock menor o igual a cero.", 'Sistema', 'warning'); 
    }

    if($("#DlgModal_procedencia").val()=='RIO_SECO'){
        id_almacen=1;
    }else if($("#DlgModal_procedencia").val()=='ZOLEZZI'){
        id_almacen=2;
    }

    MsgDlgLoadAjaxForm("form");   
    $.ajax({         
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
        url: 'orden-detalle/create',
        type: 'GET',
        data:{  
            "_token"        : "{{ csrf_token() }}",          
            id_orden        : $("#DlgModal_id_orden").val(),         
            cant            : $("#txt_cantidad").val(),
            id_servicio     : 41,//id_del servicio material dc
            precio_unid     : $("#txt_precio").val(),
            material        : 1      
        },
    }).done(function (data) { 
        console.log($('meta[name="csrf-token"]').attr('content'));
        $.ajax({                    
            url: 'ing_sal_insumos',
            type: 'POST', 
            data:{                         
                id_insumo       : $("#txt_id_insumo").val(),
                cantidad        : $("#txt_cantidad").val(),
                tipo            : 0,
                obs             : 'VENTA DE PLANCHA ORDEN: '+$("#DlgModal_id_orden").val(),                
                pre_venta       : $("#txt_precio").val(),            
                id_almacen      : id_almacen,
                pvc             : 1,
                origen_destino  : 'DESTINO: '+$("#DlgModal_txt_name").val()+" "+$("#DlgModal_txt_surname").val(),                
                id_cliente      : $("#DlgModal_txt_id_cliente").val(),
                movimiento      : 0,
                id_orden        : $("#DlgModal_id_orden").val(),
                id_orden_det    : data.id_detalle
            },         
        }).done(function(data2){           
            console.log(data2.msg);
        }).fail( function(error, jqXHR, textStatus, errorThrown ) { 
            console.log('Error al agregar material...');
        });  
            
        setTimeout(function(){
           
            $("#txt_insumo,#txt_stock,#txt_id_insumo,#txt_precio").val('');
            $("#txt_cantidad").val('1');
          
            MsgDlgLoadAjaxFinish("form");
         
        }, 300);
        
        actualizar_tabla_dinamica();
    }).fail(function (jqXHR, textStatus,errorThrown) {
        maquinge.notificaciones(textStatus, 'Sistema', 'error');     
        initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
        MsgDlgLoadAjaxFinish("form"); 
    });


}
