<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    'no_unused_imports' => true,
];

$paths = [
    __DIR__ . DIRECTORY_SEPARATOR . 'src',
    __DIR__ . DIRECTORY_SEPARATOR . 'tests',
];

return (new Config())->setRules($rules)
    ->setHideProgress(false)
    ->setUsingCache(false)
    ->setFinder(Finder::create()->in($paths));
