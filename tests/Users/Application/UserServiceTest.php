<?php

namespace Anazarov\Tests\Users\Application;

use Anazarov\Users\Application\BlacklistService;
use Anazarov\Users\Application\Dispatchers\EventsDispatcherInterface;
use Anazarov\Users\Application\UserService;
use Anazarov\Users\Application\ValidationException;
use Anazarov\Users\Domain\User\User;
use Anazarov\Users\Domain\User\UserNotFoundException;
use Anazarov\Users\Domain\User\UserRepositoryInterface;
use Codeception\Test\Unit;
use Tests\Support\UnitTester;

class UserServiceTest extends Unit
{

    /** @var UnitTester */
    protected UnitTester $tester;

    public function testFindById()
    {
        $user = $this->createMock(User::class);
        $repository = $this->createStub(UserRepositoryInterface::class);
        $repository->expects(self::once())->method('findById')->with(1)->willReturn($user);

        $dispatcher = $this->createStub(EventsDispatcherInterface::class);
        $blacklistService = $this->createStub(BlacklistService::class);
        $service = new UserService($repository, $blacklistService, $dispatcher);
        $service->findById(1);
    }

    public function testFindByIdNotExist()
    {
        $repository = $this->createStub(UserRepositoryInterface::class);
        $repository->expects(self::once())->method('findById')->with(1)->willReturn(null);

        $dispatcher = $this->createStub(EventsDispatcherInterface::class);
        $blacklistService = $this->createStub(BlacklistService::class);
        $service = new UserService($repository, $blacklistService, $dispatcher);

        $this->expectException(UserNotFoundException::class);
        $service->findById(1);
    }

    public function testCreate()
    {
        $repository = $this->createStub(UserRepositoryInterface::class);
        $dispatcher = $this->createStub(EventsDispatcherInterface::class);
        $blacklistService = $this->createStub(BlacklistService::class);

        $service = new UserService($repository, $blacklistService, $dispatcher);
        $service->create('AlekseiNazarov', 'AlekseiNazarov@mail.ru');
        $service->create('AlekseiNazarov2', 'AlekseiNazarov2@mail.ru', '123');
    }

    public function testFailCreate()
    {
        $repository = $this->createStub(UserRepositoryInterface::class);
        $dispatcher = $this->createStub(EventsDispatcherInterface::class);
        $blacklistService = $this->createStub(BlacklistService::class);
        $service = new UserService($repository, $blacklistService, $dispatcher);

        $this->tester->expectThrowable(ValidationException::class, function () use ($service) {
            $service->create('Aleksei', 'AlekseiNazarov@mail.ru');
        });

        $this->tester->expectThrowable(ValidationException::class, function () use ($service) {
            $service->create(str_repeat('i', 65), 'AlekseiNazarov2@mail.ru', '123');
        });

        $this->tester->expectThrowable(ValidationException::class, function () use ($service, $blacklistService) {
            $blacklistService->expects(self::any())->method('isBlacklistedEmail')->willReturn(true);
            $service->create('Aleksei', 'AlekseiNazarov2@mail.ru', '123');
        });

        $this->tester->expectThrowable(ValidationException::class, function () use ($service, $blacklistService) {
            $blacklistService->expects(self::any())->method('isBlacklistedWord')->willReturn(true);
            $service->create('test', 'AlekseiNazarov2@mail.ru', '123');
        });
    }

    public function testUpdate()
    {
        $user = $this->createMock(User::class);

        $repository = $this->createStub(UserRepositoryInterface::class);
        $repository->expects(self::once())->method('findById')->with(1)->willReturn($user);

        $dispatcher = $this->createStub(EventsDispatcherInterface::class);
        $blacklistService = $this->createStub(BlacklistService::class);
        $service = new UserService($repository, $blacklistService, $dispatcher);
        $service->update(1, 'AlekseiNazarov', 'AlekseiNazarov@mail.ru', 'notes');
    }

