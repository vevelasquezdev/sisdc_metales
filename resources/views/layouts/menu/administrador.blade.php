{{-- <li id="menu_linea_tiempo">
    <a href="{!! route('linea-tiempo.index') !!}" title="Linea de tiempo" data-filter-tags="linea-tiempo">
        <i class="fas fa-calendar-alt"></i>
        <span class="nav-link-text">Tiempo</span>
    </a>
</li>  --}}
@if((Auth::user())->rol=='ADMINISTRADOR')
    @php
        $userName = Auth::user()->name; // Obtener el nombre del usuario
        $allowedNames = ['ODILSON', 'ZAIDA', 'WILBERT','VLADIMIRO','SAYDA']; // Lista de nombres permitidos
    @endphp
    @if(in_array($userName, $allowedNames))
            <li id="menu_cotizacion">
                <a href="{!! route('cotizacion.index') !!}" title="Cotizacion" data-filter-tags="cotizacion">
                    <i class="fa fa-window-maximize"></i>
                    <span class="nav-link-text">Cotización</span>                                    
                </a>               
            </li>
            <li id="menu_orden">
                <a href="{!! route('orden.index') !!}" title="Orden" data-filter-tags="orden">
                    <i class="fa fa-window-maximize"></i>
                    <span class="nav-link-text">Orden de Servicio</span>                                    
                </a>               
            </li>
            <li id="menu_ctrl_servicios">
                <a href="{!! route('ctrl-servicios.index') !!}" title="Control de Servicios" data-filter-tags="control">
                    <i class="fas fa-dolly"></i>
                    <span class="nav-link-text">Control de Servicios</span>                                    
                </a>               
            </li>
            <li id="menu_cliente">
                <a href="{!! route('clientes.index') !!}" title="Cliente" data-filter-tags="cliente">
                    <i class="fa fa-user"></i>
                    <span class="nav-link-text">Clientes</span>                                    
                </a>               
            </li>
         
            <li id="menu_caja">
                <a href="#" title="Caja" data-filter-tags="caja">
                    <i class="fal fa-chart-line"></i>
                    <span class="nav-link-text" data-i18n="nav.datatables">Caja</span>                                    
                </a>
                <ul>
                    <li id="submenu_caja_movimientos">
                        <a href="{!! route('movimientosctrl.index') !!}" title="Movimientos" data-filter-tags="movimientos">
                            <span class="nav-link-text" >Movimientos</span>
                        </a>
                    </li>                   
                </ul>
            </li>
            
       
            <li id="menu_reportes_caja">
                <a href="#" title="Caja" data-filter-tags="caja">
                    <i class="fa fa-clipboard"></i>
                    <span class="nav-link-text">Reportes</span>                                    
                </a>
                <ul>
                    <li id="submenu_report_ordenes">
                        <a href="report-ordenes" title="Ordenes y Servicios" data-filter-tags="ordenes y servicios">
                            <span class="nav-link-text" >Ordenes</span>
                        </a>
                    </li>
                    <li id="submenu_report_movimientos">
                        <a href="vwreportmovimientos" title="Movimientos" data-filter-tags="movimientos">
                            <span class="nav-link-text" >Movimientos Caja</span>
                        </a>
                    </li>
                    <li id="submenu_report_servicios">
                        <a href="report-servicios-dia" title="Servicios por dia" data-filter-tags="servicios por dia">
                            <span class="nav-link-text" >Servicios x Día</span>
                        </a>
                    </li>
                    <li id="submenu_report_ingresos_salidas">
                        <a href="report-ingresos-salidas" title="Ingresos vs Ventas" data-filter-tags="ingresos vs ventas">
                            <span class="nav-link-text">Almacen Ingresos/Salidas</span>
                        </a>
                    </li>                
                </ul>
            </li>
            
            <li class="nav-title">Almacenes</li>            
            <li id="menu_almacenes">    
                <li id="submenu_alm_rioseco">
                    <a href="{!! route('ing_sal_insumos.index') !!}" title="Almacen Rio Seco" data-filter-tags="Ingresos y Salidas Rio Seco">
                        <i class="ni ni-basket"></i>
                        <span class="nav-link-text" >Rio_Seco</span>
                    </a>
                </li>
                <li id="submenu_alm_zolezzi">
                    <a href="{{ route('ing_sal_insumos_zolezzi') }}" title="Almacen Zolezzi" data-filter-tags="Ingresos y Salidas Zolezzi">
                        <i class="ni ni-basket"></i>
                        <span class="nav-link-text" >Zolezzi</span>
                    </a>
                </li>                   
                <li id="submenu_alm_stock">
                    <a href="{!! route('stock.index') !!}" title="Stock" data-filter-tags="stock">
                        <i class="ni ni-bar-chart"></i>
                        <span class="nav-link-text" >Stock</span>
                    </a>
                </li>
            </li>
            
            
            <li class="nav-title">Configuraciones</li>
            <li id="menu_administracion"> {{-- active open --}}
                <a href="#" title="Application Intel" data-filter-tags="administracion">
                    <i class="fal fa-cog"></i>
                    <span class="nav-link-text" data-i18n="nav.application_intel">Administracion</span>
                </a>
                <ul>                   
                    <li id="submenu_prod_insumos">
                        <a href="{!! route('insumos.index') !!}" title="Materiales" data-filter-tags="materiales">
                            <span class="nav-link-text">Productos Almacen</span>
                        </a>
                    </li>                    
                    <li id="submenu_productos"> 
                        <a href="{!! route('productos.index') !!}" title="Productos" data-filter-tags="productos">
                            <span class="nav-link-text">Productos Fabricados</span>
                        </a>
                    </li>
                    <li id="submenu_usuarios">
                        <a href="{!! route('vwuser') !!}" title="Usuarios" data-filter-tags="usuarios">
                            <span class="nav-link-text">Usuarios</span>
                        </a>
                    </li>
                    <li id="submenu_servicios"> 
                        <a href="{!! route('vwservicios') !!}" title="Servicios" data-filter-tags="servicios">
                            <span class="nav-link-text">Servicios</span>
                        </a>
                    </li>   
                    <li id="submenu_confmaterial">
                        <a href="{!! route('confmaterial.index') !!}" title="Materiales" data-filter-tags="materiales">
                            <span class="nav-link-text">Materiales Retazos</span>
                        </a>
                    </li>            
                </ul>
            </li>
    @else

            <li id="menu_cotizacion">
                <a href="{!! route('cotizacion.index') !!}" title="Cotizacion" data-filter-tags="cotizacion">
                    <i class="fa fa-window-maximize"></i>
                    <span class="nav-link-text">Cotización</span>                                    
                </a>               
            </li>
            <li id="menu_orden">
                <a href="{!! route('orden.index') !!}" title="Orden" data-filter-tags="orden">
                    <i class="fa fa-window-maximize"></i>
                    <span class="nav-link-text">Orden de Servicio</span>                                    
                </a>               
            </li>
            <li id="menu_ctrl_servicios">
                <a href="{!! route('ctrl-servicios.index') !!}" title="Control de Servicios" data-filter-tags="control">
                    <i class="fas fa-dolly"></i>
                    <span class="nav-link-text">Control de Servicios</span>                                    
                </a>               
            </li>
            <li id="menu_cliente">
                <a href="{!! route('clientes.index') !!}" title="Cliente" data-filter-tags="cliente">
                    <i class="fa fa-user"></i>
                    <span class="nav-link-text">Clientes</span>                                    
                </a>               
            </li>
        
            <li id="menu_caja">
                <a href="#" title="Caja" data-filter-tags="caja">
                    <i class="fal fa-chart-line"></i>
                    <span class="nav-link-text" data-i18n="nav.datatables">Caja</span>                                    
                </a>
                <ul>
                    <li id="submenu_caja_movimientos">
                        <a href="{!! route('movimientosctrl.index') !!}" title="Movimientos" data-filter-tags="movimientos">
                            <span class="nav-link-text" >Movimientos</span>
                        </a>
                    </li>                   
                </ul>
            </li>
            
        
            <li id="menu_reportes_caja">
                <a href="#" title="Caja" data-filter-tags="caja">
                    <i class="fa fa-clipboard"></i>
                    <span class="nav-link-text">Reportes</span>                                    
                </a>
                <ul>
                    <li id="submenu_report_ordenes">
                        <a href="report-ordenes" title="Ordenes y Servicios" data-filter-tags="ordenes y servicios">
                            <span class="nav-link-text" >Ordenes</span>
                        </a>
                    </li>
                    <li id="submenu_report_movimientos">
                        <a href="vwreportmovimientos" title="Movimientos" data-filter-tags="movimientos">
                            <span class="nav-link-text" >Movimientos Caja</span>
                        </a>
                    </li>
                    <li id="submenu_report_servicios">
                        <a href="report-servicios-dia" title="Servicios por dia" data-filter-tags="servicios por dia">
                            <span class="nav-link-text" >Servicios x Día</span>
                        </a>
                    </li>                               
                </ul>
            </li>
    
    
    
    
    
    @endif
@endif
