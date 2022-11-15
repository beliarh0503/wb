<?php

declare(strict_types=1);

namespace Anazarov\Users\Domain\User;

use Anazarov\Common\Domain\AggregateRoot;
use DateTimeImmutable;

class User extends AggregateRoot implements UserInterface
{

    public function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private readonly DateTimeImmutable $created,
        private ?DateTimeImmutable $deleted,
        private ?string $notes
    ) {
    }

    public static function create(string $name, string $email, ?string $notes = null): self
    {
        $user = new self(
            id: null,
            name: $name,
            email: $email,
            created: new \DateTimeImmutable(),
            deleted: null,
            notes: $notes
        );

        $user->recordEvent(new UserCreatedEvent($user));

        return $user;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getDeleted(): ?DateTimeImmutable
    {
        $this->recordEvent(new UserDeletedEvent($this));

        return $this->deleted;
    }

    /**
     * @return string
     */
    public function getNotes(): string
    {
        return $this->notes;
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $this->deleted = new DateTimeImmutable;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param ?string $notes
     * @return void
     */
    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

}
