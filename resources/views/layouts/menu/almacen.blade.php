


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

<li id="menu_reportes_caja">
    <a href="#" title="Caja" data-filter-tags="caja">
        <i class="fa fa-clipboard"></i>
        <span class="nav-link-text">Reportes</span>                                    
    </a>
    <ul>        
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
        
                   
    </ul>
</li>