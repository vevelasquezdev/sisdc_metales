@extends('layouts.app')
@section('titulo') {{ __('Dashboard') }} @endsection



@section('contenido')

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>PANEL</i></span>&nbsp;&nbsp;DE BIENVENIDA
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        @if((Auth::user())->rol=='ADMINISTRADOR')
                            @php
                                $userName = Auth::user()->name; // Obtener el nombre del usuario
                                $allowedNames = ['ODILSON', 'ZAIDA', 'WILBERT','VLADIMIRO','SAYDA']; // Lista de nombres permitidos
                            @endphp
                            @if(in_array($userName, $allowedNames))
                                <div class="col-sm-12 py-0 px-0 bg-white border">
                                    <div class="panel-container show">
                                        <div class="panel-content">
                                            <div class="panel-tag col-md-12">
                                                <div class="row">
                                                    <div>Gráfico: Flujo de caja mensual.</div> 
                                                </div>
                                            </div>
                                            <div class="row mb-4 justify-content-end">
                                                <div class="mt-2">Año:</div>                                        
                                                <div class="col-md-3">                                            
                                                    <select id="graf_flujo_anio" onchange="flujo_anio();" class="form-control jquery_field text-uppercase" required>
                                                        <option value="2023">2023</option>
                                                        <option value="2024">2024</option>
                                                        <option value="2025">2025</option>
                                                        <option value="2026">2026</option>
                                                        <option value="2027">2027</option>
                                                    </select>                                            
                                                </div>
                                            </div>
                                            
                                            <div id="tot_por_mes" style="width:100%; height:300px;"></div>
                                        </div>
                                    </div>
                                </div>

                                                    
                                <div class="col-sm-12 py-0 px-0 bg-white border border-top-0">
                                    <div class="panel-container show">
                                        <div class="panel-content">
                                            <div class="panel-tag col-md-12">
                                                <div class="row">
                                                    <div>Gráfico: trabajos por operador.</div> 
                                                </div>
                                            </div>
                                            <div class="row mb-4 justify-content-end">
                                                <div class="col-md-3">                                            
                                                    <select id="reporte_operador_mes" onchange="select_mes();" class="form-control jquery_field text-uppercase" required>
                                                        <option value="00">Selecciona Mes</option>
                                                        <option value="01">enero</option>
                                                        <option value="02">febrero</option>
                                                        <option value="03">marzo</option>
                                                        <option value="04">abril</option>
                                                        <option value="05">mayo</option>
                                                        <option value="06">junio</option>
                                                        <option value="07">julio</option>
                                                        <option value="08">agosto</option>
                                                        <option value="09">setiembre</option>
                                                        <option value="10">octubre</option>
                                                        <option value="11">noviembre</option>
                                                        <option value="12">diciembre</option>
                                                    </select>                                            
                                                </div>
                                                <div class="mt-2">Año:</div>                                        
                                                <div class="col-md-3">                                            
                                                    <select id="reporte_operador_mes_anio" onchange="select_mes();" class="form-control jquery_field text-uppercase" required>
                                                        <option value="2023">2023</option>
                                                        <option value="2024">2024</option>
                                                        <option value="2025">2025</option>
                                                        <option value="2026">2026</option>
                                                        <option value="2027">2027</option>
                                                    </select>                                            
                                                </div>
                                            </div>
                                            <div id="tot_trab_count" style="width:100%; height:300px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 py-0 px-0 bg-white border border-top-0">
                                    <div class="panel-container show">
                                        <div class="panel-content">
                                            <div class="panel-tag col-md-12">
                                                <div class="row">
                                                    <div>Gráfico: Total por servicios (Monto y Cantidad).</div> 
                                                </div>
                                            </div>
                                            <div class="row justify-content-end">
                                                <div class="col-md-2  mb-3">                                            
                                                    <select id="reporte_serv_select1" onchange="fn_report_serv()" class="form-control jquery_field text-uppercase" required>
                                                        <option value="01">Monto ($)</option>
                                                        <option value="02">cantidad</option>                                                                                                       
                                                    </select>                                            
                                                </div>
                                                <div class="mt-2">Mes:</div>
                                                <div class="col-md-2 mb-3">                                            
                                                    <select id="reporte_serv_mes" onchange="fn_report_serv()" class="form-control jquery_field text-uppercase" required>
                                                        <option value="00">Todos</option>
                                                        <option value="01">enero</option>
                                                        <option value="02">febrero</option>
                                                        <option value="03">marzo</option>
                                                        <option value="04">abril</option>
                                                        <option value="05">mayo</option>
                                                        <option value="06">junio</option>
                                                        <option value="07">julio</option>
                                                        <option value="08">agosto</option>
                                                        <option value="09">setiembre</option>
                                                        <option value="10">octubre</option>
                                                        <option value="11">noviembre</option>
                                                        <option value="12">diciembre</option>
                                                    </select>                                            
                                                </div>
                                                <div class="mt-2">Año:</div>                                   
                                                <div class="col-md-2 mb-3">                                                                                       
                                                    <select id="reporte_serv_anio" onchange="fn_report_serv();" class="form-control jquery_field text-uppercase" required>
                                                        <option value="2023">2023</option>
                                                        <option value="2024">2024</option>
                                                        <option value="2025">2025</option>
                                                        <option value="2026">2026</option>
                                                        <option value="2027">2027</option>
                                                    </select>                                            
                                                </div>
                                            </div>
                                        
                                            <div id="report_serv_monto_mes" style="width:100%; height:300px;"></div>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-sm-12 py-0 px-0 bg-white border border-top-0">
                                    <div class="panel-container show">
                                        <div class="panel-content">
                                            <div class="panel-tag col-md-12">
                                                Ordenes segun Local de procedencia
                                            </div>                                    
                                            <div class="row mb-4 justify-content-end">
                                                <div class="col-md-2  mb-3">                                            
                                                    <select id="reporte_TotPorLocal_tipo" onchange="fn_TotPorLocal()" class="form-control jquery_field text-uppercase" required>
                                                        <option value="01">Cantidad</option>
                                                        <option value="02">Monto (S/.)</option>                                                                                                       
                                                    </select>                                            
                                                </div>
                                                <div class="mt-2">Mes:</div>
                                                <div class="col-md-2 mb-3">                                            
                                                    <select id="reporte_TotPorLocal_mes" onchange="fn_TotPorLocal()" class="form-control jquery_field text-uppercase" required>
                                                        <option value="00">Todos</option>
                                                        <option value="01">enero</option>
                                                        <option value="02">febrero</option>
                                                        <option value="03">marzo</option>
                                                        <option value="04">abril</option>
                                                        <option value="05">mayo</option>
                                                        <option value="06">junio</option>
                                                        <option value="07">julio</option>
                                                        <option value="08">agosto</option>
                                                        <option value="09">setiembre</option>
                                                        <option value="10">octubre</option>
                                                        <option value="11">noviembre</option>
                                                        <option value="12">diciembre</option>
                                                    </select>                                            
                                                </div>
                                                <div class="mt-2">Año:</div>                                   
                                                <div class="col-md-2 mb-3">                                                                                       
                                                    <select id="reporte_TotPorLocal_anio" onchange="fn_TotPorLocal();" class="form-control jquery_field text-uppercase" required>
                                                        <option value="2023">2023</option>
                                                        <option value="2024">2024</option>
                                                        <option value="2025">2025</option>
                                                        <option value="2026">2026</option>
                                                        <option value="2027">2027</option>
                                                    </select>                                            
                                                </div>
                                            </div>
                                            
                                            <div id="graf_Tot_por_Local" style="width:100%; height:300px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 py-0 px-0 bg-white border border-top-0">
                                    <div class="panel-container show">
                                        <div class="panel-content">
                                            <div class="panel-tag col-md-12">
                                                <div class="row" >
                                                    <div>Ordenes por Usuario</div>
                                                </div>
                                            </div>
                                            <div class="row mb-4 justify-content-end">
                                                <div class="col-md-2  mb-3">                                            
                                                    <select id="reporte_select_TotPorUsuario" onchange="fn_TotPorUsuario()" class="form-control jquery_field text-uppercase" required>
                                                        <option value="01">Cantidad</option>
                                                        <option value="02">Monto (S/.)</option>                                                                                                       
                                                    </select>                                            
                                                </div>
                                                <div class="mt-2">Mes:</div>
                                                <div class="col-md-2 mb-3">                                            
                                                    <select id="reporte_TotPorUsuario_mes" onchange="fn_TotPorUsuario()" class="form-control jquery_field text-uppercase" required>
                                                        <option value="00">Todos</option>
                                                        <option value="01">enero</option>
                                                        <option value="02">febrero</option>
                                                        <option value="03">marzo</option>
                                                        <option value="04">abril</option>
                                                        <option value="05">mayo</option>
                                                        <option value="06">junio</option>
                                                        <option value="07">julio</option>
                                                        <option value="08">agosto</option>
                                                        <option value="09">setiembre</option>
                                                        <option value="10">octubre</option>
                                                        <option value="11">noviembre</option>
                                                        <option value="12">diciembre</option>
                                                    </select>                                            
                                                </div>
                                                <div class="mt-2">Año:</div>                                   
                                                <div class="col-md-2 mb-3">                                                                                       
                                                    <select id="reporte_TotPorUsuario_anio" onchange="fn_TotPorUsuario();" class="form-control jquery_field text-uppercase" required>
                                                        <option value="2023">2023</option>
                                                        <option value="2024">2024</option>
                                                        <option value="2025">2025</option>
                                                        <option value="2026">2026</option>
                                                        <option value="2027">2027</option>
                                                    </select>                                            
                                                </div>
                                            </div>
                                            <div id="pieTotPorUsuario" style="width:100%; height:300px;"></div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-sm-12 py-0 px-0 bg-white border border-top-0">
                                    <div class="panel-container show">
                                        <div class="panel-content">
                                            <div class="panel-tag col-md-12">
                                                <div class="row" >
                                                    <div>Ordenes por Usuario</div>
                                                </div>
                                            </div>
                                            <div class="row mb-4 justify-content-end">
                                                <div class="col-md-2  mb-3">                                            
                                                    <select id="reporte_select_TotPorUsuario" onchange="fn_TotPorUsuario()" class="form-control jquery_field text-uppercase" required>
                                                        <option value="01">Cantidad</option>
                                                        <option value="02">Monto (S/.)</option>                                                                                                       
                                                    </select>                                            
                                                </div>
                                                <div class="mt-2">Mes:</div>
                                                <div class="col-md-2 mb-3">                                            
                                                    <select id="reporte_TotPorUsuario_mes" onchange="fn_TotPorUsuario()" class="form-control jquery_field text-uppercase" required>
                                                        <option value="00">Todos</option>
                                                        <option value="01">enero</option>
                                                        <option value="02">febrero</option>
                                                        <option value="03">marzo</option>
                                                        <option value="04">abril</option>
                                                        <option value="05">mayo</option>
                                                        <option value="06">junio</option>
                                                        <option value="07">julio</option>
                                                        <option value="08">agosto</option>
                                                        <option value="09">setiembre</option>
                                                        <option value="10">octubre</option>
                                                        <option value="11">noviembre</option>
                                                        <option value="12">diciembre</option>
                                                    </select>                                            
                                                </div>
                                                <div class="mt-2">Año:</div>                                   
                                                <div class="col-md-2 mb-3">                                                                                       
                                                    <select id="reporte_TotPorUsuario_anio" onchange="fn_TotPorUsuario();" class="form-control jquery_field text-uppercase" required>
                                                        <option value="2023">2023</option>
                                                        <option value="2024">2024</option>
                                                        <option value="2025">2025</option>
                                                        <option value="2026">2026</option>
                                                        <option value="2027">2027</option>
                                                    </select>                                            
                                                </div>
                                            </div>
                                            <div id="pieTotPorUsuario" style="width:100%; height:300px;"></div>
                                        </div>
                                    </div>
                                </div>                         
                            @endif
                        @elseif((Auth::user())->rol=='OPERARIO')

                            @foreach ($operadores as $operador)

                                @if ($operador->tot == 0)
                                    <div class="col-sm-6">
                                        <a href="{!! route('login-operador', $operador->id ) !!}" class="btn btn-pills btn-info waves-effect waves-themed mt-3">
                                            <b>{{ $operador->name." ".$operador->surname." - (".$operador->tot.")" }}</b>
                                        </a>
                                    </div>                           
                                @else
                                    <div class="col-sm-6">
                                        <a href="{!! route('login-operador', $operador->id ) !!}" class="btn btn-pills btn-danger waves-effect waves-themed mt-3">
                                            <b>{{ $operador->name." ".$operador->surname." - (".$operador->tot.")" }}</b>
                                        </a>
                                    </div>
                                @endif
                            @endforeach

                        @elseif((Auth::user())->rol=='CAJA')
                            <div class="col-sm-6 col-xl-3">
                                <a href="{!! route('orden.index') !!}" class="btn btn-outline-info color-info-900">
                                    <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                        ORDENES
                                        <small class="m-0 l-h-n">click</small>
                                    </h3>
                                    <i class="fal fa-user position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                        style="font-size:6rem"></i>
                                </a>
                            </div>
                            <div class="col-sm-6 col-xl-3">
                                <a href="{!! route('vwuser') !!}" class="btn btn-outline-info color-info-900">
                                    <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                        USUARIOS
                                        <small class="m-0 l-h-n">click</small>
                                    </h3>
                                    <i class="fal fa-user position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                        style="font-size:6rem"></i>
                                </a>
                            </div>
                            <div class="col-sm-6 col-xl-3">
                                <a href="{!! route('clientes.index') !!}" class="btn btn-outline-info color-info-900">
                                    <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                        CLIENTES
                                        <small class="m-0 l-h-n">click</small>
                                    </h3>
                                    <i class="fal fa-user position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                        style="font-size:6rem"></i>
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){ 
    date = new Date();
    anio = date.getFullYear();
    mes = date.getMonth() + 1;   
    // $("#graf_flujo_anio").val(anio);
    // $("#reporte_operador_mes_anio").val(anio);
    // $("#reporte_serv_anio").val(anio);
    // setTimeout(refrescar, 30000);
});

