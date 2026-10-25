@extends('layouts.app')
@section('titulo') Clientes @endsection


@section('contenido')

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Tabla</i></span>&nbsp;&nbsp;Clientes 
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>                    
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row ml-1 mb-3">
                        <button class="btn btn-primary" data-toggle='modal' data-target='.default-example-modal-right'>
                            Nuevo Cliente
                        </button>
                    </div>
                                  
                    <div class="table-responsive">
                        <table id="tabla" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>                                   
                                    <th>Servicio</th>                                    
                                    <th colspan="4">1</th>
                                    <th colspan="4">2</th>
                                    <th colspan="4">3</th>
                                    <th colspan="4">4</th>
                                    <th colspan="4">5</th> 
                                    <th>6</th>                                  
                                    <th colspan="4">7</th>
                                    <th colspan="4">8</th>
                                    <th colspan="4">9</th>
                                    <th colspan="4">10</th>
                                    <th colspan="4">11</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Corte Laser tine</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
                                    <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
                                </tr>
                                <tr>
                                    <td>Plegado</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
                                    <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
                                </tr>
                                <tr>
                                    <td>Guillotine</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
                                    <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
                                </tr>
                            </tbody>                    
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
    $('#tabla').dataTable({
        responsive: true,
        ordering: false,
    });

});




function close_modal(){  
    $("#DldModal").modal('hide');
    $("#txt_id").val('');
    $('.error_msg').addClass('d-none').text('');  
    $("#form")[0].reset();
}




</script>
@endpush
