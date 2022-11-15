<?php

namespace Anazarov\Common\Domain;

class Event
{
    public function __construct(private readonly object $object)
    {
    }

    public function getObject(): object
    {
        return $this->object;
    }
}
