# ProcessGuard for PHP

This library has been designed with the intention of preventing the execution 
of multiple instances of a script.

In the event that it is intended for use in batch processing and a script is 
already running, all subsequent attempts at execution can be terminated 
until the current process completes.

## Installation

```php
composer require sunaoka/process-guard-php
```

## Basic Usage

```php
use Sunaoka\ProcessGuard\Drivers\FileDriver;
use Sunaoka\ProcessGuard\LockFactory;

$driver = new FileDriver();
$factory = new LockFactory($driver);

$lock = $factory->create('Preventing Multiple Instances', ttl: 60.0);
if ($lock->acquire() === false) {
    // already running
    exit;
}

// You can do some processing

$lock->release();
```
