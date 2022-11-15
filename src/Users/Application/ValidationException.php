<?php

declare(strict_types=1);

namespace Anazarov\Users\Application;

class ValidationException extends \InvalidArgumentException
{

    public function __construct(protected $message)
    {
        parent::__construct($this->errorMessage());
    }

    public function errorCode(): string
    {
        return 'validation_error';
    }

    protected function errorMessage(): string
    {
        return $this->message;
    }
}
