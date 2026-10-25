<?php

namespace App\Http\Controllers\productos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductosController extends Controller
{
    
    public function index()
    {
        $servicios =DB::table('servicios')->where('active',1)->orderBy('orden')->get();
        return view('productos.productos',compact('servicios'));
    }

    public function tabla_productos(){
        $data = DB::table('productos')->where('active',1)->orderBy('id')->get();
            
        
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){                    
                $buttons =  "<button onclick='editar_producto(".$row->id.")' class='btn btn-primary btn-sm btn-edit'  
                                title='Editar Producto'><i class='fa fa-edit'></i>
                            </button>|
                            <button onclick='eliminar_producto(".$row->id.")' class='btn btn-danger btn-sm btn-delete'  
                                    title='Eliminar Producto'><i class='fa fa-trash'></i>
                            </button>";
                return $buttons;                
            })
            ->addColumn('costo_tot', function($row){                    
                $total = DB::table('producto_detalle')->where('id_producto',$row->id)->sum('precio');
                return $total;            
            })
            ->addColumn('ruta_url', function($row){                    
                if($row->ruta_url!=""){
                    return "<button class='btn' onclick='VerPLano(\"".$row->ruta_url."\")'  style='padding: 0px 0px 0px 0px;' title='Subir Archivos'>
                                    <img src='images/autocad_logo.jpg' alt='Imagen del botón' width='32' height='30'>
                            </button>";
                }          
            })
            ->rawColumns(['action','costo_tot','ruta_url'])              
            ->make(true);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validate_file=$request->hasFile('file');
        
        if($request->id!=''){ ///Actualizar producto
            $updateData = [
                'desc'   => $request['descripcion'],
            ];

            if($validate_file==true){
                $file = $request->file('file');          
                $extension = $file[0]->getClientOriginalExtension();
                $filenameWithoutExtension = str_replace(" ", "_", $request['descripcion']);
                $newfilename = $filenameWithoutExtension.'_1.'.$extension;
                
                $filepath = '/storage/productos/' . $newfilename;
                $file1 = public_path().'/storage/productos/' . $filenameWithoutExtension.'_1.pdf';
                $file2 = public_path().'/storage/productos/' . $filenameWithoutExtension.'_1.dwg';

                if(File::exists($file1) || File::exists($file2)){
                    File::delete($file1);
                    File::delete($file2);
                    //echo 'Archivo eliminado';
                }else{
                    //echo 'Archivo no existe';
                }

                if($extension=='pdf' || $extension=='dwg'){                
                    $file[0]->move('storage/productos',$newfilename);
                    $updateData+=['ruta_url' => $filepath];              
                }else{
                    return response()->json(['msg'=>'Solo archivos pdf, dwg'], 500);
                }
            } 

            $id = DB::table('productos')->where('id',$request->id)->update($updateData);

            if($id){
                return response()->json(['msg'=>'Actualizacion realizada...','id'=>$id], 200);
            }else{
                return response()->json(['msg'=>'Error en base de datos...'], 500);
            }

        }else{/////Nuevo producto
           
            $insertData = [
                'desc'          => $request['descripcion'],
                'fch_create'    => date('d-m-Y'),
            ];

            if($validate_file==true){
                $file = $request->file('file');          
                $extension = $file[0]->getClientOriginalExtension();
                $filenameWithoutExtension = str_replace(" ", "_", $request['descripcion']);
                $newfilename = $filenameWithoutExtension.'_1.'.$extension;
                
                $filepath = '/storage/productos/' . $newfilename;
                $file1 = public_path().'/storage/productos/' . $filenameWithoutExtension.'_1.pdf';
                $file2 = public_path().'/storage/productos/' . $filenameWithoutExtension.'_1.dwg';

                if(File::exists($file1) || File::exists($file2)){
                    File::delete($file1);
                    File::delete($file2);
                    //echo 'Archivo eliminado';
                }else{
                    //echo 'Archivo no existe';
                }

                if($extension=='pdf' || $extension=='dwg'){                
                    $file[0]->move('storage/productos',$newfilename);
                    $insertData+=['ruta_url' => $filepath];              
                }else{
                    return response()->json(['msg'=>'Solo archivos pdf, dwg'], 500);
                }
            } 
                    
            $id=DB::table('productos')->insertGetId($insertData);
                      
            if($id){
                return response()->json(['msg'=>'Producto agregado...','id'=>$id], 200);
            }else{
                return response()->json(['msg'=>'Error en base de datos...'], 500);
            }
            
        }

        
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        $datos = DB::table('productos')->where('id',$id)->get();
      
        if($datos){
            return response()->json($datos[0],200);
        }else{
            return response()->json(['msg'=>'Error al obtener datos'], 500);
        }
    }

    
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        $ruta_url = DB::table('productos')->where('id',$id)->get();
        $file = public_path().$ruta_url[0]->ruta_url;

        if(File::exists($file)){            
            File::delete($file);            
        }

        //$id = DB::table('productos')->where('id',$id)->delete();
        $id = DB::table('productos')->where('id',$id)->update([
            'active'   => 0,
        ]);
        
        if($id){
            return response()->json([ 'msg' => 'Registro eliminado con éxito!'],200);
        }else{
            return response()->json(['msg'=>'Error al eliminar registro'], 500);
        }
    }

    public function autocompletar_orden_producto($id_producto,$id_orden){
        
        $ruta_url = DB::table('productos')->where('id',$id_producto)->get();
        $producto_detalle = DB::table('producto_detalle')->where('id_producto',$id_producto)->get();

        DB::table('orden')->where('id',$id_orden)->update(['ruta_url'=>$ruta_url[0]->ruta_url]);
       
        foreach ($producto_detalle as $detalle) {
            $Data = [
                'id_orden'      => $id_orden,
                'cant'          => 1,
                'id_servicio'   => $detalle->id_serv,
                'orden'         => "NO",
                'numeracion'    => $detalle->orden,
                'precio_unid'   => number_format((float) $detalle->precio, 2, '.', ''),
                'pre_tot'       => number_format((float) $detalle->precio, 2, '.', ''),
                'fch'           => date('Y-m-d')
            ];
        
            if ($detalle->id_serv==29) { //materiaL
                $Data += ['estado' => 2];
            }            

            $id_detalle = DB::table('orden_detalle')->insertGetId($Data);
          
            if($id_detalle){// derivar automatico

                $derivar_auto=DB::table('servicios')->where('id',$detalle->id_serv)->select('id_operador')->get();

                if($derivar_auto[0]->id_operador>0){
                    DB::table('orden_detalle')->where('id',$id_detalle)->update([
                        'fch_ent'       => date('Y-m-d'), 
                        'hora_ent'      => date('H:i'),
                        'id_operario'   => $derivar_auto[0]->id_operador,
                    ]);
            
                    $id_lista_trab = DB::table('lista_trabajo')->insert([                      
                        'id_orden_det'  => $id_detalle,
                        'id_operador'   => $derivar_auto[0]->id_operador,
                        'fch_ent'       => date('Y-m-d'), 
                        'hora_ent'      => date('H:i'), 
                        'id_servicio'   => $detalle->id_serv,
                        'id_orden'      => $id_orden,
                        'id_tiempo_ref' => 3,//45min id_horas
                        'tiempo_ref'    => '00h:45m',
                        'llave'         => 1
                    ]);
                }
            }
        }
       
        if($producto_detalle){
            return response()->json($ruta_url,200);
        }else{
            return response()->json(['msg'=>'Error al obtener datos'], 500);
        }
    }

    public function autocompletar_orden_detalle_producto($id_orden){
        echo $id_orden;
    }

    public function autocompletar($query){

        $Consulta = DB::table('productos')->where('desc','like','%'.strtoupper($query).'%')->where('active',1)->get();
        
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->id = $Datos->id;
            $Lista->label = trim($Datos->desc);
            
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
}
