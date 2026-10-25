@extends('layouts.app')
@section('titulo') Control de Servicios @endsection


@section('contenido')

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Control</i></span>&nbsp;&nbsp;de Servicios                    
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>                    
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row ml-0">
                        <div class="col-md-2 mb-3">
                            <input type='text'  id='search_fromdate' class="form-control form-control-sm" value="{{ date( "d-m-Y" ) }}" placeholder='desde...'>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input type='text'  id='search_todate' class="form-control form-control-sm" value="{{ date( "d-m-Y" ) }}" placeholder='hasta...'>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type='button' class="btn btn-sm btn-info" id="btn_search">Buscar por fechas</button>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group row">
                                <label for="search_limite" class="col-md-5 mt-2">Limite de registros:</label>
                                <div class="col-md-6">
                                  <select id="search_limite" onchange="actualizar_tabla();" class="form-control">
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="500">500</option>
                                    <option value="1000">1000</option>
                                    <option value="2000">2000</option>
                                  </select>
                                </div>
                              </div>
                        </div>                        
                    </div>
                                  
                    <div class="table-responsive">                                      
                        <table id="tabla" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>ID</th>                                    
                                    <th>Cod.Orden</th>
                                    <th>Fch_Buscar</th>                                    
                                    <th>Cant</th>
                                    <th>Descripción del Servicio</th>                                    
                                    <th>Comentario y/o observación</th>
                                    <th>Operario</th>
                                    <th>Estado</th>
                                    <th>Fecha Ent.</th> 
                                    <th>Fecha Ejec.</th>
                                    <th>Fecha Sal.</th>
                                    <th>T. Ref</th>
                                    <th>T. Efec.</th>                               
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

<div class="modal fade default-example-modal" id="DldModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="dlg_form">
                <div class="modal-header">
                    <h5 class="modal-title h4 text-center"><label id="txt_title"></label></h5>
                    <button onclick="close_modal_derivar();" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row pl-3 pr-3">                        
                        <div class="col-md-12 mb-3">
                            <input type="hidden" id="derivar_id_orden_detalle">
                            <input type="hidden" id="derivar_id_orden">

                            <label class="form-label" for="id_operador">Operario:<span class="text-danger">*</span></label>
                            <select id="id_operador" class="form-control jquery_field" required>
                                <option value="">Selecciona</option>
                                @foreach ($operarios as $operario)
                                    <option value="{{$operario->id}}">{{$operario->name}} - {{$operario->surname}}</option>
                                @endforeach
                            </select>
                            <div id="error_id_operador" class="error_msg text-danger d-none"> </div>
                        </div>                                
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="id_horas">Tiempo de Referencia:<span class="text-danger">*</span></label>
                            <select id="id_horas" class="form-control jquery_field" required>
                                <option value="">Selecciona</option>
                                @foreach ($horas as $hora)
                                    <option value="{{$hora->id}}">{{$hora->t_hrs}}</option>
                                @endforeach
                            </select>
                            <div id="error_id_horas" class="error_msg text-danger d-none"> </div>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="derivar();" type="button" class="btn btn-primary">Guardar</button>
                    <button onclick="close_modal_derivar();" type="button" class="btn btn-secondary">Cerrar</button>
                </div>
            </form>            
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>  

