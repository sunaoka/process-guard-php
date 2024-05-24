<?php

declare(strict_types=1);

namespace Sunaoka\ProcessGuard\Tests\Drivers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Sunaoka\ProcessGuard\Drivers\FileDriver;
use Sunaoka\ProcessGuard\Tests\TestCase;

#[CoversClass(FileDriver::class)]
class FileDriverTest extends TestCase
{
    #[Test]
    public function save(): void
    {
        $key = __METHOD__;

        $driver = new FileDriver();
        $driver->remove($key);

        $actual = $driver->save($key);
        self::assertTrue($actual);
    }

    #[Test]
    public function save_failure(): void
    {
        $key = __METHOD__;

        $driver = new FileDriver();
        $driver->remove($key);

        $filename = $driver->getLockFilename($key);
        touch($filename);
        chmod($filename, 0000);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to save the lock file.');

        $driver->save($key);
    }

    #[Test]
    public function remove_exist(): void
    {
        $key = __METHOD__;

        $driver = new FileDriver();
        $driver->save($key);

        $actual = $driver->exists($key);
        self::assertTrue($actual);

        $driver->remove($key);

        $actual = $driver->exists($key);
        self::assertFalse($actual);
    }

    #[Test]
    public function remove_not_exist(): void
    {
        $key = __METHOD__;

        $driver = new FileDriver();
        $driver->remove($key);

        $actual = $driver->exists($key);
        self::assertFalse($actual);
    }

    #[Test]
    public function not_expired(): void
    {
        $key = __METHOD__;

        $driver = new FileDriver();

        $actual = $driver->expired($key, null);
        self::assertFalse($actual);
    }

    #[Test]
    public function expired(): void
    {
        $key = __METHOD__;

        $driver = new FileDriver();
        $filename = $driver->getLockFilename($key);
        touch($filename, time() - 2);

        $actual = $driver->expired($key, 1);
        self::assertTrue($actual);
    }

    #[Test]
    public function directory_cannot_be_created(): void
    {
        $path = '/operation/not/permitted';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Directory '{$path}' does not exists and cannot be created.");

        new FileDriver($path);
    }

    #[Test]
    public function directory_is_not_writable(): void
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'foo';
        if (is_dir($path)) {
            rmdir($path);
        }
        mkdir($path, 0000, true);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Directory '{$path}' is not writable.");

        new FileDriver($path);
    }
}
