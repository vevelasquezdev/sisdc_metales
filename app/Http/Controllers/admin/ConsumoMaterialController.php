<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class ConsumoMaterialController extends Controller
{
    
    public function index()
    {
        //
    }

    
    public function create(Request $request){
        $id_consumo_material = DB::table('consumo_material')->insertGetId([
            "largo_cm"          => $request['largo'],
            "ancho_cm"          => $request['ancho'],
            "espesor_mm"        => $request['espesor'],
            "id_tipo_mat"       => $request['id_tip_mat'],
            "id_orden"          => $request['id_orden'],
            "id_orden_det"      => $request['id_orden_det'],
            "cant"              => $request['cant'],
            "id_servicio"       => $request['id_servicio'],            
            'precio_unid'       => number_format((float) $request['precio_unid'], 2, '.', '')            
        ]);
    

        if($id_consumo_material){
            return response()->json(['msg'=>'Material agregado...','id_consumo_material'=>$id_consumo_material], 200);
        }else{
            return response()->json(['msg'=>'Error de base de datos'], 500);
        }
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id){
        $getmaterial = DB::table('consumo_material')->where('id_orden_det',$id)->get();
        return response()->json($getmaterial[0], 200);
    }

    
    public function update(Request $request, $id){
        $edit_material = DB::table('consumo_material')->where('id_orden_det',$id)->update([
            "largo_cm"          => $request['largo'],
            "ancho_cm"          => $request['ancho'],
            "espesor_mm"        => $request['espesor'],
            "id_tipo_mat"       => $request['id_tip_mat'],
            "cant"              => $request['cant'],                      
            'precio_unid'       => number_format((float) $request['precio_unid'], 2, '.', '')            
        ]);

        if($edit_material){
            return response()->json(['msg'=>'Material modificado...','id'=>$id], 200);
        }else{
            return response()->json(['msg'=>'Error de base de datos'], 500);
        }
    }

    
    public function destroy($id)
    {
        //
    }
}
