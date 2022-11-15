<?php

declare(strict_types=1);

namespace Anazarov\Users\Application;

use Anazarov\Users\Application\Dispatchers\EventsDispatcherInterface;
use Anazarov\Users\Domain\User\DeletedUserException;
use Anazarov\Users\Domain\User\User;
use Anazarov\Users\Domain\User\UserNotFoundException;
use Anazarov\Users\Domain\User\UserRepositoryInterface;
use yii\base\DynamicModel;

class UserService
{

    /**
     * @param UserRepositoryInterface   $repository
     * @param BlacklistService          $blacklistService
     * @param EventsDispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly BlacklistService $blacklistService,
        private readonly EventsDispatcherInterface $dispatcher,
    ) {
    }

    /**
     * @param int $id
     * @return User
     */
    public function findById(int $id): User
    {
        $user = $this->repository->findById($id);

        if ($user === null) {
            throw new UserNotFoundException($id);
        }

        return $user;
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $user = $this->findById($id);
        $user->delete();

        $this->repository->update($user);
        $this->dispatcher->dispatch($user->releaseEvents());
    }

    /**
     * @param int         $id
     * @param string|null $name
     * @param string|null $email
     * @param string|null $notes
     * @return void
     */
    public function update(int $id, ?string $name, ?string $email, ?string $notes): void
    {
        $user = $this->findById($id);

        if ($user->getDeleted() !== null) {
            throw new DeletedUserException($id);
        }

        if ($name !== null) {
            $user->setName($name);
        }

        if ($email !== null) {
            $user->setEmail($email);
        }

        if ($notes !== null) {
            $user->setNotes($notes);
        }

        $this->validate($user);
        $this->repository->update($user);
        $this->dispatcher->dispatch($user->releaseEvents());
    }

    /**
     * @param string      $name
     * @param string      $email
     * @param string|null $notes
     */
    public function create(
        string $name,
        string $email,
        ?string $notes = null
    ): void {
        $createUser = User::create($name, $email, $notes);
        $this->validate($createUser);
        $this->repository->save($createUser);
        $this->dispatcher->dispatch($createUser->releaseEvents());
    }

    private function validate(User $user): void
    {
        $data = [
            'name'  => $user->getName(),
            'email' => $user->getEmail(),
        ];

        $dynamicModel = DynamicModel::validateData($data, $this->getValidationRules());

        if ($dynamicModel->hasErrors()) {
            throw new ValidationException(json_encode($dynamicModel->getFirstErrors()));
        }
    }

    /**
     * @return array
     */
    private function getValidationRules(): array
    {
        $blacklist = $this->blacklistService;

        return [
            [
                'name',
                'string',
                'min'      => 8,
                'max'      => 64,
                'tooShort' => 'should contain at least 8 character',
                'tooLong'  => 'should contain at most 64 character',
            ],
            ['name', 'match', 'pattern' => '/^[a-zA-Z0-9]+$/'],
            [
                'name',
                function ($attribute) use ($blacklist) {
                    /** @var DynamicModel $this */
                    if ($blacklist->isBlacklistedWord($this->$attribute)) {
                        $this->addError($attribute, sprintf('Сan not contain a word %s', $attribute));
                    }
                },
            ],
            ['email', 'email'],
            [
                'email',
                function ($attribute) use ($blacklist) {
                    /** @var DynamicModel $this */
                    if ($blacklist->isBlacklistedEmail($this->$attribute)) {
                        $this->addError($attribute,
                            sprintf('The email %s contains a banned domain ', $this->$attribute));
                    }
                },
            ],
        ];
    }
}
