<?php

declare(strict_types=1);


use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Log\NullLogger;
use Sunaoka\ProcessGuard\Drivers\FileDriver;
use Sunaoka\ProcessGuard\Lock;
use Sunaoka\ProcessGuard\LockFactory;
use Sunaoka\ProcessGuard\Tests\TestCase;

#[CoversClass(LockFactory::class)]
class LockFactoryTest extends TestCase
{
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
