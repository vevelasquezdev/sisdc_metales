<?php

namespace App\Http\Controllers\reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;

class ReportOrdenes extends Controller
{
    protected $fpdf;
    public function __construct()
    {
        $this->fpdf = new Fpdf();
    }

    public function index(){
        return view('reportes.reporte_ordenes');
    }

    public function tabla_report_ordenes(Request $request){
               
         $from_date = $request->filled('from_date') ? (new \DateTime($request['from_date']))->format("Y-m-d") : null;
            $to_date   = $request->filled('to_date')   ? (new \DateTime($request['to_date']))->format("Y-m-d")   : null;
            $alm       = $request->filled('alm')       ? $request['alm'] : null;

            // Obtener servicios activos desde la BD
            $servicios = DB::table('servicios')
                ->where('active', 1)
                ->orderBy('orden')  // respeta el orden definido en la tabla
                ->get(['id', 'descripcion']);

            // IDs que contienen "MATERIAL" en su descripción se excluyen del cálculo servicio
            $ids_material = $servicios
                ->filter(fn($s) => stripos($s->descripcion, 'MATERIAL') !== false)
                ->pluck('id')
                ->toArray();

            $ids_activos      = $servicios->pluck('id')->toArray();
            $ids_sin_material = $servicios
                ->whereNotIn('id', $ids_material)
                ->pluck('id')
                ->toArray();

            // Construir columnas pivot dinámicamente
            $columnas_pivot = $servicios->map(function ($servicio) {
                return DB::raw("COALESCE(SUM(CASE WHEN od.id_servicio = {$servicio->id} THEN od.pre_tot ELSE 0 END), 0) AS \"{$servicio->descripcion}\"");
            })->toArray();

            $ids_activos_str      = implode(',', $ids_activos);
            $ids_sin_material_str = implode(',', $ids_sin_material);

            $data = DB::table('orden as o')
                ->leftJoin('cliente as c', 'o.id_cliente', '=', 'c.id')
                ->leftJoin('users as u', 'o.id_user', '=', 'u.id')
                ->leftJoin('orden_detalle as od', 'o.id', '=', 'od.id_orden')
                ->whereNotNull('o.id_cliente')
                ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                    return $query->whereBetween('o.fch_create', [$from_date, $to_date]);
                })
                ->when($alm, function ($query) use ($alm) {
                    return $query->where('o.procedencia', $alm);
                })
                ->groupBy('o.id', 'c.name', 'c.surname', 'o.fch_create', 'o.procedencia', 'o.pago_caja', 'u.name', 'u.surname')
                ->orderByDesc('o.id')
                ->select(array_merge(
                    [
                        'o.id as id_orden',
                        DB::raw("CONCAT(c.name, ' ', c.surname) AS cliente"),
                        'o.fch_create as fecha_creacion',
                        'o.procedencia',
                    ],
                    $columnas_pivot,
                    [
                        DB::raw("COALESCE(SUM(CASE WHEN od.id_servicio IN ({$ids_activos_str}) THEN od.pre_tot ELSE 0 END), 0) AS total"),
                        DB::raw("COALESCE(SUM(CASE WHEN od.id_servicio IN ({$ids_sin_material_str}) THEN od.pre_tot ELSE 0 END), 0) AS servicio"),
                        DB::raw("CASE WHEN o.pago_caja = 1 THEN 'Cancelado' WHEN o.pago_caja = 0 THEN 'Por Cancelar' ELSE 'Desconocido' END AS pagado"),
                        DB::raw("CONCAT(u.name, ' ', u.surname) AS usuario"),
                    ]
                ))
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('fecha_creacion', function ($row) {
                    if ($row->fecha_creacion) {
                        return (new \DateTime($row->fecha_creacion))->format("d-m-Y");
                    }
                })
                ->rawColumns(['fecha_creacion'])
                ->make(true);
        
               
        // $from_date = $request->filled('from_date') ? (new \DateTime($request['from_date']))->format("Y-m-d") : null;
        // $to_date = $request->filled('to_date') ? (new \DateTime($request['to_date']))->format("Y-m-d") : null;
        // $alm = $request->filled('alm') ? $request['alm'] : null;
        
        // $data = DB::table('vw_report_ordenes')
        //     ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
        //         return $query->whereBetween('fecha_creacion', [$from_date, $to_date]);
        //     })
        //     ->when($alm, function ($query) use ($alm) {
        //         return $query->where('procedencia', $alm);
        //     })           
        //     ->latest('id_orden')
        //     ->get();        

        // return Datatables::of($data)
        //     ->addIndexColumn()
        //     ->addColumn('fecha_creacion', function($row){
        //         if($row->fecha_creacion){
        //             return (new \DateTime($row->fecha_creacion))->format("d-m-Y");
        //         }                   
        //     })                           
        //     ->rawColumns(['fecha_creacion'])              
        //     ->make(true);
    }
}
