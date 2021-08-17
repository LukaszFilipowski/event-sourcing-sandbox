<?php

namespace Tests\Feature;

use App\Models\Car;
use Tests\TestCase;

class CarControllerTest extends TestCase
{
    public function testCreate()
    {
        $response = $this->post(
            route('cars.create'),
            [
                'id' => 1,
                'brand_name' => 'Cupra',
                'model_name' => 'Leon',
                'vin' => 'VZZ42356ExampleVin',
            ]
        );

//        dd(Car::all());
        $response->assertStatus(201);
    }
}
