<?php

declare(strict_types=1);

namespace Sunaoka\ProcessGuard;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class LockFactory implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(private DriverInterface $driver)
    {
    }

    public function create(string $key, ?float $ttl = null): LockInterface
    {
        $lock = new Lock($this->driver, $key, $ttl);
        if ($this->logger) {
            $lock->setLogger($this->logger);
        }

        return $lock;
    }
}
