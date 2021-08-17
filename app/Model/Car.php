<?php
declare(strict_types=1);

namespace App\Model;

use App\Event\CarCreated;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class Car extends AggregateRoot
{
    private string $id;
    private string $brand_name;
    private string $model_name;
    private string $vin;

    static public function createWithData(string $id, string $brandName, string $modelName, string $vin): self
    {
        $obj = new self;
        $obj->recordThat(CarCreated::occur($id, [
            'brand_name' => $brandName,
            'model_name' => $modelName,
            'vin' => $vin
        ]));

        return $obj;
    }

    protected function aggregateId(): string
    {
        return $this->id;
    }

    protected function apply(AggregateChanged $event): void
    {
        switch (get_class($event)) {
            case CarCreated::class:
                /** @var CarCreated $event */
                $this->id = $event->aggregateId();
                $this->brand_name = $event->brandName();
                $this->model_name = $event->modelName();
                $this->vin = $event->vin();
                break;
        }
    }
}
