<?php

declare(strict_types=1);

namespace Anazarov\Common\Domain;

class AggregateRoot
{
    private array $events = [];

    /**
     * @param $event
     * @return void
     */
    protected function recordEvent($event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return array
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }

}
