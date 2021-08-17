<?php

namespace App\Providers;

use App\Command\CreateCar;
use App\Command\CreateCarHandler;
use App\Event\CarCreated;
use App\Projection\CarProjector;
use App\Repository\CarRepository;
use Illuminate\Database\PDO\Connection;
use Illuminate\Support\ServiceProvider;
use Prooph\Common\Event\ProophActionEventEmitter;
use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\EventStore\ActionEventEmitterEventStore;
use Prooph\EventStore\Pdo\MySqlEventStore;
use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlAggregateStreamStrategy;
use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
use Prooph\EventStoreBusBridge\EventPublisher;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\EventBus;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Prooph\ServiceBus\Plugin\Router\EventRouter;
use Prooph\SnapshotStore\Pdo\PdoSnapshotStore;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MySqlEventStore::class, function ($app) {
            /** @var Connection $pdo */
            $pdo = app(Connection::class);
            $pdo = $pdo->getWrappedConnection();

            return new MySqlEventStore(
                new FQCNMessageFactory(),
                $pdo,
                new MySqlAggregateStreamStrategy()
            );
        });

        $this->app->bind(ProophActionEventEmitter::class, function ($app) {
            return new ProophActionEventEmitter();
        });

        $this->app->bind(ActionEventEmitterEventStore::class, function ($app) {
            return new ActionEventEmitterEventStore(
                app(MySqlEventStore::class),
                app(ProophActionEventEmitter::class),
            );
        });

        $this->app->bind(EventBus::class, function ($app) {
            return new EventBus(app(ProophActionEventEmitter::class));
        });

        $this->app->bind(EventPublisher::class, function ($app) {
            $eventBus = app(EventBus::class);
            $eventPublisher = new EventPublisher($eventBus);
            $eventPublisher->attachToEventStore(app(ActionEventEmitterEventStore::class));
            return $eventPublisher;
        });

        $this->app->bind(PdoSnapshotStore::class, function ($app) {
            /** @var Connection $pdo */
            $pdo = app(Connection::class);
            $pdo = $pdo->getWrappedConnection();

            return new PdoSnapshotStore($pdo);
        });

        $this->app->bind(CarRepository::class, function ($app) {
            return new CarRepository(app(MySqlEventStore::class), app(PdoSnapshotStore::class));
        });

        $this->app->bind(MySqlProjectionManager::class, function ($app) {
            /** @var Connection $pdo */
            $pdo = app(Connection::class);
            $pdo = $pdo->getWrappedConnection();

            return new MySqlProjectionManager(app(MySqlEventStore::class), $pdo);
        });

        $this->app->bind(CommandBus::class, function ($app) {
            return new CommandBus();
        });

        $this->app->bind(CreateCarHandler::class, function ($app) {
             return new CreateCarHandler(app(CarRepository::class));
        });

        $this->app->bind(CommandRouter::class, function ($app) {
            $router = new CommandRouter();
            $router->route(CreateCar::class)->to(app(CreateCarHandler::class));
            $router->attachToMessageBus(app(CommandBus::class));
            return $router;
        });

        $this->app->bind(EventRouter::class, function ($app) {
            $eventRouter = new EventRouter();
            $eventRouter->route(CarCreated::class)->to([app(CarProjector::class), 'onCarCreated']);
            $eventRouter->attachToMessageBus(app(EventBus::class));
            return $eventRouter;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
