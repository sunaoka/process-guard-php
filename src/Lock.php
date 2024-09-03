<?php

declare(strict_types=1);

namespace Sunaoka\ProcessGuard;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Lock implements LockInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private DriverInterface $driver,
        private string $key,
        private ?float $ttl = null
    ) {}

    public function acquire(): bool
    {
        if ($this->driver->exists($this->key) === true &&
            $this->driver->expired($this->key, $this->ttl) === false) {
            return false;
        }

        return $this->driver->save($this->key);
    }

    public function release(): void
    {
        $this->driver->remove($this->key);
    }
}
