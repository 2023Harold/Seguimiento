<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Auditoria;
use App\Models\SUTIC\EntidadFiscalizableIntra;

class CP2023SEFSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $auditorias2023 = Auditoria::where('cuenta_publica',2023)->get();
		
		foreach($auditorias2023 as $auditoria){
			$entidad = EntidadFiscalizableIntra::find($auditoria->entidad_fiscalizable_id);
			if($entidad){
				if($entidad->SigEntFis){
				$auditoria->update(['siglas_entidad'=>$entidad->SigEntFis]);
				}else{
					if($entidad->CveOsfEnt){			
						$auditoria->update(['siglas_entidad'=>$entidad->CveOsfEnt]);
					}
				}				
			}			
		}	
    }
}