$(function () { 
    $("#menu_ctrl_servicios").addClass("active");
    $('#txt_fch_nac').datepicker({
                    todayHighlight: true,                    
                    templates: controls,
                    format: 'yyyy-mm-dd'
                }); 
    $('#search_fromdate').datepicker({ todayHighlight: true,orientation: "bottom left",templates: controls, format: 'dd-mm-yyyy'  });
    $('#search_todate').datepicker({ todayHighlight: true,orientation: "bottom left",templates: controls, format: 'dd-mm-yyyy'  });

    var table = $('#tabla').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        rowGroup: true,
        searching: true,
        ajax: {
            url:"{{ route('ctrl.servicios.list') }}",
            data: function(data){
                var from_date = convertirFecha($('#search_fromdate').val());
                var to_date = convertirFecha($('#search_todate').val());
                var limite = $('#search_limite').val();
                data.from_date = from_date;
                data.to_date = to_date;
                data.limite = limite;
            }
        },
        columns: [
            {data: 'id',visible: false, searchable: false},                                     
            {data: 'id_orden',visible: false},
            {data: 'fch', className:'text-center'},
            {data: 'cant', className:'text-center'},
            {data: 'descripcion'},           
            {data: 'comentario'},
            {data: 'operario'},
            {data: 'estado'},  
            {data: 'fch_ent'},
            {data: 'fch_hor_ejecucion'}, 
            {data: 'fch_sal'},
            {data: 'tiempo_ref'},
            {data: 't_efectivo'},  
            {data: 'action', className:'text-center',searchable: false},
        ],
        columnDefs: [{targets: 7,
            render: function ( data, type, row ) {
                if(data=="0"){
                    return "<span class='badge badge-danger badge-pill'>EN ESPERA</span>";                    
                }else if(data=="1"){
                    return "<span class='badge badge-info badge-pill'>EJECUTANDO</span>"; 
                }else{
                    return "<span class='badge badge-success badge-pill'>TERMINADO</span>"; 
                }                
            }
        }],
        dom: dom_buttons_table,
        buttons: [{
            extend: 'pdfHtml5',
            text: 'PDF',
            titleAttr: 'Generate PDF',
            className: 'btn-outline-danger btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9,10]
            },
            orientation: 'landscape',
        },{
            extend: 'excelHtml5',
            text: 'Excel',
            titleAttr: 'Generate Excel',
            className: 'btn-outline-success btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9,10]
            }
        }],
        order: [
            [1, 'asc']
        ],
        rowGroup:{
            dataSrc: 'id_orden'
        },
        lengthMenu: [ [15,30,50], [15,30,50] ],
        language: espanol,
        ordering: false,
        initComplete: function () {
            this.api().columns(7).every( function () {
                var column = this;
                var select = $("<select class='form-control' style='padding: 0px;font-size: 11px;height: 20px;'><option value=''>ESTADO</option></select>")
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column.search( val ? '^'+val+'$' : '', true, false ).draw();
                    } );
                select.append("<option value='0'>EN ESPERA</option>")
                select.append("<option value='1'>EJECUTANDO</option>")
                select.append("<option value='2'>TERMINADO</option>")
            } );
        }
    });

    $('#btn_search').click(function(){
        table.draw();
    });

    // setInterval( function() {       
    //     table.ajax.reload(null, false);       
    // },20000);
    
});

function enviarAlServicio(id_orden_detalle,id_orden,servicio){
    $("#txt_title").text('.: DERIVAR A '+servicio+' :.');
    OpenDlgModal();
    $("#derivar_id_orden_detalle").val(id_orden_detalle);
    $("#derivar_id_orden").val(id_orden);
}

function derivar(){
    id_orden_detalle=$("#derivar_id_orden_detalle").val();
    id_orden=$("#derivar_id_orden").val();

    if($("#id_operador").val()==""){
        $("#id_operador").focus(); 
        return maquinge.notificaciones("Selecciona el Operador.", 'Sistema', 'warning');        
    }
    if($("#id_horas").val()==""){
        $("#id_horas").focus(); 
        return maquinge.notificaciones("Selecciona el tiempo de referencia.", 'Sistema', 'warning');        
    }

    Swal.fire({
        title: "¿ Confirma derivar este trabajo...  ?",
        text: "Esta acción no se puede deshacer!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        target: document.getElementById('DldModal'),
    }).then(function(result){
        if (result.value){
            $.ajax({        
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                url: 'enviar-al-servicio/'+id_orden_detalle+'/'+id_orden,
                type: 'GET',
                data:{
                    "_token": "{{ csrf_token() }}",
                    id_operario: $("#id_operador").val(),
                    tiempo_ref: $('#id_horas option:selected').text(),
                    id_tiempo_ref: $("#id_horas").val()
                }                  
            }).done(function(data){        
                $('#tabla').DataTable().ajax.url("{!! route('ctrl.servicios.list') !!}").load();
                close_modal_derivar();       
            }).fail( function(jqXHR, textStatus, errorThrown ) {
                maquinge.notificaciones(textStatus+" interno.", 'Sistema', 'error');        
                initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
            });
        }
    });

}

