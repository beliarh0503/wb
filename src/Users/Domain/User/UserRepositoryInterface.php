<?php

declare(strict_types=1);

namespace Anazarov\Users\Domain\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findById(int $id): ?User;

    public function findAll(): array;

    public function update(User $user): void;
}
