<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class OperariosController extends Controller
{
    
    public function index()
    {
        return view('admin.operarios');
    }

    public function getEstOperarios(){
        $data = DB::table('vw_operarios2')->OrderBy('id')->get();
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('ope_est', function($row){
                    if($row->tot==0){
                        return "<span class='badge badge-success badge-pill'>Libre</span>";                   
                    }else{
                        return "<span class='badge badge-danger badge-pill'>ocupado</span>";
                    }                
                })                
                ->rawColumns(['ope_est'])              
                ->make(true);
    }

   
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

   
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }
}