    public function testUpdateName()
    {
        $user = $this->createMock(User::class);
        $user->expects(self::once())->method('setName');
        $user->expects(self::never())->method('setNotes');
        $user->expects(self::never())->method('setEmail');

        $repository = $this->createStub(UserRepositoryInterface::class);
        $repository->expects(self::once())->method('findById')->with(1)->willReturn($user);

        $dispatcher = $this->createStub(EventsDispatcherInterface::class);
        $blacklistService = $this->createStub(BlacklistService::class);
        $service = new UserService($repository, $blacklistService, $dispatcher);
        $service->update(1, 'AlekseiNazarov', null, null);
    }

    public function testUpdateEmail()
    {
        $user = $this->createMock(User::class);
        $user->expects(self::once())->method('setEmail');
        $user->expects(self::never())->method('setNotes');
        $user->expects(self::never())->method('setName');

        $repository = $this->createStub(UserRepositoryInterface::class);
        $repository->expects(self::once())->method('findById')->with(1)->willReturn($user);

        $dispatcher = $this->createStub(EventsDispatcherInterface::class);
        $blacklistService = $this->createStub(BlacklistService::class);
        $service = new UserService($repository, $blacklistService, $dispatcher);
        $service->update(1, null, 'AlekseiNazarov@mail.ru', null);
    }

    public function testNotes()
    {
        $user = $this->createMock(User::class);
        $user->expects(self::once())->method('setNotes');
        $user->expects(self::never())->method('setName');
        $user->expects(self::never())->method('setEmail');

        $repository = $this->createStub(UserRepositoryInterface::class);
        $repository->expects(self::once())->method('findById')->with(1)->willReturn($user);

        $dispatcher = $this->createStub(EventsDispatcherInterface::class);
        $blacklistService = $this->createStub(BlacklistService::class);
        $service = new UserService($repository, $blacklistService, $dispatcher);
        $service->update(1, null, null, 'notes');
    }

    public function testFailUpdateShotName()
    {
        $id = 1;
        $name = 'MyName';

        $user = $this->createMock(User::class);
        $user->expects(self::once())->method('setName');
        $user->expects(self::once())->method('getName')->willReturn($name);
        $user->expects(self::never())->method('setNotes');
        $user->expects(self::never())->method('setEmail');

        $repository = $this->createStub(UserRepositoryInterface::class);
        $repository->expects(self::once())->method('findById')->with($id)->willReturn($user);

        $dispatcher = $this->createStub(EventsDispatcherInterface::class);
        $blacklistService = $this->createStub(BlacklistService::class);
        $service = new UserService($repository, $blacklistService, $dispatcher);

        $this->expectException(ValidationException::class);
        $service->update(1, $name, null, null);
    }

    public function testFailUpdateLongName()
    {
        $id = 1;
        $name = str_repeat('i', 65);

        $user = $this->createMock(User::class);
        $user->expects(self::once())->method('setName');
        $user->expects(self::once())->method('getName')->willReturn($name);
        $user->expects(self::never())->method('setNotes');
        $user->expects(self::never())->method('setEmail');

        $repository = $this->createStub(UserRepositoryInterface::class);
        $repository->expects(self::once())->method('findById')->with($id)->willReturn($user);

        $dispatcher = $this->createStub(EventsDispatcherInterface::class);
        $blacklistService = $this->createStub(BlacklistService::class);
        $service = new UserService($repository, $blacklistService, $dispatcher);

        $this->expectException(ValidationException::class);
        $service->update(1, $name, null, null);
    }

    public function testDelete()
    {
        $user = $this->createMock(User::class);
        $repository = $this->createStub(UserRepositoryInterface::class);
        $repository->expects(self::once())->method('findById')->with(1)->willReturn($user);

        $dispatcher = $this->createStub(EventsDispatcherInterface::class);
        $blacklistService = $this->createStub(BlacklistService::class);
        $service = new UserService($repository, $blacklistService, $dispatcher);
        $service->delete(1);
    }

    public function testDeleteNotExist()
    {
        $id = 1;

        $repository = $this->createStub(UserRepositoryInterface::class);
        $repository->expects(self::once())->method('findById')->with($id)->willReturn(null);

        $dispatcher = $this->createStub(EventsDispatcherInterface::class);
        $blacklistService = $this->createStub(BlacklistService::class);
        $service = new UserService($repository, $blacklistService, $dispatcher);

        $this->expectException(UserNotFoundException::class);
        $service->delete($id);
    }
}
