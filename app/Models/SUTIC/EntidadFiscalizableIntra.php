<?php

namespace App\Models\SUTIC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;

class EntidadFiscalizableIntra extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;

    protected $connection = 'sqlsrv';

    protected $table = 'DbsGlobal.dbo.TblEntFis';

    protected $primaryKey = 'PkCveEntFis';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'PkCveEntFis',
        'CveEntFis',
        'NomEntFis',
        'SigEntFis',
        'CveOsfEnt',
        'FkCveEntFis',
        'NivEntFis',
        'StsEntFis',
        'EntInfMns',
        'EntCtaPub',
        'Evl',
        'Observaciones',
        'Sol',
        'RtaEntFis',
        'CveUsuAlt',
        'FecAltSis',
        'CveUsuMod',
        'FecultMod',
        'Art4FisOrd',
        'Ambito',
        'CveUsuBaj',
        'FecUsuBaj',
        'EntInfTrm',
    ];

    public $timestamps = false;

    protected $dates = [
        'FecultMod',
        'FecUsuBaj',
        'FecAltSis',
    ];

    public function entidadFiscalizableN2()
    {                
        return $this->hasOne(EntidadFiscalizableIntra::class, 'PkCveEntFis', 'FkCveEntFis');
    }

    public function entidadFiscalizableN1()
    {                
        return $this->entidadFiscalizableN2->hasOne(EntidadFiscalizableIntra::class, 'PkCveEntFis', 'FkCveEntFis');
    }
}
