<?php

namespace Anazarov\Users\Infrastructure\Repository;

use Anazarov\Users\Domain\Blacklist\BlacklistRepositoryInterface;
use Anazarov\Users\Domain\Blacklist\Type;

class BlacklistRepository implements BlacklistRepositoryInterface
{

    private array $collection = [
        'email' => [
            'google.com',
            'yandex.ru'
        ],
        'word' => [
            'test',
            'wisebits'
        ],

    ];

    public function isExist(Type $type, string $value): bool
    {
        foreach ($this->collection[$type->value] as $blacklistValue) {
            if (str_contains(mb_strtolower($value), $blacklistValue)) {
                return true;
            }
        }

        return false;
    }

}
