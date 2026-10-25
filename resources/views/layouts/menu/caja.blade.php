<li id="menu_cliente">
    <a href="{!! route('clientes.index') !!}" title="Cliente" data-filter-tags="cliente">
        <i class="fa fa-user"></i>
        <span class="nav-link-text">Clientes</span>                                    
    </a>               
</li>
<li id="menu_orden">
    <a href="{!! route('orden.index') !!}" title="Orden" data-filter-tags="orden">
        <i class="fa fa-window-maximize"></i>
        <span class="nav-link-text">Orden de Servicio</span>                                    
    </a>               
</li>




<li class="nav-title">Caja</li>
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

<li class="nav-title">Reportes Caja</li>            
<li id="menu_reportes_caja">
    <a href="#" title="Caja" data-filter-tags="caja">
        <i class="fa fa-clipboard"></i>
        <span class="nav-link-text">Reportes Caja</span>                                    
    </a>
    <ul>
        <li id="submenu_report_movimientos">
            <a href="vwreportmovimientos" title="Movimientos" data-filter-tags="movimientos">
                <span class="nav-link-text" >Movimientos</span>
            </a>
        </li> 
        <li id="submenu_report_ingresos_salidas">
            <a href="report-ingresos-salidas" title="Ingresos vs Ventas" data-filter-tags="ingresos vs ventas">
                <span class="nav-link-text">Almacen Ingresos/Salidas</span>
            </a>
        </li>                  
    </ul>
</li>