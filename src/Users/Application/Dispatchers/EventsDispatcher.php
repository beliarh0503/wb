<?php

declare(strict_types=1);

namespace Anazarov\Users\Application\Dispatchers;

use Psr\EventDispatcher\EventDispatcherInterface;

class EventsDispatcher implements EventsDispatcherInterface
{

    public function __construct(private readonly EventDispatcherInterface $dispatcher)
    {
    }

    public function dispatch(array $events)
    {
        foreach ($events as $event) {
            $this->dispatcher->dispatch($event);
        }
    }
}
