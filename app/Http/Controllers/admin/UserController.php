<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
   
    public function index(Request $request) {
        
        if ($request->ajax()) {
            $data = User::latest('id')->where('active',1);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){                    
                    return "
                    <button onclick='getdatauser(".$row->id.")' class='btn btn-primary btn-sm' data-toggle='modal' 
                        data-target='.default-example-modal-right'>Editar
                    </button>|
                    <button onclick='delete_user(".$row->id.")' class='btn btn-danger btn-sm'>Eliminar</button>                                         
                    ";                     
                })                
                ->addColumn('profile_photo_path', function($row){
                    if($row->profile_photo_path=="" || $row->profile_photo_path==Null){
                         return '<div class="profile-image-md rounded-circle" style="background-image:url('.$row->profile_photo_url.'); background-size: cover; margin-left:8px">                    
                            </div>'; 
                    }else{
                        return '<div class="profile-image-md rounded-circle" style="background-image:url(\''.App::make('url')->to('/storage/').$row->profile_photo_path.'\'); background-size: cover; margin-left:8px">                    
                                </div>';
                    }
                                        
                })
                ->rawColumns(['action', 'profile_photo_path'])              
                ->make(true);
        }
        
        return view('admin.usuario');
    }

    public function get_data_user(Request $request) {
        
        $datos = User::find($request['id']);
        if($datos){
            return response()->json($datos);
        }else{
            return response()->json(['msg'=>'Error'], 500);
        }
        
    }


   

    public function store(Request $request){

        // VALIDACIÓN PRIMERO
        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($request->id)
            ],
            'rol' => 'required',
            'fch_nac' => 'required',
            'telefono' => 'required',
        ];

        if (!$request->id) {
            $rules['password'] = 'required';
        }
       
        if ($request->hasFile('image')) {
            $rules['image'] = 'image|mimes:jpg,jpeg,png|max:2048';        
        }

        $request->validate($rules);

        // CREAR O ACTUALIZAR
        if ($request->id) {
            $user = User::find($request->id);
            $oldImage = $user->profile_photo_path ?? null;            
        } else {
            $user = new User();
            $oldImage = null;
        }
       
        $currentURL = null;

        if ($request->hasFile('file')) {
          
            // eliminar imagen anterior
            if ($oldImage) {
                $filename = basename($oldImage);
                $oldPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/users/' . $filename;
               
                if (file_exists($oldPath)) {                   
                    unlink($oldPath);
                }
                
            }
          

            
            $files = $request->file('file');                  
            $extension = $files[0]->getClientOriginalExtension();
            $newfilename = Str::uuid() . '.' . $extension;            
            // Mover el archivo            
            $files[0]->move('storage/users/',$newfilename); 
            $currentURL = '/users/' . $newfilename;
           
        }
      

        // ASIGNAR DATOS
        $user->name = strtoupper($request->name);
        $user->surname = strtoupper($request->surname);
        $user->email = $request->email;
        $user->rol = $request->rol;
        $user->fch_nac = $request->fch_nac;
        $user->telefono = $request->telefono;
        $user->active = 1;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
       
        if ($currentURL) {
            $user->profile_photo_path = $currentURL;
        }
        
    
        if ($user->save()) {           
            return response()->json([
                'msg' => $request->id ? 'Registro actualizado con éxito!' : 'Registro creado con éxito!'
            ]);
        } else {
            return response()->json(['msg' => 'Error al guardar'], 500);
        }  
        
    
    }

    
    public function edit(Request $request, $id){

        try{
            $datos = User::find($id);
        }catch(\Exception $exception){
            return view('errors.404');
        }
       
        if($datos){
            return response()->json($datos);
        }else{
            return response()->json(['msg'=>'Error al obtener datos'], 500);
        }
    }

       
    public function destroy($id){

        $user = User::find($id);
        $user->active = 0;
        
        if($user->save()){
            return response()->json([ 'msg' => 'Registro eliminado con éxito!']);
        }else{
            return response()->json(['msg'=>'Error al eliminar registro'], 500);
        }
       
    }


    public function exportExcelUsers(){
        return Excel::download(new UsersExport, 'lista de usuarios.xlsx');
    }

    public function importExcelUsers(Request $request){
        $file = $request->file('file');
        Excel::import(new UsersImport, $file);
        return back()->with('message', 'importacion de usuarios completada...');
    }
}

