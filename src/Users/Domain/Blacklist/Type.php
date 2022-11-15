<?php

namespace Anazarov\Users\Domain\Blacklist;

enum Type: string
{
    case EMAIL = 'email';
    case WORD = 'word';
}
