<?php

namespace App\Http\Controllers\reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;

class ReportServiciosDiaController extends Controller
{
    protected $fpdf;
    public function __construct()
    {
        $this->fpdf = new Fpdf();
    }
    
    public function report_servicios_dia(){
        return view('reportes.reporte_serviciosdia');
    }

    public function tabla_servicios_dia(Request $request){
        $data = DB::table('vw_orden_detalle')
                            ->select('id_servicio','descripcion','fch', 'codigo_orden','estado', 'pre_tot')
                            ->where('fch', date('Y-m-d',strtotime($request['fch'])))
                            ->get();

                    
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('fch', function($row){
                if($row->fch){
                    return (new \DateTime($row->fch))->format("d-m-Y") . PHP_EOL;
                }                   
            })
            ->addColumn('estado', function($row){
                if($row->estado==0){
                    return "<span class='badge badge-danger badge-pill'>EN ESPERA</span>";;
                }elseif($row->estado==1){
                    return "<span class='badge badge-info badge-pill'>EJECUTANDO</span>";
                }elseif($row->estado==2){
                    return "<span class='badge badge-success badge-pill'>TERMINADO</span>";
                }                   
            })                      
            ->rawColumns(['fch','estado'])              
            ->make(true);
    }

    public function pdf_servicios_dia(Request $request){      

        $data = DB::select("select
                                        s.id,
                                        s.descripcion,
                                        coalesce (tb.tot,0) as total,
                                        tb.pre_tot
                                    from servicios s 
                                    right join (
                                        select a.id_servicio,a.descripcion, count(a.id) as tot, sum(a.pre_tot) as pre_tot  from vw_orden_detalle a
                                        where a.fch = '".date('Y-m-d',strtotime($request['fch']))."'
                                        group by a.id_servicio, a.descripcion
                                    ) as tb on s.id = tb.id_servicio
                                    where s.active = 1
                                    order by s.orden ");
     
        //dd($data);
        $this->fpdf->AddPage();        
        $this->fpdf->SetFont('Helvetica','B', 15);
        $this->fpdf->Image('smartadmin/dist/img/DC_PNG.jpg',10,10,40); //imagen(archivo, png/jpg || x,y,tamaño)
        $this->fpdf->setXY(60,15);
        $this->fpdf->SetTextColor(255, 0, 0);//color de fondo rgb
        $this->fpdf->Cell(100,8,'REPORTE SERVICIOS:  '.$request['fch'],'B',1,'C',0); 
        $this->fpdf->SetFont('Helvetica','', 11);
        $this->fpdf->SetTextColor(0, 0, 0); 
        $this->fpdf->Cell(200,5,'Fecha de impresion:  '.date('d-m-Y G:ia'),'',0,'C',0);  
        $this->fpdf->SetFont('Helvetica','', 10);
       
        $this->fpdf->Ln(10);

        // Recorrer los resultados de la consulta y agregarlos a la tabla
        foreach ($data as $dat) {
            $this->fpdf->setX(30);
            $this->fpdf->SetFont('Helvetica','B', 11);
            $this->fpdf->Cell(40, 4, utf8_decode($dat->descripcion), 0, 0, 'L');
            $this->fpdf->Cell(20, 4, ':  '.number_format($dat->total), 0, 0, 'R');
            $this->fpdf->Cell(30, 4, '   S/.'.number_format($dat->pre_tot,2), 0, 0, 'R');
            $this->fpdf->Ln(6);
        }
        $this->fpdf->Ln(8);
        $data2 = DB::table('vw_orden_detalle')
                            ->select('id_servicio','descripcion','fch', 'codigo_orden','pre_tot','estado')
                            ->where('fch', date('Y-m-d',strtotime($request['fch'])))
                            ->get();

        $this->fpdf->SetMargins(10,10,10);
        $this->fpdf->SetAutoPageBreak(true,20);//salto de pagina automatico
        $this->fpdf->SetX(15);
        $this->fpdf->SetFont('Helvetica','B',11);
        
        $this->fpdf->Cell(20,8,'Cod.Serv',1,0,'C',0);
        $this->fpdf->Cell(70,8,utf8_decode('Descripción de Servicio'),1,0,'C',0);
        $this->fpdf->Cell(30,8,'Cod. Orden',1,0,'C',0);
        $this->fpdf->Cell(30,8,'Precio',1,0,'C',0);
        $this->fpdf->Cell(30,8,'Estado',1,1,'C',0);

        $this->fpdf->SetFont('Helvetica','',9);
        
        foreach($data2 as $dat2){             
            $this->fpdf->setX(15);
            $this->fpdf->Cell(20,6,$dat2->id_servicio,1,0,'C',0);
            $this->fpdf->Cell(70,6,utf8_decode($dat2->descripcion),1,0,'L',0);
            $this->fpdf->Cell(30,6,$dat2->codigo_orden,1,0,'C',0);
            $this->fpdf->Cell(30,6,number_format($dat2->pre_tot,2),1,0,'C',0);
            $this->fpdf->Cell(30,6,$dat2->estado,1,1,'C',0);
        }     
       

        // //abrir
        $this->fpdf->Output('reporte_servicios_por_dia_'.$request['fch'].'.pdf','i');
    }
}
