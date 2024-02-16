<?php
namespace App\Providers;

use App\Listeners\ContentLenghtListener;
use App\Listeners\HandleEntityListener;
use App\Listeners\InternalErrorListener;
use Jatmy\Framework\Dbal\Event\EntityPersist;
use Jatmy\Framework\Event\EventDispatcher;
use Jatmy\Framework\Http\Events\ResponseEvent;
use Jatmy\Framework\Providers\ServiceProviderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventServiceProvider implements ServiceProviderInterface
{
    private array $listen = [
        ResponseEvent::class => [
            InternalErrorListener::class,
            ContentLenghtListener::class,
        ],
        EntityPersist::class => [
            HandleEntityListener::class,
        ]
    ];

    public function __construct(
        private EventDispatcherInterface $dispatcher,
    ) {
    }
    public function register(): void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $this->dispatcher->addListener($event, new $listener);
            }
        }
    }
}
