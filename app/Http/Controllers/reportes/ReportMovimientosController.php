<?php

namespace App\Http\Controllers\reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Codedge\Fpdf\Fpdf\Fpdf;

class ReportMovimientosController extends Controller
{
    protected $fpdf;
    public function __construct()
    {
        $this->fpdf = new Fpdf();
    }


    public function vw_reporte_movimientos(){
        return view('reportes.reporte_movimientos');
    }

    public function reporte_movimientos(Request $request){
        if($request['caja']==0){
            if($request['tipo']==0){
                $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                    ->latest('id')
                    ->get();
            }elseif($request['tipo']==1){
                $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                    ->where('monto','>',0)
                    ->latest('id')
                    ->get();
            }elseif($request['tipo']==2){
                $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                    ->where('monto','<',0)
                    ->latest('id')
                    ->get();
            }
            
        }elseif($request['caja']==1){
            if($request['tipo']==0){
                $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                    ->where('caja',1)
                    ->latest('id')
                    ->get();
            }elseif($request['tipo']==1){
                $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                    ->where('caja',1)
                    ->where('monto','>',0)
                    ->latest('id')
                    ->get();
            }elseif($request['tipo']==2){
                $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                    ->where('caja',1)
                    ->where('monto','<',0)
                    ->latest('id')
                    ->get();
            }
            
        }elseif($request['caja']==2){
            if($request['tipo']==0){
                $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                    ->where('caja',2)
                    ->latest('id')
                    ->get();
            }elseif($request['tipo']==1){
                $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                    ->where('caja',2)
                    ->where('monto','>',0)
                    ->latest('id')
                    ->get();
            }elseif($request['tipo']==2){
                $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                    ->where('caja',2)
                    ->where('monto','<',0)
                    ->latest('id')
                    ->get();
            }
            
        }elseif($request['caja']==3){
            if($request['tipo']==0){
                $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                    ->where('caja',3)
                    ->latest('id')
                    ->get();
            }elseif($request['tipo']==1){
                $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                    ->where('caja',3)
                    ->where('monto','>',0)
                    ->latest('id')
                    ->get();
            }elseif($request['tipo']==2){
                $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                    ->where('caja',3)
                    ->where('monto','<',0)
                    ->latest('id')
                    ->get();
            }
        }

        
        
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('fch_emi', function($row){
                if($row->fch_emi){
                    return (new \DateTime($row->fch_emi))->format("j F Y - G:ia") . PHP_EOL;
                }                   
            })
            ->addColumn('caja', function($row){
                if($row->caja==1){
                    return "CAJA_1";
                }elseif($row->caja==2){
                    return "CAJA_2";
                }elseif($row->caja==3){
                    return "CAJA_3";
                }                   
            })               
            ->rawColumns(['estado', 'fch_emi','caja'])              
            ->make(true);
    }

