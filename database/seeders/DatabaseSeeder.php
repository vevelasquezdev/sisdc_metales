<?php

namespace Database\Seeders;

use App\Models\admin\Carta;
use App\Models\admin\Servicios;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'VLADY',
            'surname' => 'VELASQUEZ',
            'email' => 'admin@dhymark.com',
            'rol' => 'ADMINISTRADOR',
            'password' => Hash::make('1234'),
            'active' => 1,
            'fch_nac' => '1991-04-17',
        ]);
        User::factory(20)->create();
        
        Servicios::factory(10)->create();
       

        /* $users = factory(App\User::class, 50)->create(); */
    }
}
