<?php

declare(strict_types=1);

$dirs = [
    __DIR__ . '/src',
    __DIR__ . '/tests',
];

$rules = [
    '@PER-CS2.0' => true,
    '@PHP80Migration' => true,
];

return (new PhpCsFixer\Config())
    ->setRules($rules)
    ->setIndent('    ')
    ->setLineEnding("\n")
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setFinder(PhpCsFixer\Finder::create()->in($dirs)->append([__FILE__]));
