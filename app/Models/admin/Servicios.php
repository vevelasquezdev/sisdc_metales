<?php

namespace App\Models\admin;

use Database\Factories\ServiciosFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'descripcion',
        'maquina',       
        'limite',
        'orden',
        'id_operador',
        'active',
        'local'     
    ];
    
    protected $table = 'servicios';
    protected $primaryKey='id';

    protected static function newFactory()
    {
        return ServiciosFactory::new();
    }
}
