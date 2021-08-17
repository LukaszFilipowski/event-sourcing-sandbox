<?php

namespace App\Http\Controllers;

use App\Command\CreateCar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prooph\ServiceBus\CommandBus;

class CarController extends Controller
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function create(Request $request)
    {
        $input = $request->all();
        dump($input);

        $this->commandBus->dispatch(new CreateCar([
            'id' => $input['id'],
            'brand_name' => $input['brand_name'],
            'model_name' => $input['model_name'],
            'vin' => $input['vin'],
        ]));

        return new JsonResponse(['status' => 'success'], 201);
    }
}
