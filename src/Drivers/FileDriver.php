<?php

declare(strict_types=1);

namespace Sunaoka\ProcessGuard\Drivers;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Sunaoka\ProcessGuard\DriverInterface;

class FileDriver implements DriverInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private string $path;

    public function __construct(?string $path = null)
    {
        $path ??= sys_get_temp_dir();

        if (is_dir($path) === false && @mkdir($path, 0777, true) === false && is_dir($path) === false) {
            throw new \InvalidArgumentException("Directory '{$path}' does not exists and cannot be created.");
        }

        if (is_writable($path) === false) {
            throw new \InvalidArgumentException("Directory '{$path}' is not writable.");
        }

        $this->path = $path;
    }

    public function getLockFilename(string $key): string
    {
        return $this->path . DIRECTORY_SEPARATOR . preg_replace('/[^a-z0-9._-]+/i', '-', $key) . '.lock';
    }

    public function save(string $key): bool
    {
        $size = @file_put_contents($this->getLockFilename($key), getmypid(), LOCK_EX);
        if ($size === 0 || $size === false) {
            throw new \RuntimeException('Unable to save the lock file.');
        }

        return true;
    }

    public function remove(string $key): void
    {
        if ($this->exists($key)) {
            unlink($this->getLockFilename($key));
        }
    }

    public function exists(string $key): bool
    {
        $this->clearStatCache($key);

        return file_exists($this->getLockFilename($key));
    }

    public function expired(string $key, ?float $ttl = null): bool
    {
        if ($ttl === null) {
            return false;
        }

        $this->clearStatCache($key);

        return microtime(true) - filemtime($this->getLockFilename($key)) > $ttl;
    }

    private function clearStatCache(string $key): void
    {
        clearstatcache(true, $this->getLockFilename($key));
    }
}
