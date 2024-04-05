<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([ 
            PermisosSeeder::class,     
            RolesSeeder::class,   
            AccesosSeeder::class,   
            CatTipoAuditoriaSeeder::class,  
            CatTiposAccionesSeeder::class,    
            CatUnidadesAdministrativasSeeder::class,    
            CatMunicipiosSeeder::class,  
            CatTipologiasSeeder::class,  
            UsersSeeder::class,     
        ]);
        
    }
}
