<?php

declare(strict_types=1);

namespace Sunaoka\ProcessGuard\Tests;

use PHPUnit\Framework\Attributes\Test;
use Psr\Log\NullLogger;
use Sunaoka\ProcessGuard\Drivers\FileDriver;
use Sunaoka\ProcessGuard\Lock;
use Sunaoka\ProcessGuard\LockFactory;

/**
 * @coversDefaultClass LockFactory
 * @covers \Sunaoka\ProcessGuard\LockFactory
 * @covers \Sunaoka\ProcessGuard\Drivers\FileDriver
 * @covers \Sunaoka\ProcessGuard\Lock
 */
class LockFactoryTest extends TestCase
{
    /**
     * @test
     */
    #[Test]
    public function create(): void
    {
        $driver = new FileDriver();
        $factory = new LockFactory($driver);
        $factory->setLogger(new NullLogger());

        $actual = $factory->create(__METHOD__);
        self::assertInstanceOf(Lock::class, $actual);
    }
}
