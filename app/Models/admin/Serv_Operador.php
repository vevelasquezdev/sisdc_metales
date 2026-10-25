<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serv_Operador extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_orden_det',
        'id_operador'
    ];
    public $timestamps = false;
    protected $table = 'serv_operador';
    protected $primaryKey='id';
}
