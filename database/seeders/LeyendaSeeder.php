<?php

namespace Database\Seeders;

use App\Models\CuentaPublica;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeyendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CuentaPublica::create([
            'cuenta_publica'=>'2021',
            'leyenda'=>'"2021. Año de la Consumación de la Independencia y la Grandeza de México"',
        ]);
        CuentaPublica::create([
            'cuenta_publica'=>'2022',
            'leyenda'=>'"2022. Año del Quincentenario de Toluca, Capital del Estado de México"',
        ]);
        CuentaPublica::create([
            'cuenta_publica'=>'2023',
            'leyenda'=>'"2023. Año del Septuagésimo Aniversario del Reconocimiento del Derecho al Voto de las Mujeres en México"',
        ]);
        
            
    }
}
