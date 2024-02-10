<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('node_modules')
    ->exclude('public')
    ->exclude('app')
    ->exclude('var')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();

return $config->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2'                  => true,
        '@PhpCsFixer'            => true,
        '@Symfony'               => true,
        '@DoctrineAnnotation'    => true,
        'list_syntax'            => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'operators' => [
                '='  => 'align',
                '=>' => 'align',
                '+=' => 'align',
                '-=' => 'align',
                '*=' => 'align',
                '%=' => 'align',
                '.=' => 'align',
                '^=' => 'align',
            ],
        ],
        'yoda_style'       => false,
        'class_definition' => [
            'single_line' => false,
        ],
        'native_function_invocation'             => true,
        'native_function_casing'                 => true,
        'native_constant_invocation'             => true,
        'object_operator_without_whitespace'     => false,
        'php_unit_test_class_requires_covers'    => false,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'declare_strict_types'   => true,
        'single_line_empty_body' => false,
        'phpdoc_to_comment'      => false,
    ])
    ->setFinder($finder);
