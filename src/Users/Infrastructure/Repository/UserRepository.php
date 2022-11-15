<?php

declare(strict_types=1);

namespace Anazarov\Users\Infrastructure\Repository;

use Anazarov\Users\Domain\User\User;
use yii\db\Connection;
use yii\db\Exception;
use yii\db\Query;

class UserRepository implements \Anazarov\Users\Domain\User\UserRepositoryInterface
{

    private readonly string $tableName;

    public function __construct(private readonly Connection $db)
    {
        $this->tableName = 'user_table';
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function save(User $user): void
    {
        $this->db->createCommand()->insert(
            $this->tableName,
            $this->extractData($user)
        )->execute();

        $user->setId((int) $this->db->lastInsertID);
    }

    /**
     * @param int $id
     * @return User|null
     * @throws \Exception
     */
    public function findById(int $id): ?User
    {
        $query = (new Query())->select([
            'id',
            'name',
            'email',
            'created',
            'deleted',
            'notes',
        ])->from($this->tableName)->andWhere(['id' => $id])->one($this->db);

        if (empty($query)) {
            return null;
        }

        return $this->fromState($query);
    }

    public function findAll(): array
    {
        // TODO: Implement findAll() method.
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function update(User $user): void
    {
        $this->db->createCommand()->update(
            $this->tableName,
            $this->extractData($user),
            ['id' => $user->getId()]
        )->execute();
    }

    private function extractData(User $user): array
    {
        return [
            'id'      => $user->getId(),
            'name'    => $user->getName(),
            'email'   => $user->getEmail(),
            'created' => $user->getCreated()->format('Y-m-d H:i:s'),
            'deleted' => $user->getDeleted()?->format('Y-m-d H:i:s'),
            'notes'   => $user->getNotes(),
        ];
    }

    /**
     * @param $array
     * @return User
     * @throws \Exception
     */
    private function fromState($array): User
    {
        return new User(
            $array['id'],
            $array['name'],
            $array['email'],
            new \DateTimeImmutable($array['created']),
            isset($array['deleted']) ? new \DateTimeImmutable($array['deleted']) : null,
            $array['notes']
        );
    }
}