function refrescar(){   
    location.reload();    
}


var get_total_por_usuario;
$.getJSON('pie-chart-tot-orden-porusuario', {mes:$("#reporte_TotPorUsuario_mes").val(), anio:$("#reporte_TotPorUsuario_anio").val()}, function(chartData) {
    get_total_por_usuario = c3.generate({
        bindto: "#pieTotPorUsuario",
        data: {        
            json: chartData,             
            type : 'bar',
            labels: true,        
            keys: {
                x: 'name',
                value: ['total']
            }
        },    
        axis: {
            x: {
                type: 'categories'
            }
        },
        bar: {
            width: {
                ratio: 0.5
            }
        },        
        legend: {
            show: false
        }       
    });
});
function fn_TotPorUsuario(){
    tipo = $("#reporte_select_TotPorUsuario").val();
    mes=$("#reporte_TotPorUsuario_mes").val();
    anio=$("#reporte_TotPorUsuario_anio").val();
    if(tipo=='01'){
        $.getJSON('pie-chart-tot-orden-porusuario', {mes:mes, anio:anio}, function(chartData) {     
            get_total_por_usuario.load({
                json: chartData,
                keys: {
                    x: 'name',
                    value: ['total']
                }
            });
        });
        event.preventDefault();
    }else{
        $.getJSON('pie-chart-tot-orden-porusuario-monto', {mes:mes, anio:anio}, function(chartData) {     
            get_total_por_usuario.load({
                json: chartData,
                keys: {
                    x: 'name',
                    value: ['total']
                }
            });
        });
        event.preventDefault();
    }
    
}


