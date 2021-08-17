<?php
declare(strict_types=1);

namespace App\Repository;

use App\Model\Car;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\EventStore;
use Prooph\SnapshotStore\SnapshotStore;

class CarRepository extends AggregateRepository
{
    public function __construct(EventStore $eventStore, SnapshotStore $snapshotStore)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromAggregateRootClass(Car::class),
            new AggregateTranslator(),
            $snapshotStore,
            null,
            true
        );
    }

    public function save(Car $car): void
    {
        $this->saveAggregateRoot($car);
    }

    public function get(int $id): ?Car
    {
        return $this->getAggregateRoot((string)$id);
    }
}
