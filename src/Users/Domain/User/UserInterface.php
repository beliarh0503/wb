<?php

namespace Anazarov\Users\Domain\User;

interface UserInterface
{
    public function getName(): string;

    public function getEmail(): string;

    public function getCreated(): \DateTimeImmutable;

    public function getDeleted(): ?\DateTimeImmutable;

    public function getNotes(): ?string;

    public function setName(string $name): void;

    public function setEmail(string $email): void;

    public function setNotes(?string $notes): void;
}
