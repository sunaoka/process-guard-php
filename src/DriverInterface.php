<?php

declare(strict_types=1);

namespace Sunaoka\ProcessGuard;

interface DriverInterface
{
    public function save(string $key): bool;

    public function remove(string $key): void;

    public function exists(string $key): bool;

    public function expired(string $key, ?float $ttl = null): bool;
}
