<?php

namespace Database\Factories;

use App\Models\admin\Servicios;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiciosFactory extends Factory
{
    
    protected $model = Servicios::class;

    
    public function definition()
    {
        return [
            'descripcion' => strtoupper($this->faker->streetName),
            'maquina' => strtoupper($this->faker->streetName),
            'limite'   => 1,
        ];
    }
}
