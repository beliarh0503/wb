<?php

namespace Anazarov\Users\Application\Dispatchers;

class ListenerProvider implements \Psr\EventDispatcher\ListenerProviderInterface
{

    private array $listeners = [];
    /**
     * @inheritDoc
     */
    public function getListenersForEvent(object $event): iterable
    {
        $eventType = get_class($event);
        if (array_key_exists($eventType, $this->listeners)) {
            return $this->listeners[$eventType];
        }

        return [];
    }

    /**
     * @param string $eventType
     * @param callable $callable
     * @return $this
     */
    public function addListener(string $eventType, callable $callable): self
    {
        $this->listeners[$eventType][] = $callable;
        return $this;
    }

    /**
     * @param string $eventType
     */
    public function clearListeners(string $eventType): void
    {
        if (array_key_exists($eventType, $this->listeners)) {
            unset($this->listeners[$eventType]);
        }
    }
}
