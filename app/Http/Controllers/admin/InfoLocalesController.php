<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class InfoLocalesController extends Controller
{
    private $table = 'info_locales';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table($this->table)
                ->whereNull('deleted_at')
                ->orderBy('local')
                ->get();

            return DataTables::of($data)
                ->addColumn('qr_url', function ($row) {
                    return $row->qr_pago ? asset('storage/info_locales/'.basename($row->qr_pago)) : null;
                })
                ->addColumn('action', function ($row) {
                    return "<button onclick='getdata(".$row->id.")' class='btn btn-primary btn-sm' data-toggle='modal' data-target='.default-example-modal-right'>Editar</button> |
                        <button onclick='del(".$row->id.")' class='btn btn-danger btn-sm'>Eliminar</button>";
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.info-locales');
    }

    public function store(Request $request)
    {
        $isUpdate = $request->filled('id');
        $record = null;

        if ($isUpdate) {
            $record = DB::table($this->table)
                ->where('id', $request->id)
                ->whereNull('deleted_at')
                ->first();

            if (!$record) {
                return response()->json(['msg' => 'No se encontró el registro.'], 404);
            }
        }

        foreach (['local', 'direccion', 'razon_social'] as $field) {
            $value = $request->input($field);
            if (is_string($value)) {
                $request->merge([$field => Str::upper($value)]);
            }
        }

        $cuentas = [
            'cta_cte_bcp_soles',
            'cta_cci_bcp_soles',
            'cta_cte_bcp_dolares',
            'cta_cci_bcp_dolares',
            'cta_cte_interbank_soles',
            'cta_cci_interbank_soles',
            'cta_cte_interbank_dolares',
            'cta_cci_interbank_dolares',
        ];
        foreach ($cuentas as $cuenta) {
            $campoVisible = $cuenta.'_check';
            if (!$request->exists($campoVisible)) {
                $request->merge([$campoVisible => '0']);
            }
        }

        $rules = [
            'local' => 'required|string|max:150',
            'direccion' => 'required|string|max:150',
            'razon_social' => 'required|string|max:50',
            'ruc' => 'required|integer|digits:11',
            'telefono' => 'required|string|max:30',
            'celular' => 'required|string|max:30',
            'celular2' => 'nullable|string|max:30',
            'email' => 'required|email|max:50',
            'cta_cte_bcp_soles' => 'required|string|max:50',
            'cta_cci_bcp_soles' => 'required|string|max:50',
            'cta_cte_bcp_dolares' => 'nullable|string|max:50',
            'cta_cci_bcp_dolares' => 'nullable|string|max:50',
            'cta_cte_interbank_soles' => 'nullable|string|max:30',
            'cta_cci_interbank_soles' => 'nullable|string|max:30',
            'cta_cte_interbank_dolares' => 'nullable|string|max:30',
            'cta_cci_interbank_dolares' => 'nullable|string|max:30',
            'cta_cte_bcp_soles_check' => 'required|boolean',
            'cta_cci_bcp_soles_check' => 'required|boolean',
            'cta_cte_bcp_dolares_check' => 'required|boolean',
            'cta_cci_bcp_dolares_check' => 'required|boolean',
            'cta_cte_interbank_soles_check' => 'required|boolean',
            'cta_cci_interbank_soles_check' => 'required|boolean',
            'cta_cte_interbank_dolares_check' => 'required|boolean',
            'cta_cci_interbank_dolares_check' => 'required|boolean',
            'qr_pago' => ($isUpdate ? 'nullable' : 'required').'|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link_web' => 'required|string|max:100'
        ];

        $validated = $request->validate($rules);
        unset($validated['qr_pago']);

        if ($request->hasFile('qr_pago')) {
            $filename = Str::uuid().'.'.$request->file('qr_pago')->extension();
            $directory = 'storage/info_locales/';
            File::ensureDirectoryExists($directory, 0755, true);
            $request->file('qr_pago')->move($directory, $filename);
            $validated['qr_pago'] = $filename;
        }
        
        if ($isUpdate) {
            $validated['updated_at'] = now();
            DB::table($this->table)
                ->where('id', $record->id)
                ->update($validated);

            if ($request->hasFile('qr_pago') && $record->qr_pago) {
                File::delete('storage/info_locales/'.basename($record->qr_pago));
            }

            return response()->json(['msg' => 'Información del local actualizada.']);
        }

        $validated['created_at'] = now();
        $validated['updated_at'] = now();
        $validated['deleted_at'] = null;
        DB::table($this->table)->insert($validated);

        return response()->json(['msg' => 'Información del local guardada.']);
    }

    public function edit($id)
    {
        $record = DB::table($this->table)
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$record) {
            return response()->json(['msg' => 'No se encontró el registro.'], 404);
        }

        $record->qr_url = $record->qr_pago ? asset('storage/info_locales/'.basename($record->qr_pago)) : null;

        return response()->json($record);
    }

    public function destroy($id)
    {
        $deleted = DB::table($this->table)
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        if (!$deleted) {
            return response()->json(['msg' => 'No se encontró el registro.'], 404);
        }

        return response()->json(['msg' => 'Información del local eliminada.']);
    }
}