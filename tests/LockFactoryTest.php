<?php

declare(strict_types=1);

namespace Sunaoka\ProcessGuard\Tests;

use Psr\Log\NullLogger;
use Sunaoka\ProcessGuard\Drivers\FileDriver;
use Sunaoka\ProcessGuard\Lock;
use Sunaoka\ProcessGuard\LockFactory;

/**
 * @coversDefaultClass LockFactory
 */
class LockFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function create(): void
    {
        $driver = new FileDriver();
        $factory = new LockFactory($driver);
        $factory->setLogger(new NullLogger());

        $actual = $factory->create(__METHOD__);
        self::assertInstanceOf(Lock::class, $actual);
    }
}
