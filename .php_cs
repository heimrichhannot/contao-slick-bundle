<?php

$date = date('Y');

$header = <<<EOF
Copyright (c) $date Heimrich & Hannot GmbH

@license LGPL-3.0-or-later
EOF;

$finder = PhpCsFixer\Finder::create()
    ->exclude('Resources')
    ->exclude('Fixtures')
    ->in([__DIR__.'/src'])
    ->exclude('vendor')
    ->in([__DIR__])
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'psr0' => false,
        'strict_comparison' => false,
        'strict_param' => false,
        'array_syntax' => ['syntax' => 'short'],
        'heredoc_to_nowdoc' => true,
        'header_comment' => ['header' => $header],
        'ordered_imports' => true,
        'ordered_class_elements' => true,
        'php_unit_strict' => false,
        'phpdoc_order' => true,
        'no_useless_return' => true,
        'no_useless_else' => true,
        'no_unreachable_default_argument_value' => true,
        'combine_consecutive_unsets' => true,
        'general_phpdoc_annotation_remove' => [
            'expectedException',
            'expectedExceptionMessage',
        ],
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(false)
;
