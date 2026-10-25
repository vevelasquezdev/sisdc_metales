<?php

use App\Http\Controllers\admin\ClientesController;
use App\Http\Controllers\admin\ColaboradoresController;
use App\Http\Controllers\admin\ConfigMaterialController;
use App\Http\Controllers\admin\ConsumoMatCotizacionController;
use App\Http\Controllers\admin\ConsumoMaterialController;
use App\Http\Controllers\admin\ControlServiciosController;
use App\Http\Controllers\admin\CotizacionController;
use App\Http\Controllers\admin\CotizacionDetalleController;
use App\Http\Controllers\admin\LineaTiempoController;
use App\Http\Controllers\admin\ListaTrabajoController;
use App\Http\Controllers\admin\OperariosController;
use App\Http\Controllers\admin\OrdenController;
use App\Http\Controllers\admin\OrdenDetalleController;
use App\Http\Controllers\admin\ServiciosController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\caja\MovimientosController;
use App\Http\Controllers\LoginOperadorController;
use App\Http\Controllers\reportes\ReportMovimientosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\productos\ProductoDetalleController;
use App\Http\Controllers\productos\ProductosController;
use App\Http\Controllers\reportes\ReportServiciosDiaController;
use Illuminate\Http\Request;
use App\Http\Controllers\AccessIpController;
use App\Http\Controllers\insumos\IngresoSalidaInsumosController;
use App\Http\Controllers\insumos\InsumosController;
use App\Http\Controllers\insumos\ProveedoresController;
use App\Http\Controllers\insumos\StockController;
use App\Http\Controllers\reportes\ReportIngresosSalidasInsumos;
use App\Http\Controllers\reportes\ReportOrdenes;

Route::get('print_boleta/{id_orden}', [OrdenController::class, 'print_boleta']);
Route::get('print_cotizacion/{id_orden}', [CotizacionController::class, 'print_cotizacion']);

Route::get('/admin-'.date('d-m-Y'), function (Request $request) {
    if ($request->session()->has('authenticatedIp')) {
        return redirect()->route('protected-content');
    }else{
        return view('acceso_ip.login-ip');
    }
    
})->name('protected');


Route::post('/check-password', function (Request $request) {
    $password = $request->input('password');
    $correctPassword = 'sdc_123Cuatro+';

    if ($password === $correctPassword) {
        $request->session()->put('authenticatedIp', true);
        return redirect()->route('protected-content');
    } else {
        return redirect()->route('protected')->with('error', 'Contraseña incorrecta');
    }
})->name('check-password');

Route::get('/protected-content', [AccessIpController::class, 'index'])->name('protected-content')->middleware('checkIpAuthenticated');
Route::post('/access-ips', [AccessIpController::class, 'store'])->name('access-ips.store')->middleware('checkIpAuthenticated');
Route::delete('/access-ips/{id}', [AccessIpController::class, 'destroy'])->name('access-ips.destroy')->middleware('checkIpAuthenticated');

Route::post('/logout-ip', function (Request $request) {
    $request->session()->forget('authenticatedIp');
    return redirect()->route('protected')->with('message', 'Sesión cerrada exitosamente');
})->name('logout-ip');