function actualizar_tabla(){
    
    $('#tabla').DataTable().ajax.url("{{ route('ctrl.servicios.list') }}").load();
}

function OpenDlgModal(){
    $("#DldModal").modal('show');
}

function close_modal_derivar(){
    $("#DldModal").modal('hide');
    $("#dlg_form")[0].reset();
}

// function enviarAlServicio(id_orden_detalle,id_orden,servicio){
//     var selectOptions = [];
//     var selectHoras = [];
    
//     $.ajax({        
//         headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
//         url: 'get-operarios',
//         type: 'GET',
//         data:{
//             "_token": "{{ csrf_token() }}",
//         }                  
//     }).done(function(data){ 
//         for (var i = 0; i < data.operarios.length; i++) {
//             selectOptions[data.operarios[i].id] = data.operarios[i].name + " " + data.operarios[i].surname;           
//         } 
//         for (var i = 0; i < data.horas.length; i++) {
//             selectHoras[data.horas[i].id] = data.horas[i].des_horas;           
//         }
//     }).fail( function(jqXHR, textStatus, errorThrown ) {
//         maquinge.notificaciones(textStatus+" interno.", 'Sistema', 'error');        
//         initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
//     }); 
   

//     setTimeout(function(){
//         Swal.fire({
//             title: 'Enviar al servicio de '+servicio,
//             input: 'select',
//             html: `<label>Tiempo estimado:</label><br>
//                     <select id="tiempo_ref" style="width:50%; height:33px; padding-left:7px"><option>Opciones</option></select> <br><br>
//                     Seleccione un operario encargado:`,
//             inputOptions: selectOptions,
//             inputPlaceholder: 'Requerido',
//             showCancelButton: true,  
//             onBeforeOpen: () => {
//                 const select =  document.getElementById('tiempo_ref');
  
//                 selectHoras.forEach(e=>{
//                     const option = document.createElement('option');
//                     option.text=selectHoras[1];
//                     option.value=selectHoras[1];
//                     select.appendChild(option);
//                 })
              
//             },
//             inputValidator: function (value) {
//                 return new Promise(function (resolve, reject) {
//                     if (value !== '') {
//                         resolve();                        
//                     } else {
//                         resolve('Debe seleccionar un Operario.');
//                     }
//                 });
//             }
//         }).then(function (result) {
//             if (result.value) {
//                 var t_r=$("#tiempo_ref").val();
//                 Swal.fire({
//                     title: 'Servicio a sido enviado al operario.',
//                     icon: 'success',
//                     html: 'Codigo Operario: ' + result.value+'<br>Tiempo referencia: '+t_r
//                 });
//                 $.ajax({        
//                     headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
//                     url: 'enviar-al-servicio/'+id_orden_detalle+'/'+id_orden,
//                     type: 'GET',
//                     data:{
//                         "_token": "{{ csrf_token() }}",
//                         id_operario: result.value,
//                         tiempo_ref: t_r
//                     }                  
//                 }).done(function(data){        
//                     $('#tabla').DataTable().ajax.url("{!! route('ctrl.servicios.list') !!}").load();        
//                 }).fail( function(jqXHR, textStatus, errorThrown ) {
//                     maquinge.notificaciones(textStatus+" interno.", 'Sistema', 'error');        
//                     initApp.playSound(asset+'smartadmin/dist/media/sound', 'voice_alert');
//                 });
//             }
//         });
//      }, 500);
// }



// html: `<label>Tiempo estimado:</label><br>
//                     <input id="tiempo_ref" type="text" placeholder="tiempo estimado" value="01h:00m" style="width:50%; height:33px; padding-left:7px"> (01h:30m) <br><br>
//                     Seleccione un operario encargado:`,


</script>
@endpush
