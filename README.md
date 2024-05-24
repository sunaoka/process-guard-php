# ProcessGuard for PHP

[![Latest](https://poser.pugx.org/sunaoka/process-guard-php/v)](https://packagist.org/packages/sunaoka/process-guard-php)
[![License](https://poser.pugx.org/sunaoka/process-guard-php/license)](https://packagist.org/packages/sunaoka/process-guard-php)
[![PHP](https://img.shields.io/packagist/php-v/sunaoka/process-guard-php)](composer.json)
[![Test](https://github.com/sunaoka/process-guard-php/actions/workflows/test.yml/badge.svg)](https://github.com/sunaoka/process-guard-php/actions/workflows/test.yml)
[![codecov](https://codecov.io/gh/sunaoka/process-guard-php/branch/develop/graph/badge.svg)](https://codecov.io/gh/sunaoka/process-guard-php)

----

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
