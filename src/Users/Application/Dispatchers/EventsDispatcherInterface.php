<?php

declare(strict_types=1);

namespace Anazarov\Users\Application\Dispatchers;

interface EventsDispatcherInterface
{
    public function dispatch(array $events);
}
