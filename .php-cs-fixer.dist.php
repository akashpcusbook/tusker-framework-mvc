<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->ignoreDotFiles(true)
    ->ignoreVCSIgnored(true)
    ->exclude(['vendor'])
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        'ordered_imports' => false,
        'no_trailing_comma_in_singleline_array' => false,
        'trailing_comma_in_multiline' => false,
        'concat_space' => false,
        'cast_spaces' => false,
    ])
    ->setFinder($finder)
;

return $config;
