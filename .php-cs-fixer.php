<?php

use PhpCsFixer\Config;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->notPath('bootstrap/cache')
    ->notPath('node_modules')
    ->notPath('vendor')
    ->notPath('storage')
    ->notName('*.blade.php')
    ->notName('_ide_helper*.php')
    ->ignoreVCS(true);

return (new Config())
    ->setFinder($finder);
