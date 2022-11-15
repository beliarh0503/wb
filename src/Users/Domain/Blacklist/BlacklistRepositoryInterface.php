<?php

declare(strict_types=1);

namespace Anazarov\Users\Domain\Blacklist;

interface BlacklistRepositoryInterface
{
    public function isExist(Type $type, string $value): bool;
}
