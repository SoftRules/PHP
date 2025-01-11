<?php declare(strict_types=1);

use Rector\Arguments\Rector\ClassMethod\ArgumentAdderRector;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\If_\NullableCompareToNullRector;
use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\PropertyProperty\RemoveNullPropertyInitializationRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector;
use Rector\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;
use Rector\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;

/**
 * @see https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md
 */
return RectorConfig::configure()
    ->withCache(
        cacheDirectory: './.cache/rector',
        cacheClass: FileCacheStorage::class,
        containerCacheDirectory: './.cache/rectorContainer',
    )
    ->withRules([
        ParenthesizeNestedTernaryRector::class,
    ])
    ->withSkip([
        AddOverrideAttributeToOverriddenMethodsRector::class,
        ArgumentAdderRector::class,
        ClosureToArrowFunctionRector::class,
        DisallowedEmptyRuleFixerRector::class,
        EncapsedStringsToSprintfRector::class,
        ExplicitBoolCompareRector::class,
        FirstClassCallableRector::class => [
            //
        ],
        NullableCompareToNullRector::class,
        ReadOnlyClassRector::class => [
            //
        ],
        ReadOnlyPropertyRector::class => [
            //
        ],
        RemoveNullPropertyInitializationRector::class => [
            //
        ],
        RenameParamToMatchTypeRector::class,
        RenameVariableToMatchNewTypeRector::class,
        RestoreDefaultNullToNullableTypePropertyRector::class,
        SeparateMultiUseImportsRector::class,
        StaticArrowFunctionRector::class,
        StaticCallOnNonStaticToInstanceCallRector::class,
        StaticClosureRector::class,
        StringableForToStringRector::class => [
            //
        ],
    ])
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/config',
        __DIR__ . '/bootstrap',
    ])
    ->withParallel(300, 14, 14)
    // here we can define, what prepared sets of rules will be applied
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: false,
        typeDeclarations: true,
        privatization: false,
        naming: false,
        instanceOf: false,
        earlyReturn: true,
        strictBooleans: false,
        carbon: true,
        rectorPreset: true,
    )
    ->withMemoryLimit('3G')
    ->withPhpSets(php82: true);