Route::middleware([])->group(function () {
// Route::middleware(['check.local.ip'])->group(function () {
  
    Route::get('/login', function () {
        return view('auth.login'); 
    })->name('login');

    Route::post('/login', [CustomLoginController::class, 'login']);

    Route::group(['middleware' => 'guest'], function () {
        Route::get('/', function () {return view('auth.login');});
    });

    Route::group(['middleware'=>['auth','PreventBackHistory']], function(){

        Route::resource('linea-tiempo', LineaTiempoController::class)->names('linea-tiempo');
    
        Route::get('dashboard', [LoginOperadorController::class, 'home_ini'])->name('dashboard');
        Route::get('tot-por-local-procedencia', [LoginOperadorController::class, 'tot_por_local_procedencia']);
        Route::get('tot-por-local-procedencia-monto', [LoginOperadorController::class, 'tot_por_local_procedencia_monto']);
        Route::get('pie-chart-tot-orden-porusuario', [LoginOperadorController::class, 'piechart_tot_orden_usuario']);
        Route::get('pie-chart-tot-orden-porusuario-monto', [LoginOperadorController::class, 'piechart_tot_orden_usuario_monto']);
        Route::get('count-trab-operadores', [LoginOperadorController::class, 'count_trab_operadores']);
        Route::get('get-totales-por-mes', [LoginOperadorController::class, 'get_totales_por_mes']);
        Route::get('report_serv_monto', [LoginOperadorController::class, 'report_serv_monto']);
        Route::get('report_serv_cantidad', [LoginOperadorController::class, 'report_serv_cantidad']);
        
        /*USUARIOS*/ 
        Route::get('view_users', [UserController::class, 'index'])->name('vwuser');
        Route::resource('users', UserController::class)->names('users');


        /*SERVICIOS*/  
        Route::get('view_servicios', [ServiciosController::class, 'index'])->name('vwservicios');  
        Route::resource('servicios', ServiciosController::class)->names('servicios');
        Route::get('serv_autocompletar/{query}', [ServiciosController::class, 'autocompletar']);// buscardor de servicios

        /*CONF MATERIALES*/  
        Route::get('confmaterial-list', [ConfigMaterialController::class, 'getMateriales'])->name('confmaterial.list');  
        Route::resource('confmaterial', ConfigMaterialController::class)->names('confmaterial');


        // CONSUMO MATERIAL
        Route::resource('consumomaterial', ConsumoMaterialController::class)->names('consumomaterial');    

        /* CLIENTES */
        Route::get('clientes-list', [ClientesController::class, 'getClientes'])->name('clientes.list');
        Route::resource('clientes', ClientesController::class)->names('clientes');

        /** ORDEN DE SERVICIO */
        Route::get('orden-list', [OrdenController::class, 'getOrdenesServicio'])->name('orden.list');
        Route::resource('orden', OrdenController::class)->names('orden');  
        /** aqui iba el print_boleta */
        Route::get('realizar_entrega_producto/{id_orden}', [OrdenController::class, 'realizar_entrega_producto']);
        Route::get('get_saldo/{id_orden}', [OrdenController::class, 'get_saldo']);
        Route::get('ver_archivos_subidos/{id_orden}', [OrdenController::class, 'ver_archivos_subidos']);
        Route::get('print-hoja-produccion/{id_orden}', [OrdenController::class, 'print_hoja_produccion']);


        /** CAJA -----------*/
        Route::resource('movimientosctrl', MovimientosController::class)->names('movimientosctrl');
        Route::get('movimientos_list', [MovimientosController::class, 'getMovimientos'])->name('movimientos.list');
        Route::post('insert-movimiento', [MovimientosController::class, 'insert_movimiento']);


        /**REPORTES */
        Route::get('vwreportmovimientos', [ReportMovimientosController::class, 'vw_reporte_movimientos']);
        Route::get('reporte-movimientos/{fch?}{caja?}{tipo?}', [ReportMovimientosController::class, 'reporte_movimientos']);
        Route::get('reporte-movimientos-pdf/{fch?}{caja?}', [ReportMovimientosController::class, 'reporte_movimientos_pdf']);
        Route::get('report-servicios-dia', [ReportServiciosDiaController::class, 'report_servicios_dia']);
        Route::get('report-tabla-servicios-dia/{fch?}', [ReportServiciosDiaController::class, 'tabla_servicios_dia']);
        Route::get('report-pdf-servicios-dia/{fch?}', [ReportServiciosDiaController::class, 'pdf_servicios_dia']);
        Route::get('report-ingresos-salidas', [ReportIngresosSalidasInsumos::class, 'index']);
        Route::get('report-tabla-ingresos-salidas', [ReportIngresosSalidasInsumos::class, 'tabla_ingresos_salidas_por_orden']);
        Route::get('report-pdf-ingresos-salidas', [ReportIngresosSalidasInsumos::class, 'pdf_ingresos_salidas']);
        Route::get('report-ordenes', [ReportOrdenes::class, 'index']);
        Route::get('report-tabla-ordenes', [ReportOrdenes::class, 'tabla_report_ordenes']);


        Route::get('/tabla_orden_detalle/{id_orden?}', [OrdenDetalleController::class, 'get_ittems_orden_detalle'])->name('tabla_orden_detalle');
        Route::resource('orden-detalle', OrdenDetalleController::class)->names('orden-detalle');
        Route::get('borrar-orden-no/{id_orden}', [OrdenDetalleController::class, 'borrar_orden_no']);
        Route::get('borrar-orden-temporal/{id_orden}', [OrdenDetalleController::class, 'borrar_orden_temporal']);
        Route::get('add_coment', [OrdenDetalleController::class, 'add_coment']);
        Route::get('check_orden/{id_orden_detalle}', [OrdenDetalleController::class, 'check_orden']);// eliminar sin pedir razon tabla pedido_detalle_tem

        
        /**CONTROL DE SERVICIOS*/
        Route::resource('ctrl-servicios', ControlServiciosController::class)->names('ctrl-servicios');
        Route::get('ctrl-servicios-list', [ControlServiciosController::class, 'getCtrlServicios'])->name('ctrl.servicios.list');
        Route::get('enviar-al-servicio/{id_servicio}/{id_orden}', [ControlServiciosController::class, 'enviarAlServicio']);
        Route::get('get-operarios', [ControlServiciosController::class, 'getOperarios']);//ya no se usa
        Route::get('get-operarios-colaborador', [ControlServiciosController::class, 'getOperariosColaborador']);


        /** COLABORADORES*/
        Route::get('add-colaborador/{id_servicio}', [ColaboradoresController::class, 'addColaborador']);
        Route::get('tabla-colaboradores/{id_servicio}', [ColaboradoresController::class, 'tabla_colaboradores']);
        Route::get('delete-colaborador/{id_servicio}', [ColaboradoresController::class, 'deleteColaborador']);
        Route::get('check-encargado-trabajo/{id_lista_trabajo}', [ColaboradoresController::class, 'check_encargado_trabajo']);
        //Route::resource('colaboradores-resource', ColaboradoresController::class)->names('colaboradores-resource');


        /** LISTA DE TRABAJO */
        Route::resource('lista-trabajo', ListaTrabajoController::class)->names('lista-trabajo');
        Route::get('trabajos', [ListaTrabajoController::class, 'getTrabajos'])->name('trabajos');
        Route::get('trabajos-terminados', [ListaTrabajoController::class, 'getTrabajosTerminados'])->name('trabajos.terminados');
        Route::get('enviar-sgte-servicio/{id_list_trab}/{id_servicio}/{id_orden}/{numeracion}', [ListaTrabajoController::class, 'enviarSiguienteServicio']);
        Route::get('ultimo-servicio/{id_servicio}/{id_orden}', [ListaTrabajoController::class, 'UltimoServicio']);
        Route::get('add_coment_trabajo', [ListaTrabajoController::class, 'add_coment_trabajo']);
        Route::get('pausar-trabajo', [ListaTrabajoController::class, 'pausar']);
        Route::get('reanudar-trabajo', [ListaTrabajoController::class, 'reanudar']);
        

        /**CTRL DE OPERARIOS */
        Route::resource('ctrl-operarios', OperariosController::class)->names('ctrl-operarios');
        Route::get('ctrl-estoperarios-list', [OperariosController::class, 'getEstOperarios'])->name('ctrl.estoperarios.list');

        /** LOGIN OPERARIO */
        Route::get('login.operador/{id_user}', [LoginOperadorController::class, 'login_operador'])->name('login-operador');

        /** enviar MENSAJE E-MAIL */
        Route::get('enviar-email/{id_orden}', [OrdenController::class, 'EnviarEmail']);

        /** PRINT TICKET */
        Route::get('print-ticket-qr-pdf/{id_orden}', [OrdenController::class, 'print_ticket_pdf']);  
        Route::get('print-ticket-qr-png/{id_orden}', [OrdenController::class, 'print_ticket_png']);
        Route::get('print-ticket-proforma/{id_orden}', [OrdenController::class, 'print_ticket_proforma']);


        /**COTIZACION */
        Route::get('print-cotizacion-pequeno/{id_orden}', [CotizacionController::class, 'print_cotizacion_pequeno']);
        Route::get('cotizacion-list', [CotizacionController::class, 'getCotizacion'])->name('cotizacion.list');
        Route::resource('cotizacion', CotizacionController::class)->names('cotizacion');
        Route::get('/tabla_cotiza_detalle/{id_cotizacion?}', [CotizacionDetalleController::class, 'get_ittems_orden_detalle'])->name('tabla_cotiza_detalle');
        Route::get('ver_archivos_cotizacion/{id_orden}', [CotizacionController::class, 'ver_archivos_cotizacion']);

        Route::resource('cotizacion-detalle', CotizacionDetalleController::class)->names('cotizacion-detalle');
        Route::get('borrar-cotizacion-no/{id_orden}', [CotizacionDetalleController::class, 'borrar_cotizacion_no']);
        Route::get('borrar-cotizacion-temporal/{id_orden}', [CotizacionDetalleController::class, 'borrar_cotizacion_temporal']);
        Route::get('/tabla_cotizacion_detalle/{id_orden?}', [CotizacionDetalleController::class, 'get_ittems_cotizacion_detalle'])->name('tabla_cotizacion_detalle');
        Route::resource('consumomaterial-cotizacion', ConsumoMatCotizacionController::class);
        Route::get('cotizacion-cambiar_estado', [CotizacionController::class, 'cambiar_estado']);
        Route::get('tipo-cambio', [CotizacionController::class, 'get_tipo_cambio'])->name('tipo.cambio');


        /** PARA SUBIR ARCHIVO DESDE VISTA OPERADOR */
        Route::post('/subir-archivo-operador', [OrdenController::class, 'subir_archivo']);


        /**PRODUCTOS */
        Route::resource('productos',ProductosController::class)->names('productos');
        Route::get('tabla-admin-productos',[ProductosController::class,'tabla_productos'])->name('tabla_productos_get');
        Route::get('productos_autocompletar/{query}', [ProductosController::class, 'autocompletar']);// buscardor de productos
        Route::get('autocompletar_orden/{id_producto}/{id_orden}', [ProductosController::class, 'autocompletar_orden_producto']);// tabla orden autocompletar
        Route::get('autocompletar_orden_detalle/{id_orden}', [ProductosController::class, 'autocompletar_orden_detalle_producto']);// tabla orden_detalle autocompletar

        Route::resource('producto-detalle',ProductoDetalleController::class)->names('producto-detalle');
        Route::get('tabla-admin-servicios-por-prod/{id}',[ProductoDetalleController::class,'tabla_servicios_por_producto'])->name('tabla_admin_servicios_por_prod');


        //-----INSUMOS---VENTA DE PLANCHAS-----//
        Route::get('insumos-stock-list', [StockController::class, 'getInsumosStock'])->name('insumos.stock.list');
        Route::resource('stock', StockController::class)->names('stock');
        Route::post('llenar-historial-stock', [StockController::class, 'llenarHistorialStock'])->name('llenar.historial.stock');

        Route::get('proveedores-list', [ProveedoresController::class, 'getProveedores'])->name('proveedores.list');
        Route::resource('proveedores', ProveedoresController::class)->names('proveedores');

        Route::get('insumos-list', [InsumosController::class, 'getInsumosAlmacen'])->name('insumos.list'); //productos/Insumos
        Route::resource('insumos', InsumosController::class)->names('insumos');
        Route::get('insumos_autocompletar/{query}/{id_almacen}', [InsumosController::class, 'autocompletar']);// buscardor de insumos

        Route::get('ing-sal-insumos-list', [IngresoSalidaInsumosController::class, 'getIngSalInsumos'])->name('ing.sal.insumos.list'); //ingreso y salidas de insumos
        Route::resource('ing_sal_insumos', IngresoSalidaInsumosController::class)->names('ing_sal_insumos'); 
        Route::get('ing_sal_insumos_zolezzi', [IngresoSalidaInsumosController::class, 'abrir_zolezzi'])->name('ing_sal_insumos_zolezzi');      
        Route::post('movimiento_buscar_almacen',[IngresoSalidaInsumosController::class, 'movimiento_buscar_almacen']);
    });

});