var get_total_por_mes;
$.getJSON('get-totales-por-mes', {anio:$("#graf_flujo_anio").val()}, function(chartData) {
    get_total_por_mes = c3.generate({
        bindto: "#tot_por_mes",
        data: {        
            json: chartData,             
            type : 'area-spline',
            labels: true,       
            keys: {
                x: 'mes',
                value: ['total_mes']
            }            
        },    
        axis: {
            x: {
                type: 'categories'
            }
        },
        bar: {
            width: {
                ratio: 0.5
            }
        },        
        legend: {
            show: false
        }                
    });
});

function flujo_anio(){
    anio=$("#graf_flujo_anio").val();    
    $.getJSON('get-totales-por-mes', {anio:anio}, function(chartData) {     
        get_total_por_mes.load({
            json: chartData,             
            type : 'area-spline',        
            keys: {
                x: 'mes',
                value: ['total_mes']
            }
        });
    });
    event.preventDefault();
}


var count_trab_operadores;
$.getJSON('count-trab-operadores', {mes:$("#reporte_operador_mes").val()}, function(chartData) {
    count_trab_operadores = c3.generate({
        bindto: "#tot_trab_count",
        data: {        
            json: chartData,             
            type : 'bar',
            labels: true,        
            keys: {
                x: 'operario',
                value: ['total_operador']
            }
        },    
        axis: {
            x: {
                type: 'categories'
            }
        },
        bar: {
            width: {
                ratio: 0.5
            }
        },      
        legend: {
            show: false
        }      
    });
});

