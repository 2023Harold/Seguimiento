<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaPublica extends Model
{
        //use HasFactory;
        protected $table = 'segcuenta_publica';
        
        protected $fillable = [
        'cuenta_publica',
        'leyenda',
        'created_at',
        'updated_at',
        ];
        protected $casts = [
            'created_at'=>'datetime',
            'updated_at'=>'datetime'
        ];    
             
}
