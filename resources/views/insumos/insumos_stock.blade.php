@extends('layouts.app')
@section('titulo') Stock Insumos @endsection

@section('contenido')

<div class="row">
    <div class="col-md-12">
        <div id="panel-4" class="panel">
            <div class="panel-hdr">
                <h2> STOCK DE PRODUCTOS / INSUMOS</h2>                
                <div class="panel-toolbar">
                    <button class="btn btn-panel waves-effect waves-themed" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Minimizar"></button>
                    <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Maximizar"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="form-row ml-3 mr-3">
                       
                        <div class="col-md-2">                           
                            <div class="form-group row">
                                <label class="col-form-label col-12 col-lg-2 form-label text-lg-right mr-2">Fecha:</label>
                                <div class="col-12 col-lg-9">
                                    <input style="width: 100px" type="text" id="fchselect" class="form-control form-control-sm" value="@php echo date('d-m-Y'); @endphp"/>
                                </div>
                            </div>
                        </div>                    
                        <div class="col-md-3">
                            <div class="form-group row">
                                <label class="col-form-label col-12 col-lg-5 form-label text-lg-right">Almacen:</label>
                                <div class="col-12 col-lg-6">
                                    <select id="select_almacen" class="form-control text-uppercase" required>
                                        <option value="0">Todos</option>
                                        <option value="1">RIO_SECO</option>
                                        <option value="2">ZOLEZZI</option>                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">                           
                            <button onclick="actualizar_tabla();" class="btn btn-success btn-block btn-sm">Actualizar</button> 
                        </div>
                        <div class="col-md-6 text-right">                           
                            <button onclick="guardar_historial_stock();" type="button" class="btn btn-info btn-sm waves-effect waves-themed"><i class="fas fa-history"></i> &nbsp;GUARDAR HISTORIAL</button> 
                        </div>                        
                                             
                    </div>
                    <div class="tab-content p-3">
                        <div class="tab-pane fade show active" id="tab_justified-1" role="tabpanel">
                            <div class="row">
                                <div class="col-xl-12 col-md-12">                                                                                                                    
                                    <div class="table-responsive">
                                        <table id="tabla" class="table table-bordered table-hover table-striped w-100">
                                            <thead class="bg-primary-600">
                                                <tr>
                                                    <th>id</th>
                                                    <th>Descripción Producto / Insumo</th>
                                                    <th>Almacen</th>
                                                    <th>Ingresos</th>                                                    
                                                    <th>Salidas</th>
                                                    <th>Stock</th>
                                                    <th>P.Venta</th>
                                                    <th>Fecha</th>                                                                                 
                                                </tr>
                                            </thead>                    
                                        </table>                                             
                                    </div>                                          
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection

@push('scripts')

<script>


$(function () { 
    $("#menu_almacenes").addClass("active open");    
    $("#submenu_alm_stock").addClass("active");

    $('#fchselect').datepicker({
                    todayHighlight: true,
                    orientation: "bottom left",
                    templates: controls,
                    format: 'dd-mm-yyyy'
                });  

    var table = $('#tabla').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('insumos.stock.list') }}?id_almacen="+$("#select_almacen").val()+"&fch="+$("#fchselect").val(),        
        columns: [
            {data: 'id', name: 'id', visible: false, searchable: false},
            {data: 'insumo', name: 'insumo',width: '35%'},
            {data: 'almacen', name: 'almacen',className:'text-center',searchable: false},  
            {data: 'ingresos', name: 'ingresos',className:'text-center',searchable: false}, 
            {data: 'salidas', name: 'salidas',className:'text-center',searchable: false}, 
            {data: 'stock', name: 'stock',className:'text-center',searchable: false},
            {data: 'pre_venta', name: 'pre_venta',className:'text-right',searchable: false}, 
            {data: 'fch', name: 'fch',className:'text-center',searchable: false}           
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
        lengthMenu: [ [50, 100, 200], [50, 100, 200] ],
        language: espanol,
        ordering: false,
    });

    
    $('#select_almacen,#fchselect').on('change', function () {
        actualizar_tabla();
    });
    
});


function guardar_historial_stock(){

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },        
        url: '{{ route("llenar.historial.stock") }}',
        type: 'POST', 
        data:{
            "_token": "{{ csrf_token() }}",                    
        },         
    }).done(function(data){
        maquinge.notificaciones(data.msg, 'Sistema', 'success');

    }).fail(function(jqXHR, textStatus, errorThrown) {
        var errors = jqXHR.responseJSON; 
        if (jqXHR.status === 500) {         
            var errorMessage = (errors.message && errors.message.length > 80) ? errors.message.substring(0, 80) + '...' : errors.message || "Error interno del servidor.";
            maquinge.notificaciones(errorMessage, 'Sistema', 'error');
        } else if (jqXHR.status === 422) {           
            var validationErrors = errors.errors;
            var errorList = '';
            for (var key in validationErrors) {
                if (validationErrors.hasOwnProperty(key)) {
                    errorList += validationErrors[key].join(', ') + '<br>';
                }
            }
            maquinge.notificaciones(errorList || "Ocurrió un error inesperado.", 'Sistema', 'error');
        } else {
          
            maquinge.notificaciones("Ocurrió un error inesperado: " + errorThrown, 'Sistema', 'error');
        }
    });


}


function actualizar_tabla(){
    
    $('#tabla').DataTable().ajax.url("{{ route('insumos.stock.list') }}?id_almacen="+$("#select_almacen").val()+"&fch="+$("#fchselect").val()).load();
}


</script>
@endpush