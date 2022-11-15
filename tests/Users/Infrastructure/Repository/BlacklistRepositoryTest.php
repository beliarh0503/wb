<?php

namespace Anazarov\Tests\Users\Infrastructure\Repository;

use Anazarov\Users\Domain\Blacklist\Type;
use Anazarov\Users\Infrastructure\Repository\BlacklistRepository;
use Codeception\Test\Unit;
use Tests\Support\UnitTester;

class BlacklistRepositoryTest extends Unit
{
    /** @var UnitTester */
    protected UnitTester $tester;

    /**
     * @dataProvider isExistDataProvider
     * @param $expected
     * @param $name
     */
    public function testIsExistWord($expected, $name)
    {
        $blacklistRepository = new BlacklistRepository();
        $this->tester->assertEquals($expected, $blacklistRepository->isExist(Type::WORD, $name));
    }

    /**
     * @dataProvider isExistEmailDataProvider
     * @param $expected
     * @param $email
     */
    public function testIsExistEmail($expected, $email)
    {
        $blacklistRepository = new BlacklistRepository();
        $this->tester->assertEquals($expected, $blacklistRepository->isExist(Type::EMAIL, $email));
    }

    public function isExistDataProvider(): array
    {
        return [
            [true, 'test'],
            [true, 'Alekseitest'],
            [true, 'AlekWisebitssei'],
            [false, 'Aleksei'],
            [false, '123'],
        ];
    }

    public function isExistEmailDataProvider(): array
    {
        return [
            [true, 'test@google.com'],
            [true, 'Alekseitest@yandex.ru'],
            [true, 'AlekWisebitssei@Google.com'],
            [false, 'Aleksei@mail.ru'],
            [false, '123@su.ru'],
        ];
    }
}
