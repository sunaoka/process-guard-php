<?php

declare(strict_types=1);

namespace Sunaoka\ProcessGuard;

interface LockInterface
{
    public function acquire(): bool;

    public function release(): void;
}
