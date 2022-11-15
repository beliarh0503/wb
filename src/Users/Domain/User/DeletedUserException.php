<?php

declare(strict_types=1);

namespace Anazarov\Users\Domain\User;

class DeletedUserException extends \DomainException
{
    public function __construct(private readonly int $id)
    {
        parent::__construct($this->errorMessage());
    }

    public function errorCode(): string
    {
        return 'deleted_user';
    }

    protected function errorMessage(): string
    {
        return sprintf('The user was %i deleted. Operation does not support', $this->id);
    }
}
