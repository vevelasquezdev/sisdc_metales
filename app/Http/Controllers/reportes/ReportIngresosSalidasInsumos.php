<?php

namespace App\Http\Controllers\reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;

class ReportIngresosSalidasInsumos extends Controller
{
    protected $fpdf;
    public function __construct()
    {
        $this->fpdf = new Fpdf();
    }

    public function index(){
        return view('reportes.reporte_ingresos_salidas');
    }

    public function tabla_ingresos_salidas_por_orden(Request $request){
        $from_date = $request->filled('from_date') ? (new \DateTime($request['from_date']))->format("Y-m-d") : null;
        $to_date = $request->filled('to_date') ? (new \DateTime($request['to_date']))->format("Y-m-d") : null;
        $alm = $request['alm'];
       
        $data = DB::table('vw_ingresos_salidas_insumos')
                        ->select(
                            'id_insumo',
                            'id_almacen',
                            'almacen',
                            'insumo',
                            'id_tipo',
                            DB::raw("
                                CASE 
                                    WHEN id_movimiento IS NOT NULL THEN 'MOVIMIENTO'
                                    WHEN id_tipo = 0 THEN 'SALIDA'
                                    WHEN id_tipo = 1 THEN 'INGRESO'
                                    ELSE NULL
                                END as tipo
                            "),
                            'cant',
                            DB::raw("
                                CASE 
                                    WHEN id_movimiento IS NOT NULL THEN NULL
                                    WHEN id_tipo = 1 THEN pre_compra
                                    ELSE pre_compra
                                END as pre_compra
                            "),
                            DB::raw("
                                CASE 
                                    WHEN id_movimiento IS NOT NULL THEN NULL
                                    WHEN id_tipo = 1 THEN NULL
                                    ELSE pre_venta
                                END as pre_venta
                            "),
                            'fch',
                            'id_orden'
                        )
                        ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                            return $query->whereBetween('fch', [$from_date, $to_date]);
                        })
                        ->when($alm != 0, function ($query) use ($alm) {
                            return $query->where('id_almacen', '=', $alm);
                        })
                        ->orderByDesc('fch')->orderBy('id')
                        ->get();

                   
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('fch', function($row){
                if($row->fch){
                    return (new \DateTime($row->fch))->format("d-m-Y");
                }                   
            })
            ->addColumn('subtotal', function($row){
                if($row->pre_compra){
                    return number_format($row->pre_compra * $row->cant, 2);                    
                }else if($row->pre_venta){
                    return number_format($row->pre_venta * $row->cant, 2);
                }else{
                    return null;
                }              
            })                           
            ->rawColumns(['fch'])              
            ->make(true);

    }

    public function pdf_ingresos_salidas(Request $request) {
        $from_date = $request->filled('from_date') ? (new \DateTime($request['from_date']))->format("Y-m-d") : null;
        $to_date = $request->filled('to_date') ? (new \DateTime($request['to_date']))->format("Y-m-d") : null;
        $alm = $request['alm'];

        $data = DB::table('vw_ingresos_salidas_insumos')
                ->select(
                    'id_insumo',
                    'id_almacen',
                    'almacen',
                    'insumo',
                    'id_tipo',
                    DB::raw("
                        CASE 
                            WHEN id_movimiento IS NOT NULL THEN 'MOVIMIENTO'
                            WHEN id_tipo = 0 THEN 'SALIDA'
                            WHEN id_tipo = 1 THEN 'INGRESO'
                            ELSE NULL
                        END as tipo
                    "),
                    'cant',
                    DB::raw("
                        CASE 
                            WHEN id_movimiento IS NOT NULL THEN NULL
                            WHEN id_tipo = 1 THEN pre_compra
                            ELSE pre_compra
                        END as pre_compra
                    "),
                    DB::raw("
                        CASE 
                            WHEN id_movimiento IS NOT NULL THEN NULL
                            WHEN id_tipo = 1 THEN NULL
                            ELSE pre_venta
                        END as pre_venta
                    "),
                    'fch',
                    'id_orden'
                )
                ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                    return $query->whereBetween('fch', [$from_date, $to_date]);
                })
                ->when($alm != 0, function ($query) use ($alm) {
                    return $query->where('id_almacen', '=', $alm);
                })
                ->orderByDesc('fch')->orderBy('id')
                ->get();
    
      
        // Calcular totales
        $total_ingresos = $data->where('id_tipo', 1)->sum(function ($registro) {
            return $registro->pre_compra * $registro->cant;
        });
        $total_salidas = $data->where('id_tipo', 0)->sum(function ($registro) {
            return $registro->pre_venta * $registro->cant;
        });
    
        // Crear el PDF
        $this->fpdf->AddPage('L'); // Configurar en horizontal (landscape)
        $this->fpdf->SetFont('Helvetica', 'B', 15);
        $this->fpdf->Image('smartadmin/dist/img/DC_PNG.jpg', 10, 10, 40);
        $this->fpdf->setXY(60, 15);
        $this->fpdf->SetTextColor(255, 0, 0);
        $this->fpdf->Cell(175, 8, 'REPORTE INGRESOS Y SALIDAS ('.$request['from_date'].' a '.$request['to_date'].')', 'B', 1, 'C', 0);
        $this->fpdf->SetFont('Helvetica', '', 11);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(275, 5, 'Fecha de impresion: ' . date('d-m-Y G:ia'), '', 0, 'C', 0);
        $this->fpdf->SetFont('Helvetica', '', 10);
        $this->fpdf->Ln(10);
    
        // Mostrar totales
        $this->fpdf->SetFont('Helvetica', 'B', 12);
        $this->fpdf->Cell(80, 8, 'Total Ingresos:', 0, 0, 'R');
        $this->fpdf->Cell(20, 8, 'S/. ' . number_format($total_ingresos, 2), 0, 1, 'L');
    
        $this->fpdf->Cell(80, 8, 'Total Salidas  :', 0, 0, 'R');
        $this->fpdf->Cell(20, 8, 'S/. ' . number_format($total_salidas, 2), 0, 1, 'L');
        $this->fpdf->Cell(80, 8, 'Diferencia      :', 0, 0, 'R');
        $this->fpdf->Cell(20, 8, 'S/. ' . number_format($total_ingresos - abs($total_salidas), 2), 0, 1, 'L');
    
        // Agregar encabezados para los registros
        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Helvetica', 'B', 10);  
        $this->fpdf->Cell(25, 8, 'Tipo', 1, 0, 'C');      
        $this->fpdf->Cell(80, 8, 'Insumo', 1, 0, 'C');
        $this->fpdf->Cell(20, 8, 'Cant.', 1, 0, 'C');        
        $this->fpdf->Cell(30, 8, 'P.Compra(Und)', 1, 0, 'C');
        $this->fpdf->Cell(30, 8, 'P.Venta(Und)', 1, 0, 'C');
        $this->fpdf->Cell(30, 8, 'SubTotal', 1, 0, 'C');
        $this->fpdf->Cell(30, 8, 'Cod. Orden', 1, 0, 'C');
        $this->fpdf->Cell(30, 8, 'Fecha', 1, 1, 'C');
    
        // Recorrer los registros y agregarlos al PDF
        $this->fpdf->SetFont('Helvetica', '', 9);
        foreach ($data as $registro) {   
            $this->fpdf->Cell(25, 8, $registro->tipo, 1, 0, 'C');         
            $this->fpdf->Cell(80, 8, $registro->insumo, 1, 0, 'L');
            $this->fpdf->Cell(20, 8, $registro->cant, 1, 0, 'C');           
            $this->fpdf->Cell(30, 8, number_format($registro->pre_compra, 2), 1, 0, 'C');
            $this->fpdf->Cell(30, 8, number_format($registro->pre_venta, 2), 1, 0, 'C');
            if($registro->pre_compra){
                $this->fpdf->Cell(30, 8, number_format($registro->pre_compra * $registro->cant, 2), 1, 0, 'C');
            }else{
                $this->fpdf->Cell(30, 8, number_format($registro->pre_venta * $registro->cant, 2), 1, 0, 'C');
            }
            
            $this->fpdf->Cell(30, 8, $registro->id_orden, 1, 0, 'C');
            $this->fpdf->Cell(30, 8, $registro->fch ? (new \DateTime($registro->fch))->format("d-m-Y") : null, 1, 1, 'C');
        }
    
        // Generar el PDF
        $this->fpdf->Output('reporte_ingresos_salidas.pdf', 'I');
    }
    

    // public function pdf_ingresos_salidas(Request $request) {
    //     $from_date = $request->filled('from_date') ? (new \DateTime($request['from_date']))->format("Y-m-d") : null;
    //     $to_date = $request->filled('to_date') ? (new \DateTime($request['to_date']))->format("Y-m-d") : null;
    //     $alm = $request['alm'];
    
    //     // Obtener datos desde la base de datos
    //     $data = DB::table('vw_ingresos_salidas_insumos')
    //         ->select(
    //             'id_insumo',
    //             'id_almacen',
    //             'almacen',
    //             'insumo',
    //             'id_tipo',
    //             DB::raw("
    //                 CASE 
    //                     WHEN id_movimiento IS NOT NULL THEN 'MOVIMIENTO'
    //                     WHEN id_tipo = 0 THEN 'SALIDA'
    //                     WHEN id_tipo = 1 THEN 'INGRESO'
    //                     ELSE NULL
    //                 END as tipo
    //             "),
    //             'cant',
    //             DB::raw("
    //                 CASE 
    //                     WHEN id_tipo = 1 THEN pre_compra
    //                     WHEN id_movimiento IS NULL THEN pre_compra 
    //                     ELSE 0.00 
    //                 END as pre_compra
    //             "),
    //             DB::raw("
    //                 CASE 
    //                     WHEN id_tipo = 1 THEN NULL
    //                     WHEN id_movimiento IS NULL THEN pre_venta 
    //                     ELSE 0.00 
    //                 END as pre_venta
    //             "),
    //             'fch',
    //             'id_orden'
    //         )
    //         ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
    //             return $query->whereBetween('fch', [$from_date, $to_date]);
    //         })
    //         ->when($alm != 0, function ($query) use ($alm) {
    //             return $query->where('id_almacen', '=', $alm);
    //         })
    //         ->orderByDesc('fch')->orderBy('id')
    //         ->get();
    
    //     // Calcular totales
    //     $total_ingresos = $data->where('id_tipo', 1)->sum('pre_compra');
    //     $total_salidas = $data->where('id_tipo', 0)->sum('pre_venta');

       
        
    
    //     // Crear el PDF
    //     $this->fpdf->AddPage('L'); // Configurar en horizontal (landscape)
    //     $this->fpdf->SetFont('Helvetica', 'B', 15);
    //     $this->fpdf->Image('smartadmin/dist/img/DC_PNG.jpg', 10, 10, 40);
    //     $this->fpdf->setXY(60, 15);
    //     $this->fpdf->SetTextColor(255, 0, 0);
    //     $this->fpdf->Cell(175, 8, 'REPORTE INGRESOS Y SALIDAS ('.$request['from_date'].' a '.$request['to_date'].')', 'B', 1, 'C', 0);
    //     $this->fpdf->SetFont('Helvetica', '', 11);
    //     $this->fpdf->SetTextColor(0, 0, 0);
    //     $this->fpdf->Cell(275, 5, 'Fecha de impresion: ' . date('d-m-Y G:ia'), '', 0, 'C', 0);
    //     $this->fpdf->SetFont('Helvetica', '', 10);
    //     $this->fpdf->Ln(10);
    
    //     // Mostrar totales
    //     $this->fpdf->SetFont('Helvetica', 'B', 12);
    //     $this->fpdf->Cell(80, 8, 'Total Ingresos:', 0, 0, 'R');
    //     $this->fpdf->Cell(20, 8, 'S/. ' . number_format($total_ingresos, 2), 0, 1, 'L');
    
    //     $this->fpdf->Cell(80, 8, 'Total Salidas  :', 0, 0, 'R');
    //     $this->fpdf->Cell(20, 8, 'S/. ' . number_format($total_salidas, 2), 0, 1, 'L');
    //     $this->fpdf->Cell(80, 8, 'Diferencia      :', 0, 0, 'R');
    //     $this->fpdf->Cell(20, 8, 'S/. ' . number_format($total_ingresos-$total_salidas, 2), 0, 1, 'L');
    
    //     // Agregar encabezados para los registros
    //     $this->fpdf->Ln(10);
    //     $this->fpdf->SetFont('Helvetica', 'B', 10);
    //     $this->fpdf->Cell(20, 8, 'Cod.', 1, 0, 'C');
    //     $this->fpdf->Cell(80, 8, 'Insumo', 1, 0, 'C');
    //     $this->fpdf->Cell(20, 8, 'Cant.', 1, 0, 'C');
    //     $this->fpdf->Cell(25, 8, 'Tipo', 1, 0, 'C');
    //     $this->fpdf->Cell(30, 8, 'P. Compra', 1, 0, 'C');
    //     $this->fpdf->Cell(30, 8, 'P. Venta', 1, 0, 'C');
    //     $this->fpdf->Cell(30, 8, 'Cod. Orden', 1, 0, 'C');
    //     $this->fpdf->Cell(30, 8, 'Fecha', 1, 1, 'C');
    
    //     // Recorrer los registros y agregarlos al PDF
    //     $this->fpdf->SetFont('Helvetica', '', 9);
    //     foreach ($data as $registro) {
    //         $this->fpdf->Cell(20, 8, $registro->id_insumo, 1, 0, 'C');
    //         $this->fpdf->Cell(80, 8, $registro->insumo, 1, 0, 'L');
    //         $this->fpdf->Cell(20, 8, $registro->cant, 1, 0, 'C');
    //         $this->fpdf->Cell(25, 8, $registro->tipo, 1, 0, 'C');
    //         $this->fpdf->Cell(30, 8, number_format($registro->pre_compra*$registro->cant, 2), 1, 0, 'C');
    //         $this->fpdf->Cell(30, 8, number_format($registro->pre_venta*$registro->cant, 2), 1, 0, 'C');
    //         $this->fpdf->Cell(30, 8, $registro->id_orden, 1, 0, 'C');
    //         $this->fpdf->Cell(30, 8, $registro->fch? (new \DateTime($registro->fch))->format("d-m-Y") : null, 1, 1, 'C');
    //     }
    
    //     // Generar el PDF
    //     $this->fpdf->Output('reporte_ingresos_salidas.pdf', 'I');
    // }
}    