function select_mes(){
    mes = $("#reporte_operador_mes").val();
    anio = $("#reporte_operador_mes_anio").val();
    $.getJSON('count-trab-operadores', {mes:mes, anio:anio}, function(chartData) {     
        count_trab_operadores.load({
            json: chartData,
            keys: {
                x: 'operario',
                value: ['total_operador']
            }
        });
    });
    event.preventDefault();
}


var var_report_serv_monto;
$.getJSON('report_serv_monto', {mes:$("#reporte_serv_mes").val(),anio:$("#reporte_serv_anio").val()}, function(chartData) {
    var_report_serv_monto = c3.generate({
        bindto: "#report_serv_monto_mes",
        padding: {
            top: 0,
            right: 0,
            bottom: 40,
            left: 50,
        },
        data: {        
            json: chartData,             
            type : 'bar', 
            labels: true,       
            keys: {
                x: 'descripcion',
                value: ['total']
            }
        },    
        axis: {
            x: {
                type: 'categories'
            }
        },
        bar: {
            width: {
                ratio: 0.5
            }
        },        
        legend: {
            show: false
        }      
    });
});

function fn_report_serv(){
    
    anio =$("#reporte_serv_anio").val();
    mes = $("#reporte_serv_mes").val();
    tipo=$("#reporte_serv_select1").val();

    if(tipo=="01"){
        $.getJSON('report_serv_monto', {mes:mes, anio:anio}, function(chartData) {     
            var_report_serv_monto.load({
                json: chartData,
                keys: {
                    x: 'descripcion',
                    value: ['total']
                }
            });
        });
    }else{
        $.getJSON('report_serv_cantidad', {mes:mes,anio:anio}, function(chartData) {     
            var_report_serv_monto.load({
                json: chartData,
                keys: {
                    x: 'descripcion',
                    value: ['total']
                }
            });
        });
    }
    
    event.preventDefault();
}




