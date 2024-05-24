<?php

declare(strict_types=1);

namespace Sunaoka\ProcessGuard\Tests;

use Sunaoka\ProcessGuard\Drivers\FileDriver;
use Sunaoka\ProcessGuard\Lock;
use Sunaoka\ProcessGuard\LockFactory;

/**
 * @coversDefaultClass Lock
 */
class LockTest extends TestCase
{
    /**
     * @test
     */
    public function acquire(): void
    {
        $driver = new FileDriver();
        $factory = new LockFactory($driver);

        $lock = $factory->create(__METHOD__);
        $lock->release();

        $actual = $lock->acquire();
        self::assertTrue($actual);

        $actual = $lock->acquire();
        self::assertFalse($actual);

        $lock->release();
    }

    /**
     * @test
     */
    public function expired(): void
    {
        $driver = new FileDriver();
        $factory = new LockFactory($driver);

        $lock = $factory->create(__METHOD__, 1);
        $lock->release();

        $actual = $lock->acquire();
        self::assertTrue($actual);

        touch($driver->getLockFilename(__METHOD__), time() - 2);

        $actual = $lock->acquire();
        self::assertTrue($actual);

        $lock->release();
    }
}
