<?php

namespace Anazarov\Users\Application\Dispatchers;

use Psr\EventDispatcher\ListenerProviderInterface;

class EventDispatcher implements \Psr\EventDispatcher\EventDispatcherInterface
{

    public function __construct(private readonly ListenerProviderInterface $listenerProvider)
    {
    }

    /**
     * @inheritDoc
     */
    public function dispatch(object $event)
    {
        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
            $listener($event);
        }
    }
}
