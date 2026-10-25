@extends('layouts.app')
@section('titulo') Control de Operarios @endsection


@section('contenido')

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Control</i></span>&nbsp;&nbsp;de Operarios
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>                    
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row ml-1 mb-3">
                        
                    </div>
                                  
                    <div class="table-responsive">
                        <table id="tabla" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>                                   
                                    <th>Nombres</th>                                    
                                    <th>Apellidos</th>
                                    <th>Estado</th>
                                    <th>Cola de Trabajo</th>
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
    $("#menu_ctrl_operarios").addClass("active");
    $('#txt_fch_nac').datepicker({
                    todayHighlight: true,                    
                    templates: controls,
                    format: 'yyyy-mm-dd'
                }); 
    var table = $('#tabla').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('ctrl.estoperarios.list') }}",
        columns: [                                               
            {data: 'name'},
            {data: 'surname'},            
            {data: 'ope_est'},   
            {data: 'cola'},
        ],
        dom: dom_buttons_table,
        buttons: [{
            extend: 'pdfHtml5',
            text: 'PDF',
            titleAttr: 'Generate PDF',
            className: 'btn-outline-danger btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3]
            }
        },{
            extend: 'excelHtml5',
            text: 'Excel',
            titleAttr: 'Generate Excel',
            className: 'btn-outline-success btn-sm mr-1',
            exportOptions: {
                columns: [0,1,2,3]
            }
        }],
        lengthMenu: [ [50, 100, -1], [50, 100, "All"] ],
        language: espanol,
        ordering: false,
    });
    
});





</script>
@endpush