    public function reporte_movimientos_pdf(Request $request){
        if($request['caja']==0){
            $t_efectivo=DB::table('vw_movimientos')->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))->sum('efectivo');
            $t_tarjeta=DB::table('vw_movimientos')->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))->sum('tarjeta');
            $t_yape=DB::table('vw_movimientos')->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))->sum('yape');
            $t_transferencia=DB::table('vw_movimientos')->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))->sum('transferencia');
            $t_credito=DB::table('vw_movimientos')->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))->sum('credito');
            $data = DB::table('vw_movimientos')
                    ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))                    
                    ->latest('id')
                    ->get();
            $t_total=DB::table('vw_movimientos')->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))->sum('monto');
        }else{
            $t_efectivo=DB::table('vw_movimientos')->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))->where('caja',$request['caja'])->sum('efectivo');
            $t_tarjeta=DB::table('vw_movimientos')->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))->where('caja',$request['caja'])->sum('tarjeta');
            $t_yape=DB::table('vw_movimientos')->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))->where('caja',$request['caja'])->sum('yape');
            $t_transferencia=DB::table('vw_movimientos')->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))->where('caja',$request['caja'])->sum('transferencia');
            $t_credito=DB::table('vw_movimientos')->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))->where('caja',$request['caja'])->sum('credito');
            $data = DB::table('vw_movimientos')
                ->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))
                ->where('caja',$request['caja'])
                ->latest('id')
                ->get();
            $t_total=DB::table('vw_movimientos')->whereDate('fecha_eje', date('Y-m-d',strtotime($request['fch'])))->where('caja',$request['caja'])->sum('monto');
        }
        
        //echo 'efectivo: '.$t_efectivo.'<br>'.'tarjeta: '.$t_tarjeta.'<br>'.'t_yape: '.$t_yape.'<br>'.'t_transferencia: '.$t_transferencia.'<br>'.'t_credito: '.$t_credito.'<br>';
        $this->fpdf->AddPage();        
        $this->fpdf->SetFont('Helvetica','B', 15);
        $this->fpdf->Image('smartadmin/dist/img/DC_PNG.jpg',10,10,40); //imagen(archivo, png/jpg || x,y,tamaño)
        $this->fpdf->setXY(60,15);
        $this->fpdf->SetTextColor(255, 0, 0);//color de fondo rgb
        $this->fpdf->Cell(100,8,'REPORTE CAJA:  '.$request['fch'],'B',1,'C',0); 
        $this->fpdf->SetFont('Helvetica','', 11);
        $this->fpdf->SetTextColor(0, 0, 0); 
        $this->fpdf->Cell(200,5,'Fecha de impresion:  '.date('d-m-Y G:ia'),'',0,'C',0);  
        
       
        $this->fpdf->SetTextColor(64, 64, 64);//color de fondo rgb
        $this->fpdf->setXY(50,35);
        $this->fpdf->SetFont('Helvetica','B', 11);
        if($request['caja']==0){
            $this->fpdf->Cell(60,4,'Caja                : ' .'Todas',0,1,'');
        }else{
            $this->fpdf->Cell(60,4,'Caja                : ' .$request['caja'],0,1,'');
        }

        $this->fpdf->setXY(110,35);
        $this->fpdf->Cell(60,4,'Total: S/.' .$t_total,0,1,'');
        
        $this->fpdf->setXY(50,40);
        $this->fpdf->SetFont('Helvetica','B', 11);
        $this->fpdf->Cell(60,4,'Efectivo          :  S/.' .$t_efectivo,0,1,'');
        $this->fpdf->setXY(50,45);
        $this->fpdf->Cell(60,4,'Tarjeta            :  S/.' .$t_tarjeta,0,1,'');
        $this->fpdf->setXY(50,50);
        $this->fpdf->Cell(60,4,'Yape               :  S/.' .$t_yape,0,1,'');
        $this->fpdf->setXY(50,55);
        $this->fpdf->Cell(60,4,'Transferencia:  S/.' .$t_transferencia,0,1,'');
        $this->fpdf->setXY(50,60);
        $this->fpdf->Cell(60,4,'Credito           :  S/.' .$t_credito,0,1,'');
        $this->fpdf->Ln(5);

        $this->fpdf->SetMargins(10,10,10);
        $this->fpdf->SetAutoPageBreak(true,20);//salto de pagina automatico
        $this->fpdf->SetX(15);
        $this->fpdf->SetFont('Helvetica','B',11);
        // $this->fpdf->Cell(10,8,'Caja','B',0,'C',0);
        $this->fpdf->Cell(30,8,'Forma Pago',1,0,'C',0);
        $this->fpdf->Cell(50,8,'Razon social',1,0,'C',0);
        $this->fpdf->Cell(80,8,utf8_decode('Descripción'),1,0,'C',0);
        $this->fpdf->Cell(20,8,'Monto',1,1,'C',0);

        $this->fpdf->SetFont('Helvetica','',9);
       
        foreach($data as $dat){ 
            
            $this->fpdf->setX(15);
            $this->fpdf->Cell(30,6,$dat->desc_forma_pago,1,0,'L',0);
            $this->fpdf->Cell(50,6,$dat->razon_social,1,0,'L',0);
            $this->fpdf->Cell(80,6,$dat->descripcion,1,0,'L',0);
            $this->fpdf->Cell(20,6,$dat->monto,1,1,'R',0);
        }

        //abrir
        $this->fpdf->Output('reporte_caja_'.$request['fch'].'.pdf','i');

    
    }
}
