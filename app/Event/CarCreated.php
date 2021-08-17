<?php
declare(strict_types=1);

namespace App\Event;

use Prooph\EventSourcing\AggregateChanged;

class CarCreated extends AggregateChanged
{
    public function id(): int
    {
        return $this->payload['id'];
    }

    public function brandName(): string
    {
        return $this->payload['brand_name'];
    }

    public function modelName(): string
    {
        return $this->payload['model_name'];
    }

    public function vin(): string
    {
        return $this->payload['vin'];
    }
}
