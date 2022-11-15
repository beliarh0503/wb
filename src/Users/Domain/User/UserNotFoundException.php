<?php

declare(strict_types=1);

namespace Anazarov\Users\Domain\User;

class UserNotFoundException extends \DomainException
{
    public function __construct(private readonly int $id)
    {
        parent::__construct($this->errorMessage());
    }

    public function errorCode(): string
    {
        return 'user_not_exist';
    }

    protected function errorMessage(): string
    {
        return sprintf('The user %u does not exist', $this->id);
    }
}
