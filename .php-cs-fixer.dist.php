<?php

use Realodix\Relax\Config;
use Realodix\Relax\Finder;

$localRules = [
    // Relax
    'binary_operator_spaces' => [
        'operators' => [
            '=>' => 'align_single_space',
        ],
    ],
];

$finder = Finder::base()
    ->in(__DIR__)
    ->append(['.php-cs-fixer.dist.php']);

return Config::create('relax')
    ->setRules($localRules)
    ->setFinder($finder)
    ->setCacheFile(__DIR__.'/.tmp/.php-cs-fixer.cache');
