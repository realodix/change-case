<?php

use Realodix\CsConfig\Factory;
use Realodix\CsConfig\RuleSet;

$overrideRules = [
    // ..
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

return Factory::fromRuleSet(new RuleSet\RealodixPlus)->setFinder($finder);
