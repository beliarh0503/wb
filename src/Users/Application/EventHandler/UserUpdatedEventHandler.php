<?php

namespace Anazarov\Users\Application\EventHandler;

use Anazarov\Users\Domain\User\UserUpdatedEvent;
use Psr\Log\LoggerInterface;

class UserUpdatedEventHandler
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(UserUpdatedEvent $event): void
    {
        $this->logger->info(sprintf('User %s was updated', json_encode($event->getObject())));
    }
}
