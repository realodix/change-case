<?php

use PhpCsFixerCustomFixers\Fixer;

// PHP-CS-Fixer v3
// Based on https://docs.styleci.io/presets#laravel
$rules = [
    '@Symfony' => true,
    'array_indentation' => true,
    'compact_nullable_typehint' => true,
    'heredoc_to_nowdoc' => true,
    'increment_style' => ['style' => 'post'], // post_increment
    'multiline_whitespace_before_semicolons' => true, // no_multiline_whitespace_before_semicolons
    'no_alias_functions' => true, // @Symfony:risky, @PhpCsFixer:risky
    'no_extra_blank_lines' => [
        'tokens' => [
            'extra',
            'throw', // no_blank_lines_after_throw
            'use', // no_blank_lines_between_imports
            'use_trait' // no_blank_lines_between_traits
        ]
    ],
    'no_spaces_around_offset' => ['positions' => ['inside']], // no_spaces_inside_offset
    'no_unreachable_default_argument_value' => true, // @PhpCsFixer:risky
    'no_useless_return' => true,
    'no_whitespace_in_blank_line' => true,
    'not_operator_with_successor_space' => true,
    'psr_autoloading' => true, // @Symfony:risky, @PhpCsFixer:risky
    'self_accessor' => true, // @Symfony:risky, @PhpCsFixer:risky
    'simplified_null_return' => true,

    'new_with_braces' => false,
    'no_break_comment' => false,
    'no_empty_comment' => false,
    'no_superfluous_phpdoc_tags' => false,
    'php_unit_fqcn_annotation' => false,
    'phpdoc_annotation_without_dot' => false,
    'phpdoc_return_self_reference' => false,
    'phpdoc_separation' => false,
    'phpdoc_to_comment' => false,
    'phpdoc_trim_consecutive_blank_line_separation' => false,
    'phpdoc_types_order' => false,
    'semicolon_after_instruction' => false,
    'single_line_throw' => false,
    'single_trait_insert_per_statement' => false,
    'standardize_increment' => false,
    'yoda_style' => false,

    // Custom rules
    'binary_operator_spaces' => ['operators' => ['=>' => 'align']], // unalign_equals (default)
    'no_empty_phpdoc' => false,
    'phpdoc_summary' => false,
    'ternary_operator_spaces' => false,
    'unary_operator_spaces' => false,
    'visibility_required' => ['elements' => ['property', 'method']],

    // Additional rules
    'align_multiline_comment' => ['comment_type' => 'all_multiline'],
    'compact_nullable_typehint' => true,
    'fully_qualified_strict_types' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'php_unit_method_casing' => true,
    'phpdoc_align' => [ // align_phpdoc
        'tags' => [
            'param',
            // 'return',
            'throws','type','var'
        ]
    ],
    'phpdoc_to_comment' => true,
    'phpdoc_var_annotation_correct_order' => true,

    Fixer\CommentedOutFunctionFixer::name() => true,
    Fixer\CommentSurroundedBySpacesFixer::name() => true,
    Fixer\DataProviderNameFixer::name() => ['prefix' => '', 'suffix' => 'Provider'],
    Fixer\MultilineCommentOpeningClosingAloneFixer::name() => true,
    Fixer\NoDuplicatedArrayKeyFixer::name() => true,
    Fixer\NoDuplicatedImportsFixer::name() => true,
    Fixer\NoPhpStormGeneratedCommentFixer::name() => true,
    Fixer\NoSuperfluousConcatenationFixer::name() => true,
    Fixer\NoUselessCommentFixer::name() => true,
    Fixer\NoUselessDoctrineRepositoryCommentFixer::name() => true,
    Fixer\NoUselessParenthesisFixer::name() => true,
    Fixer\NoUselessStrlenFixer::name() => true,
    Fixer\PhpdocNoIncorrectVarAnnotationFixer::name() => true,
    Fixer\PhpdocNoSuperfluousParamFixer::name() => true,
    Fixer\PhpdocParamOrderFixer::name() => true,
    Fixer\PhpdocParamTypeFixer::name() => true,
    Fixer\PhpdocSelfAccessorFixer::name() => true,
    Fixer\PhpdocSingleLineVarFixer::name() => true,
    Fixer\PhpdocTypesTrimFixer::name() => true,
    Fixer\PhpUnitNoUselessReturnFixer::name() => true,
    Fixer\SingleSpaceAfterStatementFixer::name() => true,
    Fixer\SingleSpaceBeforeStatementFixer::name() => true,
    // Fixer\OperatorLinebreakFixer::name() => true,
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->notName('.phpstorm.meta.php')
    ->notName('_ide_*.php');

$config = new PhpCsFixer\Config();
return $config
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setLineEnding("\r\n")
    ->setFinder($finder);
