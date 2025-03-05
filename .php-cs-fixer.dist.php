<?php

use Realodix\Relax\Config;
use Realodix\Relax\Finder;

$localRules = [
    // ...
];

$finder = Finder::base()
    ->in(__DIR__)
    ->append(['.php-cs-fixer.dist.php']);

return Config::create('relax')
    ->setRules($localRules)
    ->setFinder($finder)
    ->setCacheFile(__DIR__.'/.tmp/.php-cs-fixer.cache');
