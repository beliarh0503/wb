<?php

namespace Anazarov\Users\Application\EventHandler;

use Anazarov\Users\Domain\User\UserCreatedEvent;
use Psr\Log\LoggerInterface;

class UserCreatedEventHandler
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(UserCreatedEvent $event): void
    {
        $this->logger->info(sprintf('User %s was created', json_encode($event->getObject())));
    }
}
