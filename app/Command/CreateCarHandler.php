<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Car;
use App\Repository\CarRepository;

class CreateCarHandler
{
    private CarRepository $repository;

    public function __construct(CarRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateCar $createCar): void
    {
        $car = Car::createWithData((string)$createCar->id(), $createCar->brandName(), $createCar->modelName(), $createCar->vin());
        $this->repository->save($car);
    }
}
