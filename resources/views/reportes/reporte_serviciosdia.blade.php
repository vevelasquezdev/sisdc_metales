@extends('layouts.app')
@section('titulo') Reporte Servicios por dia @endsection


@section('contenido')

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Reporte</i></span>&nbsp;&nbsp;Servicios por dia 
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>                    
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="form-row">
                        
                        <div class="col-md-2 mb-4">                           
                            <div class="form-group row">
                                <label class="col-form-label col-12 col-lg-2 form-label text-lg-right mr-2">Fecha:</label>
                                <div class="col-12 col-lg-9">
                                    <input style="width: 100px" type="text" id="fch" class="form-control form-control-sm" value="@php echo date('d-m-Y'); @endphp"/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-2 mb-3">                           
                            <button onclick="actualizar_reporte();" type="button" class="btn btn-primary btn-sm btn-block waves-effect waves-themed">ACTUALIZAR</button> 
                        </div> 
                                              
                    </div>
                                  
                    <div class="table-responsive">
                        <table id="tableReport" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>Codigo Servicio</th>                                    
                                    <th>Descripción del Servicio</th>
                                    <th>Fecha</th>               
                                    <th>Cod. Orden</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
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
    $("#submenu_report_servicios").addClass("active");  

    $('#fch').datepicker({
                    todayHighlight: true,
                    orientation: "bottom left",
                    templates: controls,
                    format: 'dd-mm-yyyy'
                });  

    var table = $('#tableReport').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "report-tabla-servicios-dia?fch="+$("#fch").val(),       
        columns: [
            {data: 'id_servicio'},         
            {data: 'descripcion'},
            {data: 'fch'},
            {data: 'codigo_orden'},
            {data: 'pre_tot'},
            {data: 'estado'},         
        ],        
        dom: dom_buttons_table,
        buttons: [{
            text: 'Reporte PDF',
            className: 'btn-outline-danger btn-sm mr-1',
            action: function ( e, dt, node, config ) {
                reporte_pdf();
            }
        }
        ,{
            extend: 'excelHtml5',
            text: 'Excel',
            titleAttr: 'Generate Excel',
            className: 'btn-outline-success btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3,4]
            }
        }],
        lengthMenu: [ [50, 100, -1], [50, 100, "All"] ],
        language: espanol,
        ordering: false,
    });
    
});

function actualizar_reporte(){

    $('#tableReport').DataTable().ajax.url("report-tabla-servicios-dia?fch="+$("#fch").val()).load();

}

function reporte_pdf(){
    window.open('report-pdf-servicios-dia?fch='+$("#fch").val());
}


</script>
@endpush
