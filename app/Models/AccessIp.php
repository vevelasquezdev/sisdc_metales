<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessIp extends Model
{
    use HasFactory;
    protected $fillable = [
        'descripcion',
        'numero_ip',       
        'fch',        
    ];
    public $timestamps = false;
    protected $table = 'ip_locales';
    protected $primaryKey='id';
}
