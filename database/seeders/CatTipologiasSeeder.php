<?php

namespace Database\Seeders;

use App\Models\CatalogoTipologia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatTipologiasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CatalogoTipologia::insert([
            ['tipo_auditoria_id'=>1,'tipologia'=>'Control presupuestal','descripcion' => null],
            ['tipo_auditoria_id'=>1,'tipologia'=>'Gastos sin comprobación y/o justificación','descripcion' => null],
            ['tipo_auditoria_id'=>1,'tipologia'=>'Impuestos','descripcion' => null],
            ['tipo_auditoria_id'=>1,'tipologia'=>'Incumplimiento a las reglas de operación','descripcion' => null],
            ['tipo_auditoria_id'=>1,'tipologia'=>'Incumplimiento al objeto del gasto','descripcion' => null],
            ['tipo_auditoria_id'=>1,'tipologia'=>'Ingresos','descripcion' => null],
            ['tipo_auditoria_id'=>1,'tipologia'=>'Pago de ADEFAS','descripcion' => null],
            ['tipo_auditoria_id'=>1,'tipologia'=>'Pago indebido','descripcion' => null],
            ['tipo_auditoria_id'=>1,'tipologia'=>'Patrimonio','descripcion' => null],
            ['tipo_auditoria_id'=>1,'tipologia'=>'Proceso adquisitivo','descripcion' => null],
            ['tipo_auditoria_id'=>1,'tipologia'=>'Saldos de balanza','descripcion' => null],
            ['tipo_auditoria_id'=>1,'tipologia'=>'Sobre ejercicio presupuestal','descripcion' => null],           
            ['tipo_auditoria_id'=>1,'tipologia'=>'Sub ejercicio presupuestal','descripcion' => null],   

            ['tipo_auditoria_id'=>2,'tipologia'=>'Cantidades de Obra o Insumos','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Comprobación de la aplicación del gasto','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Contratación','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Control presupuestal','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Convenio adicionales y ampliaciones','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Deductivas','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Ejecución de obra','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Garantías y penas convencionales','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Incumplimiento de las reglas de operación del recurso','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Objeto y registro del gasto','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Obras o acciones realizadas en zonas y propiedades que no son públicas','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Operabilidad de obra','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Pagos con inconsistencias','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Planeación, Programación y Presupuestación','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Precios unitarios','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Procedimiento de adjudicación','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Simulación','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Sobre ejercicio','descripcion' => null],   
            ['tipo_auditoria_id'=>2,'tipologia'=>'Sub ejercicio','descripcion' => null],   

            ['tipo_auditoria_id'=>4,'tipologia'=>'Control interno municipal','descripcion' => null],   
            ['tipo_auditoria_id'=>4,'tipologia'=>'Información general','descripcion' => null],   
            ['tipo_auditoria_id'=>4,'tipologia'=>'Licencias de construcción','descripcion' => null],   
            ['tipo_auditoria_id'=>4,'tipologia'=>'Licencias de funcionamiento de unidades económicas','descripcion' => null],   
            ['tipo_auditoria_id'=>4,'tipologia'=>'Procedimiento adquisitivos','descripcion' => null],   
            ['tipo_auditoria_id'=>4,'tipologia'=>'Registro patrimonial','descripcion' => null],   
            ['tipo_auditoria_id'=>4,'tipologia'=>'Transparencia','descripcion' => null],  

            ['tipo_auditoria_id'=>3,'tipologia'=>'Actividades de control','descripcion' => null],   
            ['tipo_auditoria_id'=>3,'tipologia'=>'Actividades de control e información y comunicación','descripcion' => null],   
            ['tipo_auditoria_id'=>3,'tipologia'=>'Administración de riesgos','descripcion' => null],   
            ['tipo_auditoria_id'=>3,'tipologia'=>'Ambiente de control','descripcion' => null],   
            ['tipo_auditoria_id'=>3,'tipologia'=>'Información y comunicación','descripcion' => null],   
            ['tipo_auditoria_id'=>3,'tipologia'=>'Supervisión','descripcion' => null],            

            ['tipo_auditoria_id'=>5,'tipologia'=>'Actividades de Control','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Actividades de Control e Información y Comunicación','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Actividades de Control e Información y Control','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Actividades de Control y Administración de riesgos','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Actividades de Control y Supervisión','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Actividades de Control, Administración de Riesgos e Información y Comunicación','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Actividades de Control, Ambiente de Control e Información y Comunicación','descripcion' => null],  
            ['tipo_auditoria_id'=>5,'tipologia'=>'Actividades de Control, Supervisión e Información y Comunicación','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Administración de Riesgos','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Administración de Riesgos e Información y Comunicación','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Administración de Riesgos, Actividades de Control','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Administración de Riesgos, Actividades de Control e Información y Comunicación','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Ambiente de Control','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Ambiente de Control e Información y Comunicación','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Ambiente de Control Interno e Información y Comunicación','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Ambiente de Control y Actividades de Control','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Ambiente de Control y Supervisión','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Ambiente de Control, Actividades de Control e Información y Comunicación','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Ambiente de Control y Supervisión','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Información y comunicación','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Supervisión','descripcion' => null],   
            ['tipo_auditoria_id'=>5,'tipologia'=>'Supervisión e Información y Comunicación','descripcion' => null],   

        ]);
    }
}
