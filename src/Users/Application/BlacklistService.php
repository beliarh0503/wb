<?php

namespace Anazarov\Users\Application;

use Anazarov\Users\Domain\Blacklist\BlacklistRepositoryInterface;
use Anazarov\Users\Domain\Blacklist\Type;

class BlacklistService
{
    public function __construct(private readonly BlacklistRepositoryInterface $blacklistRepository)
    {
    }

    /**
     * @param string $email
     * @return bool
     */
    public function isBlacklistedEmail(string $email): bool
    {
        return $this->blacklistRepository->isExist(Type::EMAIL, $email);
    }

    /**
     * @param string $word
     * @return bool
     */
    public function isBlacklistedWord(string $word): bool
    {
        return $this->blacklistRepository->isExist(Type::EMAIL, $word);
    }
}
