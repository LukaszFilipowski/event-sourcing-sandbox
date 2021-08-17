<?php
declare(strict_types=1);

namespace App\Projection;

use App\Event\CarCreated;
use App\Models\Car;

class CarProjector
{
    public function onCarCreated(CarCreated $carCreated): void
    {
        $car = new Car();
        $car->brand_name = $carCreated->brandName();
        $car->model_name = $carCreated->modelName();
        $car->vin = $carCreated->vin();

        $car->save();
    }
}
