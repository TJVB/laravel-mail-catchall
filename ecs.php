<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\Arrays\ArrayIndentSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\SpaceAfterCastSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff;
use PhpCsFixer\Fixer\ClassNotation\FinalClassFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use SlevomatCodingStandard\Sniffs\Classes\ClassConstantVisibilitySniff;
use SlevomatCodingStandard\Sniffs\Classes\ClassStructureSniff;
use SlevomatCodingStandard\Sniffs\Classes\MethodSpacingSniff;
use SlevomatCodingStandard\Sniffs\Commenting\EmptyCommentSniff;
use SlevomatCodingStandard\Sniffs\Functions\StaticClosureSniff;
use SlevomatCodingStandard\Sniffs\Functions\StrictCallSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\ReferenceUsedNamesOnlySniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UnusedUsesSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UselessAliasSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseSpacingSniff;
use SlevomatCodingStandard\Sniffs\Variables\DisallowSuperGlobalVariableSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // Sets have a combination of rules
    $ecsConfig->sets([
        SetList::CLEAN_CODE,
        SetList::PSR_12,
    ]);

    // Rules with a configuration (can be needed or to change the defaults)
    $ecsConfig->ruleWithConfiguration(UnusedUsesSniff::class, [
        'searchAnnotations' => true,
    ]);
    $ecsConfig->ruleWithConfiguration(ForbiddenFunctionsSniff::class,
        [
            'forbiddenFunctions' => [
                'sizeof' => 'count', //keep the default option
                'delete' => 'unset', //keep the default option
                'dump' => null, //debug statement
                'dd' => null, //debug statement
                'var_dump' => null, //debug statement
                'print_r' => null, //debug statement
                'exit' => null, //this means we also skip the middleware
            ]
        ])
    ;
    $ecsConfig->ruleWithConfiguration(ClassStructureSniff::class, [
        'groups' => [
            'uses',
            'constants',
            'enum cases',
            'properties',
            'constructor',
            'static constructors',
            'destructor',
            'magic methods',
            'all public methods',
            'all protected methods',
            'all private methods',
        ],
    ]);
    $ecsConfig->ruleWithConfiguration(ReferenceUsedNamesOnlySniff::class, [
        'searchAnnotations' => true,
        'allowFullyQualifiedNameForCollidingClasses' => true,
    ]);

    // Rules without config (defaults are good)
    $ecsConfig->rule(ArrayIndentSniff::class);
    $ecsConfig->rule(SpaceAfterCastSniff::class);
    $ecsConfig->rule(StaticClosureSniff::class);
    $ecsConfig->rule(DisallowSuperGlobalVariableSniff::class);
    $ecsConfig->rule(ClassConstantVisibilitySniff::class);
    $ecsConfig->rule(DeclareStrictTypesFixer::class);
    $ecsConfig->rule(NoExtraBlankLinesFixer::class);
    $ecsConfig->rule(FinalClassFixer::class);
    $ecsConfig->rule(MethodSpacingSniff::class);
    $ecsConfig->rule(UselessAliasSniff::class);
    $ecsConfig->rule(EmptyCommentSniff::class);
    $ecsConfig->rule(UseSpacingSniff::class);
    $ecsConfig->rule(StrictCallSniff::class);
    $ecsConfig->rule(PhpUnitConstructFixer::class);
    $ecsConfig->rule(PhpUnitDedicateAssertFixer::class);
};