var Tot_por_local;
$.getJSON('tot-por-local-procedencia', {mes:$("#reporte_TotPorLocal_mes").val(),anio:$("#reporte_TotPorLocal_anio").val()}, function(chartData) {
    Tot_por_local = c3.generate({
        bindto: "#graf_Tot_por_Local",
        padding: {
            top: 0,
            right: 0,
            bottom: 40,
            left: 50,
        },
        data: {        
            json: chartData,             
            type : 'bar', 
            labels: true,  
            keys: {
                x: 'name',
                value: ['total']
            }
        },    
        axis: {
            x: {
                type: 'categories'
            }
        },
        bar: {
            width: {
                ratio: 0.5
            }
        },
        
        legend: {
            show: false
        }      
    });
});
function fn_TotPorLocal(){
    anio =$("#reporte_TotPorLocal_anio").val();
    mes = $("#reporte_TotPorLocal_mes").val();
    tipo=$("#reporte_TotPorLocal_tipo").val();
  
    if(tipo=="01"){        
        $.getJSON('tot-por-local-procedencia', {mes:mes, anio:anio}, function(chartData) {     
            Tot_por_local.load({
                json: chartData,
                keys: {
                    x: 'name',
                    value: ['total']
                }
            });
        });
    }else{
        $.getJSON('tot-por-local-procedencia-monto', {mes:mes, anio:anio}, function(chartData) {     
            Tot_por_local.load({
                json: chartData,
                keys: {
                    x: 'name',
                    value: ['total']
                }
            });
        });
    }
    
    event.preventDefault();
}



</script>
@endpush