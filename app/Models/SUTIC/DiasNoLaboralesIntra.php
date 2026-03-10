<?php

namespace App\Models\SUTIC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;

class DiasNoLaboralesIntra extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;

    protected $connection = 'sqlsrv';

    protected $table = 'DbsGlobal.dbo.TblDiaNoLab';

    protected $primaryKey = 'PkCveEntDia';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'PkCveEntDia',
        'DiaNoLab',
        'CnpEntDia',
        'StsEntDia',
        'TipoDia',
        'Anio',
        
    ];

    public $timestamps = false;

    protected $dates = [
        'DiaNoLab',
    ];

}
