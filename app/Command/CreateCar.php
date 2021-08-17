<?php
declare(strict_types=1);

namespace App\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

class CreateCar extends Command
{
    use PayloadTrait;

    public function id(): int
    {
        return $this->payload()['id'];
    }

    public function brandName(): string
    {
        return $this->payload()['brand_name'];
    }

    public function modelName(): string
    {
        return $this->payload()['model_name'];
    }

    public function vin(): string
    {
        return $this->payload()['vin'];
    }
}
