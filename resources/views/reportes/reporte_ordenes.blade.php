@extends('layouts.app')
@section('titulo') Reporte Ordenes @endsection

@section('contenido')

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Reporte</i></span>&nbsp;&nbsp;Ordenes
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>                    
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="form-row align-items-center">  
                        <div class="form-group mb-3 mr-2 ml-2 d-flex align-items-center">
                            <label for="search_fromdate" class="mr-2 mb-0">Fechas:</label>
                        </div>                     
                        <div class="form-group mb-3 mr-2 ml-2">
                            <input type="text" id="search_fromdate" class="form-control form-control-sm w-auto" 
                                   value="{{ date('d-m-Y') }}" 
                                   placeholder="Desde...">
                        </div>
                       
                        <div class="form-group mb-3 mr-2">
                            <input type="text" id="search_todate" class="form-control form-control-sm w-auto" 
                                   value="{{ date('d-m-Y') }}" placeholder="Hasta...">
                        </div>
                        <div class="form-group mb-3 mr-2 ml-5 d-flex align-items-center">
                            <label for="select_almacen" class="mr-2 mb-0">Almacén:</label>
                            <select id="select_almacen" class="form-control form-control-sm text-uppercase">
                                <option value="">Todos</option>
                                <option value="RIO_SECO">RIO_SECO</option>
                                <option value="ZOLEZZI">ZOLEZZI</option>
                            </select>
                        </div>
                        
                        <div class="form-group mb-3">
                            <button onclick="actualizar_reporte();" type="button" 
                                    class="btn btn-primary btn-sm btn-block waves-effect waves-themed">
                                Actualizar
                            </button>
                        </div>
                    </div>
                                  
                    <div class="table-responsive">
                        <table id="tableReport" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>                                    
                                    <th>Orden</th>
                                    <th>Cliente</th>
                                    <th>Local</th>                                    
                                    <th>Total</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Usuario</th>
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

@endsection

@push('scripts')

<script>  

$(function () { 
    $("#menu_reportes_caja").addClass("active open");
    $("#submenu_report_ordenes").addClass("active");  

    $('#search_fromdate,#search_todate').datepicker({
                    todayHighlight: true,
                    orientation: "bottom left",
                    templates: controls,
                    format: 'dd-mm-yyyy'
                }); 

    let fch = $("#fch").val();
    let alm = $("#select_almacen").val();

    var table = $('#tableReport').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "report-tabla-ordenes?alm="+alm,
            data: function(data){
                var from_date = $('#search_fromdate').val();
                var to_date = $('#search_todate').val();
                data.from_date = from_date;
                data.to_date = to_date;
            }
        },
        columns: [           
            {data: 'id_orden'},
            {data: 'cliente'},            
            {data: 'procedencia',searchable: false},
            {data: 'total',searchable: false},
            {data: 'servicio',searchable: false},
            {data: 'pagado',searchable: false},
            {data: 'usuario',searchable: false},
            {data: 'fecha_creacion',searchable: false},
        ],        
        dom: dom_buttons_table,
        buttons: [{
            extend: 'excelHtml5',
            text: 'Reporte Excel',
            titleAttr: 'Generate Excel',
            className: 'btn-outline-success btn-sm mr-1',
            filename: function() {               
                var from_date = $('#search_fromdate').val() || "inicio";
                var to_date = $('#search_todate').val() || "fin";
                return 'Reporte_Ordenes_' + from_date + '_a_' + to_date;
            },
            exportOptions: {
                columns: ':visible' // Exporta solo las visibles por defecto
            },
            
            customizeData: function(data) {
                var from_date = $('#search_fromdate').val();
                var to_date = $('#search_todate').val();
                var alm = $("#select_almacen").val();
                $.ajax({
                    url: "report-tabla-ordenes?alm="+alm,
                    method: "GET",
                    data: {
                        from_date: from_date,
                        to_date: to_date
                    },
                    dataType: "json",
                    async: false,
                    success: function(response) {                   
                        data.body = response.data.map(row => Object.values(row));
                        data.header = Object.keys(response.data[0]).map(header => header.toUpperCase());
                    }                   
                });
            }
        }],
        lengthMenu: [ [50, 100, -1], [50, 100, "All"] ],
        language: espanol,
        ordering: false,
    });

    $('#search_fromdate,#search_todate,#select_almacen').on('change', function () {
        actualizar_reporte();
    });
    
});

function actualizar_reporte(){  
    let alm = $("#select_almacen").val();
    $('#tableReport').DataTable().ajax.url("report-tabla-ordenes?alm="+alm).load();
}


</script>
@endpush